<?php
/** 
 * Exception thrown when an error occurs due to the Electrum wallet being 
 * incorrectly configured. 
 *  
 * @package OGBitBlt\Electrum
 * @author Anthony Davis <anthonyjohndavis@outlook.com>
 * @version $$ 
 * @access private  
 */
namespace OGBitBlt\Electrum\Exceptions;

use Exception;
use Throwable;

class ElectrumClientConfigurationException extends Exception
{
    public function __construct(string $message, int $code=0, Throwable $previous = null)
    {
        parent::__construct($message,$code,$previous);
    }
}
?>