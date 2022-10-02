<?php
namespace OGBitBlt\Electrum;

use Exception;
use OGBitBlt\Electrum\AbstractObjectManager;
use OGBitBlt\Electrum\IObjectManagerInterface;
use OGBitBlt\Electrum\Request\Version\VersionRequest;

/** 
 * Provides functions for the Version commands 
 * @package OGBitBlt\Electrum
 * @author Anthony Davis <anthonyjohndavis@outlook.com>
 * @version $$ 
 * @access private  
 */
class VersionManager extends AbstractObjectManager implements IObjectManagerInterface
{
    /**
     * @var $_electrum_version string that holds the version info string: major.minor.revision 
     */
    private $_electrum_version = null;
    /**
     * @var $_version_info array that holds the version info ['major' => 1, 'minor' => 2, 'revision' => 3]
     */
    private $_version_info = [];
    /**
     * @var pass to GetVersionInfo to return the major version number
     */
    const MAJOR_VERSION = 'major';
    /**
     * @var pass to GetVersionInfo to return the minor version number
     */
    const MINOR_VERSION = 'minor';
    /**
     * @var pass to the GetVersionInfo to return the revision number
     */
    const REVISION_NUMBER = 'revision';

    /**
	 * Get the electrum version number
	 * @return string The current version number from electrum
	 */
	public function GetElectrumVersion(): ?string 
	{
        Diagnostics::Trace(sprintf("%s",__METHOD__), Diagnostics::ERROR_LEVEL_TRACE);
		if($this->_electrum_version == null) {
            Diagnostics::Trace("Initializing Electrum Version", Diagnostics::ERROR_LEVEL_TRACE);
			$this->_electrum_version = (new VersionRequest($this->getClient()))->ExecuteRequest()->getResult();
			$a0 = explode('.',$this->_electrum_version);
			$this->_version_info = [
				self::MAJOR_VERSION => intval($a0[0],10),
				self::MINOR_VERSION => intval($a0[1],10),
				self::REVISION_NUMBER => intval($a0[2],10)
			];
		}
        Diagnostics::Trace(sprintf("Electrum Version : %s",$this->_electrum_version), Diagnostics::ERROR_LEVEL_TRACE);
		return $this->_electrum_version;
	}

    /**
     * GetVersionInfo returns specific version information
     * @param string $versionInfo The string specifying the version info to be returned
     */
    public function GetVersionInfo(string $versionInfo)
    {
        Diagnostics::Trace(
            sprintf(
                "%s(%s)",
                __METHOD__,
                $versionInfo
            ), 
            Diagnostics::ERROR_LEVEL_TRACE
        );   
        switch($versionInfo)
        {
            case self::MAJOR_VERSION:
            case self::MINOR_VERSION:
            case self::REVISION_NUMBER:
                return $this->_version_info[$versionInfo];
            default:
                throw new Exception("Invalid version info parameter.");
        }
    }


    public function __construct(\OGBitBlt\Electrum\ElectrumClient $client)
    {
        parent::__construct($client);
    }
}
?>