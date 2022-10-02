<?php 
namespace OGBitBlt\Electrum\Request;

use OGBitBlt\Electrum\Response\IResponseInterface;

/** 
 * The base object for creating request objects
 * @package OGBitBlt\Electrum
 * @author Anthony Davis <anthonyjohndavis@outlook.com>
 * @version $ $ 
 * @access private  
 */
abstract class AbstractRequest implements IRequestInterface
{
    /**
     * @var $_method - holds the name of the command to be called on the Electrum object
     */
    protected $_method;
    /**
     * @var $_params - an array of parameters to be passed to the command when called
     */
    protected $_params;
    /**
     * @var $_client - the client object for the request
     */
    protected $_client;

    /** 
     * constructor function
     * @param   ElectrumClient  $client The client object to execute the request on
     * @param   string          $method The name of the electrum command to execute
     * @param   array           $params The parameters to pass to the command
     */
    public function __construct(
        \OGBitBlt\Electrum\ElectrumClient $client, 
        string $method, 
        array $params
        )
    {
        $this->_method = $method;
        $this->_params = $params;
        $this->_client = $client;
    }

    /**
     * getPayload 
     * @return returns an array that can be used as a payload parameter to curl
     */
    public function getPayload() : array
    {
        return array(
			"id" => "curltext", 
			"method" => $this->_method, 
			"params" => $this->_params
        );
    }

    /**
     * getMethodName
     * @return the name of the command to be executed on the Electrum object
     */
    public function getMethodName()
    {
        return $this->_method;
    }

    public function ExecuteRequest() : IResponseInterface
    {
        return $this->_client->ExecuteRequest($this);
    }
}
?>