<?php
namespace OGBitBlt\Electrum\Request\Payment;

use OGBitBlt\Electrum\Request\AbstractRequest;
use OGBitBlt\Electrum\Request\IRequestInterface;

/** 
 * Implements the function that calls the electrum payto command
 * @package OGBitBlt\Electrum
 * @author Anthony Davis <anthonyjohndavis@outlook.com>
 * @version $$ 
 * @access private  
 */

class PayToRequest extends AbstractRequest implements IRequestInterface
{
    public function __construct(string $destination, float $amount, float $fee, \OGBitBlt\Electrum\ElectrumClient $client)
    {
        $params = [
            'destination' => $destination,
            'amount' => $amount, 
            'fee' => $fee
        ];
        parent::__construct($client,"payto",$params);
    }
}
?>