<?php
namespace OGBitBlt\Electrum;

use OGBitBlt\Electrum\AbstractObjectManager;
use OGBitBlt\Electrum\IObjectManagerInterface;
use OGBitBlt\Electrum\Request\Transaction\BroadcastTransactionRequest;
use OGBitBlt\Electrum\Request\Transaction\GetFeeRateRequest;
use OGBitBlt\Electrum\Request\Transaction\GetTxStatusRequest;
use OGBitBlt\Electrum\Response\IResponseInterface;

/** 
 * Provides functions for transaction commands
 *  
 * @package OGBitBlt\Electrum
 * @author Anthony Davis <anthonyjohndavis@outlook.com>
 * @version $$ 
 * @access public  
 */
class TransactionManager extends AbstractObjectManager implements IObjectManagerInterface
{
	/**
	 * Broadcasts a bitcoin transaction to the network
	 * @param string $transaction The transaction id to broadcast
	 */
    public function BroadcastTransaction(string $transaction) 
    {
        return (new BroadcastTransactionRequest($transaction, $this->getClient()))->ExecuteRequest()->getResult();
    }

	/**
	 * Determines if the fee amount is valid 
	 * @param float $fee_level 0.0-1.0 specifies the fee level 
	 * @return bool true if is a valid feel level and false otherwise
	 */
    public function IsFeeAmountValid(float $fee_level) : bool
	{
		$result = false;
		if(floatval($fee_level) > floatval(0.0) &&
			floatval($fee_level) <= floatval(1.0)
		 ) {
			$result = true;
		} else {
            $this->getClient()->pushErrorInfo(
                new \OGBitBlt\Electrum\ErrorInfo(
                    "Invalid fee level. Fee level must be between 0.0 and 1.0", 
                    IResponseInterface::RESPONCE_CODE_ERROR
                )
            );
			$result = false;
		}
		return $result;
	}

	/**
	 * Gets the recommended transaction fee amount 
	 * @param float $fee 0.0-1.0 the fee level
	 * @return float the recommended transaction fee
	 */
    public function GetRecommendedTransactionFee(float $level = 0.5) : float
	{
		$fee = 0.0;
		if($this->IsFeeAmountValid($level)) {
			$fee = floatval(
                (new GetFeeRateRequest($level, $this->getClient()))
                    ->ExecuteRequest()
                    ->getResult()
            )/1000;
		}
		return $fee;
	}

    /**
	 * GetTransactionConfirmations --
	 * Returns the number of confirmations for the specific transaction id
	 * @param string $transaction is a transaction that must be related to the wallet
	 * @return int the number of confirmations
	 */
	public function GetTransactionConfirmations($transaction) : int
	{
        return (new GetTxStatusRequest($transaction,$this->getClient()))
            ->ExecuteRequest()
            ->getResult();
	}

    public function __construct(\OGBitBlt\Electrum\ElectrumClient $client)
    {
        parent::__construct($client);
    }
}
?>