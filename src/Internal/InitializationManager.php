<?php
namespace OGBitBlt\Electrum;

use OGBitBlt\Electrum\AbstractObjectManager;
use OGBitBlt\Electrum\IObjectManagerInterface;
use OGBitBlt\Electrum\Request\Initialize\InitializeRequest;

/** 
 * Initialization manager
 * Provides all the functions related to initalizing the library
 *  
 * @package OGBitBlt\Electrum
 * @author Anthony Davis <anthonyjohndavis@outlook.com>
 * @version $$ 
 * @access private  
 */
class InitializationManager extends AbstractObjectManager implements IObjectManagerInterface
{
    /**
     * GetCommandList -- 
     * @return array List of commands supported by the version of Electrum
     */
    public function GetCommandList() : array
    {
        return explode(" ",
            (new InitializeRequest($this->getClient()))
                ->ExecuteRequest()
                ->getResult()
        );
    }

    public function __construct(\OGBitBlt\Electrum\ElectrumClient $client)
    {
        parent::__construct($client);
    }
}
?>