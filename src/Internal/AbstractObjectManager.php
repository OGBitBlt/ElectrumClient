<?php
/** 
 * Abstract class for an ObjectManager
 *  
 * @package OGBitBlt\Electrum
 * @author Anthony Davis <anthonyjohndavis@outlook.com>
 * @version $$ 
 * @access public  
 */
namespace OGBitBlt\Electrum;
use OGBitBlt\Electrum\ElectrumClient;

abstract class AbstractObjectManager 
{
    /**
     * @var OGBitBlt\ElectrumClient $_client holds the ElectrumClient object 
     * for the object manager
     */
    protected $_client;

    /**
     * __construct --
     * @param ElectrumClient $client the client object
     * @return void
     */
    public function __construct(ElectrumClient $client)
    {
        $this->_client = $client;
    }

    /**
     * getClient --
     * return OGBitBlt\ElectrumClient
     */
    public function getClient() : ElectrumClient
    {
        return $this->_client;
    }
}
?>