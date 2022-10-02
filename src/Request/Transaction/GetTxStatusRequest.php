<?php
namespace OGBitBlt\Electrum\Request\Transaction;

use OGBitBlt\Electrum\Request\AbstractRequest;
use OGBitBlt\Electrum\Request\IRequestInterface;

/** 
 * Implements the function that calls the Electrum get_tx_status command
 * @package OGBitBlt\Electrum
 * @author Anthony Davis <anthonyjohndavis@outlook.com>
 * @version $$ 
 * @access private  
 */
class GetTxStatusRequest extends AbstractRequest implements IRequestInterface
{
    public function __construct(string $transaction, \OGBitBlt\Electrum\ElectrumClient $client)
    {
        parent::__construct($client,"get_tx_status",["txid" => $transaction]);
    }
}
?>