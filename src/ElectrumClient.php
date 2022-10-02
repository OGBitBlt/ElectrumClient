<?php
/** 
 * This library provides a simple class that encapsulates Electrum RPC command
 * calls so that you can collect payments in bitcoin without having to write
 * the code for interfacing directly with the Electrum wallet.
 * While the library can be easily used on its own, the design approach is for
 * this library to be a subsystem to a larger bitcoin transaction processing 
 * interface. 
 *  
 * @package OGBitBlt\Electrum
 * @author Anthony Davis <anthonyjohndavis@outlook.com>
 * @version $ $ 
 * @access public  
 * 
 * See the README for instructions on how to setup Electrum to run as a daemon
 * and expose the JSON RPC interface that this library will call.
 * 
 * More information on the JSON RPC commands can be found at the links below: 
 * 		https://electrum.readthedocs.io/en/latest/cmdline.html
 * 				and
 * 		https://electrum.readthedocs.io/en/latest/jsonrpc.html
 * 
 */
namespace OGBitBlt\Electrum;
use Exception;
use OGBitBlt\Electrum\Diagnostics;
use OGBitBlt\Electrum\ObjectManagerFactory;
use OGBitBlt\Electrum\Request\IRequestInterface;
use OGBitBlt\Electrum\Response\BaseResponse;
use OGBitBlt\Electrum\ElectrumConnectionInfo;
use OGBitBlt\Electrum\Exceptions\ElectrumClientConfigurationException;
use OGBitBlt\Electrum\Exceptions\VersionMismatchException;
use OGBitBlt\Electrum\Response\IResponseInterface;

/**
 * @package OGBbitBlt\Electrum\ElectrumClient
 */
class ElectrumClient
{
	private $_commandList 		= []; 	// list of supported electrum commands
	private $_electrumConnInfo 	= null;	// RPC connection info to electrum wallet
	private $_errorInfoStack 	= [];	// array of ErrorInfo objects 
	private $_diagnostics 		= null;	// used for logging diagnostics info

	/**
	 * pushErrorInfo --
	 * @param $errorInfo ErrorInfo object to add to the error info stack.
	 * return ElectrumClient 
	 */
	public function pushErrorInfo(\OGBitBlt\Electrum\ErrorInfo $errorInfo) : self
	{
		$this->TRACE(sprintf("%s(%s)",__METHOD__, $errorInfo->getMessage()));
		array_unshift($this->_errorInfoStack, $errorInfo);
		return $this;
	}

	/**
	 * getLastError --
	 * Pop's the last ErrorInfo object off of the error stack, 
	 * which contains error result from the last RPC call made
	 * to the electrum wallet.
	 * return ErrorInfo object
	 */
	public function getLastError() : \OGBitBlt\Electrum\ErrorInfo
	{
		$this->TRACE(sprintf("%s(%s)",__METHOD__,""));
		return array_shift($this->_errorInfoStack);
	}

	/**
	 * @return the ElectrumConnectionInfo object
	 */
	public function getConnection() : ElectrumConnectionInfo
	{
		$this->TRACE(sprintf("%s(%s)",__METHOD__,""));
		return $this->_electrumConnectionInfo;
	}

	/**
	 * Returns the object manager which allows the user to execute 
	 * commands against the ElectrumClient object.
	 * @param string $className class name of the object manager to be created.
	 */
	public function getManager(string $className)
	{
		$this->TRACE(sprintf("%s(%s)",__METHOD__, $className));
		return ObjectManagerFactory::Create($className, $this);
	}

	/**
	 * @return Returns an array of commands supported by Electrum wallet
	 */
	public function getCommandList() : array
	{
		$this->TRACE(sprintf("%s(%s)",__METHOD__,""));
		return $this->_commandList;
	}

	public function set_internal_diagnostics_flags(int $flags)
	{
		Diagnostics::setFlag($flags);
		$this->TRACE(sprintf("set diagnostics flags : %d",$flags));
	}

	private function TRACE(string $message) : void
	{
		Diagnostics::Trace($message, Diagnostics::ERROR_LEVEL_TRACE);
	}

	private function ERROR(string $message) : void
	{
		Diagnostics::Trace($message, Diagnostics::ERROR_LEVEL_CRITICAL);
	}

	private function WARNING(string $message) : void 
	{
		Diagnostics::Trace($message, Diagnostics::ERROR_LEVEL_WARN);
	}

	/**
	 * __construct --
	 * 
	 * Public constructor function, creates a new object of type
	 * ElectrumClient. See https://electrum.readthedocs.io/en/latest/jsonrpc.html#
	 * for more information on the parameters.
	 * 
	 * @param string $user Value set in electrum getconfig rpcuser
	 * @param string $password Value set in electrum getconfig rpcpassword
	 * @param string (optional) $host Host server where electrum JSONRPC command 
	 * 						interface is setup and running.
	 * @param int (optional) $port Port number set in electrum getconfig rpcport
	 * @param bool (optional) $useSSL if using https set to true.
	 */
	public function __construct(
								string 	$user, 
								string 	$password, 
								string 	$host 		= 'localhost', 
								int 	$port 		= 7777, 
								bool 	$useSSL 	= false
								)
	{
		$this->_electrumConnectionInfo = 
			new ElectrumConnectionInfo(
				$user, 
				$password, 
				$host, 
				$port, 
				$useSSL
			)
		;
	}

