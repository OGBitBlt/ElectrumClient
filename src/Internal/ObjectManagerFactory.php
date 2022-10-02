<?php
namespace OGBitBlt\Electrum;
use OGBitBlt\Electrum\AbstractObjectManagerFactory;
use OGBitBlt\Electrum\Exceptions\ObjectManagerFactoryException;
use OGBitBlt\Electrum\IObjectManagerFactoryInterface;

/** 
 * Creates object manager objects. 
 *  
 * @package OGBitBlt\Electrum
 * @author Anthony Davis <anthonyjohndavis@outlook.com>
 * @version $$ 
 * @access private  
 */
class ObjectManagerFactory 
                            extends AbstractObjectManagerFactory 
                            implements IObjectManagerFactoryInterface
{
    /**
     * @var $versionManager - VersionManager object calls initialization functions so we only create it once
     */
    private static $versionManager = null;

    /**
     * Creates the object manager 
     * @param string $className - The name of the class of the ObjectManager to create
     * @param ElectrumClient $client - The client object used to create the ObjectManager
     * @return The new ObjectManager created
     */
    public static function Create(
            string $className, \OGBitBlt\Electrum\ElectrumClient $client
    ) : IObjectManagerInterface
    {
        Diagnostics::Trace(sprintf("%s(%s)",__METHOD__,$className), Diagnostics::ERROR_LEVEL_TRACE);
        switch($className)
        {
            case 'OGBitBlt\Electrum\AddressManager': 
                return new \OGBitBlt\Electrum\AddressManager($client);
            case 'OGBitBlt\Electrum\VersionManager':
                if(self::$versionManager == null) { 
                    Diagnostics::Trace(
                        sprintf(
                            "%s(%s) => new VersionManager()",
                            __METHOD__,
                            $className
                        ), 
                        Diagnostics::ERROR_LEVEL_TRACE
                    );
                    self::$versionManager = new \OGBitBlt\Electrum\VersionManager($client);
                }
                return self::$versionManager;
            case 'OGBitBlt\Electrum\TransactionManager'   : 
                return new \OGBitBlt\Electrum\TransactionManager($client);
            case 'OGBitBlt\Electrum\WalletManager'        : 
                return new \OGBitBlt\Electrum\WalletManager($client);
            case 'OGBitBlt\Electrum\InitializationManager': 
                return new \OGBitBlt\Electrum\InitializationManager($client);
            case 'OGBitBlt\Electrum\PaymentManager':
                return new \OGBitBlt\Electrum\PaymentManager($client);
            default:
                throw new ObjectManagerFactoryException($className);
        }
    }
}
?>