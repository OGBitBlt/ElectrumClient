<?php
namespace OGBitBlt\Electrum\Request\Transaction;

use OGBitBlt\Electrum\Request\AbstractRequest;
use OGBitBlt\Electrum\Request\IRequestInterface;

/** 
 * Implements the function that calls the Electrum getfeerate command
 * @package OGBitBlt\Electrum
 * @author Anthony Davis <anthonyjohndavis@outlook.com>
 * @version $$ 
 * @access private  
 */
class GetFeeRateRequest extends AbstractRequest implements IRequestInterface
{
    public function __construct(float $level = 0.5, \OGBitBlt\Electrum\ElectrumClient $client)
    {
        parent::__construct($client,"getfeerate",["fee_level" => $level]);
    }
}
?>