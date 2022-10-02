<?php

namespace OGBitBlt\Electrum;

use OGBitBlt\Electrum\AbstractObjectManager;
use OGBitBlt\Electrum\IObjectManagerInterface;
use OGBitBlt\Electrum\Request\PayToRequest;

/** 
 * Provides functions related to sending payments
 *  
 * @package OGBitBlt\Electrum
 * @author Anthony Davis <anthonyjohndavis@outlook.com>
 * @version $$ 
 * @access public  
 */
class PaymentManager extends AbstractObjectManager implements IObjectManagerInterface
{
    /**
     * Pay the specified amount to the address 
     * @param   string  $address    The address to pay
     * @param   float   $amount     The amount to pay
     * @param   float   $fee        The transaction fee
     */
    public function PayTo(string $address, float $amount, float $fee)
    {
        return $this->int_pay($address, $amount, $fee);
    }

    /**
     * Pay the entire wallet balance to an address
     * @param   string  $address    The address to pay
     * @param   float   $fee        The fee amount 
     * @return  string              The transaction id hex
     */
    public function PayMax(string $address, float $fee)
    {
        return $this->int_pay($address, "!", $fee);
    }

	/**
	 * int_pay internal subsystem to the public pay functions Pay & PayMax
	 * @param   string  $dest       the destination address to pay
	 * @param   mixed   $amount     either a ! to pay max or a float amount to pay
	 * @param   float   $fee_amt    the transaction fee amount
	 * @return  string              transaction hex
	 */
	private function int_pay(
                string $address, 
		        mixed $amount="!", 
		        float $fee=0.0
                ) : string
	{
        return (new PayToRequest($address, $amount, $fee, $this->getClient()))
            ->ExecuteRequest()
            ->getResult()['hex'];

		$hex = "";
	}
}
?>