<?php
namespace OGBitBlt\Electrum\Request\Transaction;

use OGBitBlt\Electrum\Request\AbstractRequest;
use OGBitBlt\Electrum\Request\IRequestInterface;

/** 
 * Implements the function that calls the Electrum broadcast command 
 * @package OGBitBlt\Electrum
 * @author Anthony Davis <anthonyjohndavis@outlook.com>
 * @version $$ 
 * @access private  
 */
class BroadcastTransactionRequest extends AbstractRequest implements IRequestInterface
{
    public function __construct(string $transaction, \OGBitBlt\Electrum\ElectrumClient $client)
    {
        $params = ["tx" => $transaction];
        parent::__construct($client,"broadcast",$params);
    }
}
?>