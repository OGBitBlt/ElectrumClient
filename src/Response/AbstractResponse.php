<?php
namespace OGBitBlt\Electrum\Response;

use OGBitBlt\Electrum\ErrorInfo;
use OGBitBlt\Electrum\Request\IRequestInterface;

/** 
 * Object that all the Response clases will extend
 * @package OGBitBlt\Electrum
 * @author Anthony Davis <anthonyjohndavis@outlook.com>
 * @version $$ 
 * @access private  
 */
abstract class AbstractResponse implements IResponseInterface
{
	private $_request;                              // a copy of the request
    private $_errorInfo = null;                     // error info object
	private $_data;                                 // full curl response 
	private $_result;                               // rpc command result

    /**
     * __construct  Creates a new CommandResponse object
     * 
     * @param   IRequestInterface   $request    The request object
     * @param   string              $data       The full curl response
     * @param   int                 $code       One of the error code constants
     * @param   string              $message    User friendly error info
     * @return  void
     */
	public function __construct(
		\OGBitBlt\Electrum\Request\IRequestInterface $request, 
		string $data, 
		int $code = self::RESPONSE_CODE_OK, 
		string $message = null 
		) 
	{
        $this->_request = $request;
        $this->_errorInfo = new \OGBitBlt\Electrum\ErrorInfo($message, $code);
        $this->_data = $data;
        if($this->_data != null && $this->_data != '') {
            $tmp = json_decode($data, true);
            if(is_array($tmp)) {
                if(isset($tmp["result"])) {
                    $this->_result = $tmp["result"];
                }
            }
        }
	}

    /**
     * Returns the IRequestInterface object that 
     * created the IResponseInterface object 
     */
    public function getRequest() : \OGBitBlt\Electrum\Request\IRequestInterface
    {
        return $this->_request;
    }

    /**
     * Returns an ErrorInfo object containing the
     * result of the last completed operation
     */
    public function getLastError() : \OGBitBlt\Electrum\ErrorInfo
    {
        return $this->_errorInfo;
    }

    /**
     * Returns the raw (unformatted) curl method result
     */
    public function getData() : string
    {
        return $this->_data;
    }

    /**
     * Returns the result section of the curl response
     */
    public function getResult() : mixed
    {
        return $this->_result;
    }
}
?>