<?php
namespace OGBitBlt\Electrum;

use OGBitBlt\Electrum\AbstractObjectManager;
use OGBitBlt\Electrum\IObjectManagerInterface;
use OGBitBlt\Electrum\Request\Address\GetAddressBalanceRequest;
use OGBitBlt\Electrum\Request\Address\GetAddressHistoryRequest;
use OGBitBlt\Electrum\Request\Address\GetNewPaymentAddressRequest;
use OGBitBlt\Electrum\Request\Address\IsAddressMineRequest;
use OGBitBlt\Electrum\Request\Address\ValidateAddressRequest;

/** 
 * Provides functions related to the Address object 
 *  
 * @package OGBitBlt\Electrum
 * @author Anthony Davis <anthonyjohndavis@outlook.com>
 * @version $$ 
 * @access public  
 */
class AddressManager extends AbstractObjectManager implements IObjectManagerInterface
{
	/**
	 * returns a new payment address associated with the electrum bitcoin wallet
	 * @return string The new receive address. 
	 */
    public function GetNewPaymentAddress()
    {
        return (new GetNewPaymentAddressRequest($this->getClient()))->ExecuteRequest()->getResult();
    }

	/**
	 * returns the balance for the specified bitcoin address 
	 * @param string $address The bitcoin address to get the balance of 
	 * @param bool $confirmed limit the balance to confirmed transactions or not
	 * @return float the balance of the specified address 
	 */
    public function GetAddressBalance(string $address, bool $confirmed = false)
    {
        return (new GetAddressBalanceRequest($address, $this->getClient()))->ExecuteRequest()->GetBalance($confirmed);
    }

    /**
	 * IsValidAddress --
	 * Checks that address is a valid bitcoin address
	 * @param string $address the address to validate
	 * @return true if address is valid and false otherwise
	 */
	public function IsValidAddress(string $address) : bool
	{
        return (new ValidateAddressRequest($address,$this->getClient()))->ExecuteRequest()->getResult();
	}

    /** 
	 * IsMyAddress verifies that the address belongs to our wallet
	 * @param string $address the address to check
	 * @return true if it is ours and false otherwise
	 */
	public function IsMyAddress(string $address) : bool 
	{
		$r = (new IsAddressMineRequest($address, $this->getClient()))
			->ExecuteRequest()
			->getResult();
		if($r==null) return false;
		return true;
	}

    public function __construct(\OGBitBlt\Electrum\ElectrumClient $client)
    {
        parent::__construct($client);
    }

    /**
	 * GetAddressHistory --
	 * @param string $address the address you want the history of
	 * @returns an array of associated arrays with transaction ids and block height
	 * array(3) {
  	 *	[0]=>
  	 *	array(2) {
     *		["height"]=> 
	 *		int(2348296)
     *		["tx_hash"]=>
     *		string(64) "ae0722e99cc8c7759c0eff973dabdd247c94edf111259f11babe02aae629f3a8"
  	 *	}
	 *	[1]=>
  	 *	array(2) {
     *		["height"]=>
     *		int(2348296)
     *		["tx_hash"]=>
     *		string(64) "1583791b040ba101e7fbb71c8dd07ecc0b6166e2217d2f5c1f426fb0d40e7077"
  	 *	}
  	 *	[2]=>
  	 *	array(2) {
     *		["height"]=>
     *		int(2348296)
     *		["tx_hash"]=>
     *		string(64) "787a0e4e5bcab07524da9ac60802c163b86fcd297f9649ced6fafbb2005c5adf"
  	 *	}
	 *}
	 */
	public function GetAddressHistory(string $address) : ?array
	{
        return (new GetAddressHistoryRequest($address, $this->getClient()))
            ->ExecuteRequest()
            ->getResult();
	}
}
?>