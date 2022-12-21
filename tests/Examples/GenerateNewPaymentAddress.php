<?php
/**
 * This example demonstrates generating a new payment address to recieve bitcoin payments at.
 */
require dirname(__DIR__).'/vendor/autoload.php';

/* --- EDIT THE NEXT 3 LINES WITH YOUR INFO ---  */
$user = 'elrpcusr';     // must be set on the electrum wallet using setconfig rpcuser
$pw = 'elrpcusr-d3adb33f';       // must be set on the electrum wallet using setconfig rpcpassword
$port = 7777;   // must be set on the electrum wallet using setconfig rpcport
/* --- END EDIT SECTION --- */

$client = new ElectrumClient($user, $pw, $port, false);

try {
    $client->Init();
} catch(ElectrumClientConfigurationException $e) {
    printf("Electrum Configuration Exception: %s\n",$e);
    return;
}

$payment_address = $client->getManager(\OGBbitBlt\Electrum\AddressManager::class)->GetNewPaymentAddress();
printf("Please send payment to: %s\n", $payment_address);

?>