	/**
	 * Executes the Electrum RPC call using curl. 
	 * @param $request holds the IRequestInterface object for making the RPC call
	 * @return IResponseInterface object containing the response info
	 */
	public function ExecuteRequest(IRequestInterface $request) : IResponseInterface
	{
		$this->TRACE(sprintf("%s(%s)",__METHOD__, $request->getMethodName()));

		$errorInfo = new \OGBitBlt\Electrum\ErrorInfo();
		$curl_response = "";

		// ignore the command "commands" because that is called from 
		// Init() which populates the command list.
		if($request->getMethodName() !== "commands") {
			// if the command list is zero we have not been initialized
			// or initialization failed due to being configured incorrectly
			// (init only returns false on configuraiton errors)
			if(count($this->_commandList) <= 1) 
				throw new ElectrumClientConfigurationException("client object has not been initialized or is incorrectly configured.");
			
			$this->TRACE(sprintf("Count of commands is %d", count($this->_commandList)));
			// command names have been known to be removed or change on each
			// release of electrum so to safe block us from calling any commands 
			// that do not exist, if the command we are looking for does not
			// exist in the command list we will simply throw an exception here
			// to notify the users that their version of electrum does not 
			// support the command.
			if(!in_array($request->getMethodName(), $this->_commandList)) {
				$this->TRACE(
					sprintf(
						"Looking for command '%s' in commands: %s", 
						$request->getMethodName(),
						implode(',',$this->_commandList)
					)
				);
				throw new VersionMismatchException(
					sprintf(
						"Command: '%s' is not available in the configured Electrum version",
						$request->getMethodName()
					)
				);
			}
		}

		$handle = curl_init($this->getConnection()->getUrl());		
		if($handle == false) {
			$errorInfo
				->setMessage("unable to contact server")
				->setCode(IResponseInterface::RESPONSE_CODE_OK)
				;
			$this->pushErrorInfo($errorInfo);
			throw new ElectrumClientConfigurationException(
				sprintf(
					"Failed to initialize curl with '%s' for command '%s'", 
					$this->getConnection()->getUrl(), 
					$request->getMethodName()
				)
			);
		} else {
			$options = array(
				CURLOPT_POST => true, 
				CURLOPT_POSTFIELDS => json_encode($request->getPayload()),
				CURLOPT_HTTPHEADER => ["Content-type: application/json"],
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
				CURLOPT_USERPWD => $this->getConnection()->getBasicAuthCredentials(),
				CURLOPT_CONNECTTIMEOUT => 5,
				CURLOPT_TIMEOUT => 5
			);
			if(curl_setopt_array($handle, $options) == false) {
				$errorInfo
					->setMessage("error setting curl options")
					->setCode(IResponseInterface::RESPONSE_CODE_CURL_ERROR)
					;
				$this->pushErrorInfo($errorInfo);
				throw new ElectrumClientConfigurationException(
					sprintf(
						"Error setting curl options [%s] for command '%s'", 
						implode(',',$options), 
						$request->getMethodName()
					)
				);
			} else {
				$curl_response = curl_exec($handle);
				if(curl_error($handle)) {
					$errorInfo
						->setMessage(curl_error($handle))
						->setCode(IResponseInterface::RESPONSE_CODE_CURL_ERROR)
						;
					$this->pushErrorInfo($errorInfo);
					throw new ElectrumClientConfigurationException(
						sprintf(
						"Executing curl command '%s' returned error : '%s'", 
						$request->getMethodName(),
						$errorInfo->getMessage())
					);
				}
			}
		}
		curl_close($handle);

		// store the last error info for client access 
		$this->pushErrorInfo($errorInfo);
		
		return new BaseResponse(
			$request,
			$curl_response,
			$errorInfo->getCode(),
			$errorInfo->getMessage()
		);
	}

	/**
	 * Initializes the ElectrumClient object
	 * @return	bool	true on success and false otherwise
	 */
	public function Init() : bool
	{
		$this->TRACE(sprintf("%s(%s)",__METHOD__,""));

		$result = false;
		if(($this->_commandList = $this->getManager(InitializationManager::class)->GetCommandList())!==null) {
			if(count($this->_commandList) > 1) {
				
				$this->TRACE("ElectrumClient::Init => Command List Initialized");
				$this->TRACE(sprintf("Command List: %s", implode(',',$this->_commandList)));

				// wipe out any past error messages incase this object is being re-used
				$this->_errorInfoStack = [];

				$versionManager = $this->getManager(VersionManager::class);
				
				// @TODO: throw an exception if this command does not complete so that the user
				// knows that this object is not configured correctly
				if($versionManager->GetElectrumVersion() == null) {
					throw new ElectrumClientConfigurationException("Electrum version was returned null in function ElectrumClient::Init.");
				}
				$this->TRACE("ElectrumClient::Init => Loaded Electrum Version Info");

				// Deprecations introduced in Electrum version 4.
				if($versionManager->GetVersionInfo(VersionManager::MAJOR_VERSION) >= 4) { 
					if($this->getConnection()->isUseSSL() == true) {
						throw new VersionMismatchException("SSL functionality was removed in Electrum 4");
					}
				}
				// @TODO: Any future version specific deprecations should be added here.
				// Add the version in which they were deprecated.
				$result = true;
			} else {
				throw new ElectrumClientConfigurationException("Configuration Error: Electrum Command List is empty in function ElectrumClient::Init");
			}
		} else {
			throw new ElectrumCLientConfigurationException("Attempting to initialize the Electrum Command list returned null in function ElectrumClient::Init");
		}
		return $result;
	}
}
?>
