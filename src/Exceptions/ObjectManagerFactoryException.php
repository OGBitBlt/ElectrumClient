<?php
/** 
 * Exception thrown when an error occurs in the ObjectManagerFactory typically
 * due to calling it on a class it's not configured for.
 *  
 * @package OGBitBlt\Electrum
 * @author Anthony Davis <anthonyjohndavis@outlook.com>
 * @version $$ 
 * @access private  
 */
namespace OGBitBlt\Electrum\Exceptions;

use Exception;
use Throwable;

class ObjectManagerFactoryException extends Exception
{
    public function __construct($className, $code = 0, Throwable $previous = null){

        parent::__construct(sprintf("Unknown Object Manager : %s",$className), $code, $previous);
    }
}
?>