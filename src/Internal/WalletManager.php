<?php
namespace OGBitBlt\Electrum;

use OGBitBlt\Electrum\AbstractObjectManager;
use OGBitBlt\Electrum\IObjectManagerInterface;
use OGBitBlt\Electrum\Request\Wallet\GetWalletBalanceRequest;

/** 
 * Provides functions for wallet commands
 *  
 * @package OGBitBlt\Electrum
 * @author Anthony Davis <anthonyjohndavis@outlook.com>
 * @version $$ 
 * @access private  
 */
class WalletManager extends AbstractObjectManager implements IObjectManagerInterface
{
    public function __construct(\OGBitBlt\Electrum\ElectrumClient $client)
    {
        parent::__construct($client);
    }

    /**
     * GetWalletBalance - returns the balance for the wallet
     * @param bool $confirmed return only confirmed transactions as part of the balance
     */
    public function GetWalletBalance(bool $confirmed = false) : float
    {
        $result = (new GetWalletBalanceRequest($this->getClient()))->ExecuteRequest()->getResult();
		$balance = 0.0;
        if(!$confirmed) {
            if(isset($result['uncomfirmed'])) {
                $balance += floatval($result['unconfirmed']);
            }
        }
        if(isset($result['confirmed'])) {
            $balance += floatval($result['confirmed']);
        }
		return $balance;
	}
}
?>