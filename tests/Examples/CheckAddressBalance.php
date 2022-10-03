<?php
/**
 * This example demonstrates checking a payment address balance for a payment,
 * it will wait until there are 3 network confirmations before confirming the balance
 */
require dirname(__DIR__).'/vendor/autoload.php';

/* --- EDIT THE NEXT 3 LINES WITH YOUR INFO ---  */
$user = '';     // must be set on the electrum wallet using setconfig rpcuser
$pw = '';       // must be set on the electrum wallet using setconfig rpcpassword
$port = 7777;   // must be set on the electrum wallet using setconfig rpcport
/* --- END EDIT SECTION --- */

$client = new ElectrumClient($user, $pw, $port, false);

// we always have to call init before any commands to ensure that 
// our electrum wallet is running and is configured correctly
try {
    $client->Init();
} catch(ElectrumClientConfigurationException $e) {
    printf("Electrum Configuration Exception: %s\n",$e);
    return;
}

$receive_address = ''; 
if(!isset($argv[1])) {
    printf("no receive address entered");
    return;
}

$receive_address = $argv[1];

// get the address manager from the client
$addressManager = $client->getManager(\OGBbitBlt\Electrum\AddressManager::class);

// check if it is a valid receive address
if(!$addressManager->IsValidAddress($receive_address)){
    printf("%s is not a valid bitcoin address.\n",$receive_address);
    return;
}

// check that this address belongs to us
if(!$addressManager->IsMyAddress($receive_address)){
    printf("%s does not belong to our wallet.\n", $receive_address);
}

printf("Send payment to : %s\n", $receive_address);

// get the curent balance of the address which we will use to 
// determine once a new payment has arrived
$balance = $addressManager->GetAddressBalance($receive_address);
$new_balance = $balance;
$height = 0;

printf("Current Address Balance: %f\n", $balance);

// while the current balance is equal to the new balance do a loop
while($balance == $new_balance) {
    // display block height, the block height should be 
    // stored in a DB to keep track of what transactions 
    // have already been processed
    printf("Checking address block height: %d\n",$height);

    // check for transactions on the address
    $history = $addressManager->GetAddressHistory($receive_address);

    // check the status of the transactions to see if they have 3 confirmations
    foreach($history as $transaction) {
        if($transaction['height']>$height) {
            printf("\tTransaction: %s\n",$transaction['tx_hash']);
            $confirmations = $client->getManager(OGBbitBlt\Electrum\TransactionManager::class)->GetTransactionConfirmations($transaction['tx_hash']);
            if($confirmations<3) {
                printf("\t\t%d confirmations, waiting for 3 to confirm transaction...\n",$confirmations);
            } else {
                // this transation has 3 confirmations so we will update the balance
                printf("\t\tTransaction confirmed\n");
                $new_balance = $addressManager->GetAddressBalance($receive_address);
                // and we will update our block height to keep up from processing
                // this transaction again in the future
                $height = $transaction['height'];
                printf("New Address Block Height: %d\n", $height);
            }
        }
    }
    // the new address balance will contain the 
    // old balance plus any new payments so we will 
    // subtract the original balance from the new balance 
    // to figure out the payment amount 
    if($balance != $new_balance) {
        $payment = $new_balance - $balance;
        printf("Received a payment of: %f\n",$payment);
    } else {
        // if the new balance has not changed, lets pause a few seconds
        // and continue the loop to check again
        sleep(10); // wait 10 seconds and check again
    }
}

?>