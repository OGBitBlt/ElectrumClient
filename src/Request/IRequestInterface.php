<?php
namespace OGBitBlt\Electrum\Request;
/** 
 * Interface for creating new Request objects
 * @package OGBitBlt\Electrum
 * @author Anthony Davis <anthonyjohndavis@outlook.com>
 * @version $ $ 
 * @access private  
 */
interface IRequestInterface 
{
    /**
     * Executes the request
     */
    public function ExecuteRequest();
    /**
     * @return string The name of the command to be executed in Electrum
     */
    public function getMethodName();
    /**
     * @return array An array formatted as curl parameters
     */
    public function getPayload();
}
?>