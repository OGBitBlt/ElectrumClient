<?php
namespace OGBitBlt\Electrum;

use OGBitBlt\Electrum\Response\IResponseInterface;

/**
 * Object for storing information about the result of the 
 * most recently executed RPC call to the Electrum wallet
 * @package OGBitBlt\Electrum
 * @author Anthony Davis <anthonyjohndavis@outlook.com>
 * @version $ $ 
 * @access public  
 */
class ErrorInfo
{
    private $_error_code;
    private $_error_message = IResponseInterface::RESPONSE_CODE_NONE;

    /**
     * Creates a new ErrorInfo object
     * @param   string  $message    User friendly error message
     * @param   int     $code       Error code from IResponseInterface
     */
    public function __construct(
        string $message = 'success', 
        int $code = IResponseInterface::RESPONSE_CODE_NONE
        )
    {
        $this->_error_code = $code;
        $this->_error_message = $message;
    }

    /**
     * @return  int the error code from IResponseInterface::RESPONSE_CODE_xxx
     */
    public function getCode() : int
    {
        return $this->_error_code;
    }

    public function setCode(int $code) : self
    {
        $this->_error_code = $code;
        return $this;
    }

    /**
     * @return  string  a user friendly error message
     */
    public function getMessage() : string 
    {
        return $this->_error_message;
    }

    public function setMessage(string $message) : self
    {
        $this->_error_message = $message;
        return $this;
    }
}
?>