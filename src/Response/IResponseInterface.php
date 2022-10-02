<?php
namespace OGBitBlt\Electrum\Response;
/** 
 * Defines the interface for the response objects
 * @package OGBitBlt\Electrum
 * @author Anthony Davis <anthonyjohndavis@outlook.com>
 * @version $$ 
 * @access private  
 */
interface IResponseInterface 
{
    /**
     * Initial status for response codes.
     */
    const RESPONSE_CODE_NONE = -1;              
    
    /**
     * Everything is okay
     */
    const RESPONSE_CODE_OK = 1;

    /**
     * Server returned an error
     */
	const RESPONCE_CODE_ERROR = 0;              

    /**
     * Could not reach the server
     */
	const RESPONSE_CODE_SERVER_UNAVAILABLE = 2; 

    /**
     * Error either initializing or executing a CURL call
     */
	const RESPONSE_CODE_CURL_ERROR = 3;      

    /**
     * Returns the IRequestInterface object that 
     * created the IResponseInterface object 
     */
    public function getRequest();

    /**
     * Returns an ErrorInfo object containing the
     * result of the last completed operation
     */
    public function getLastError();             

    /**
     * Returns the raw (unformatted) curl method result
     */
    public function getData();

    /**
     * Returns the result section of the curl response
     */
    public function getResult();
}
?>