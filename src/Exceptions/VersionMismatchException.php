<?php
/** 
 * Exception thrown when there is a command called that is not available in 
 * the configured version of the Electrum wallet. 
 *  
 * @package OGBitBlt\Electrum
 * @author Anthony Davis <anthonyjohndavis@outlook.com>
 * @version $$ 
 * @access private  
 */
namespace OGBitBlt\Electrum\Exceptions;

use Exception;
use Throwable;

class VersionMismatchException extends Exception
{
    public function __construct(string $message, int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message,$code,$previous);
    }
}
?>