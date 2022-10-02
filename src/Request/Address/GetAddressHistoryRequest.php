<?php
namespace OGBitBlt\Electrum\Request\Address;

use OGBitBlt\Electrum\Request\AbstractRequest;
use OGBitBlt\Electrum\Request\IRequestInterface;

/** 
 * Implements the function that calls the getaddresshistory Electrum command
 * @package OGBitBlt\Electrum
 * @author Anthony Davis <anthonyjohndavis@outlook.com>
 * @version $$ 
 * @access private  
 */
class GetAddressHistoryRequest extends AbstractRequest implements IRequestInterface
{
    public function __construct(string $address, \OGBitBlt\Electrum\ElectrumClient $client)
    {
        parent::__construct($client, "getaddresshistory",['address' => $address]);
    }
}
?>