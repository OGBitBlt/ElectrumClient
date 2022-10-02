<?php
namespace OGBitBlt\Electrum;

use OGBitBlt\Electrum\ElectrumClient;

/** 
 * Interface for the object manager
 *  
 * @package OGBitBlt\Electrum
 * @author Anthony Davis <anthonyjohndavis@outlook.com>
 * @version $$ 
 * @access private  
 */
interface IObjectManagerFactoryInterface 
{
    /**
     * Create -- 
     * @param string $className The name of the class of object manager to create
     * @param ElectrumClient $client The client object used to create the object manager
     * @return an object manager that implements the IObjectManagerInterface interface
     */
    public static function Create(string $className, ElectrumClient $client) : IObjectManagerInterface;
}
?>