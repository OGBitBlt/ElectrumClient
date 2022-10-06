# ElectrumClient
## Easily accept bitcoin payments using your Electrum wallet
##### No 3rd party libraries required, uses Electrum v4xx 
This is a simple class library, developed so that all functionality can be accessed from a single object. Completely functional as a standalone library, the original design and architecture of the library is that this will be a specific component of a crypto payment processing platform, still under development as of the time of this writing.

#### Setting up Electrum 
Prior to using this library, Electrum must be configured so that it will run as a daemon and accept JSONRPC calls. Electrum will only accept RPC calls on localhost, but this library will allow you to host electrum on another host. When you do this you must setup your firewall on the Electrum host server to forward the requests from this library to localhost and to the port configured for RPC calls within Electrum.
Below are the steps for setting up Electrum:
- Download and install Electum from [https://electrum.org/#download](https://electrum.org/#download)
- Electrum has to be running as a daemon on the system you install it on, the following commands all work on a linux command line. 
- To start electrum as a daemon enter the command
```electrum daemon -d``` 
- You must specify a username 
```electrum setconfig rpcuser 'user name'```
- and a password
```electrum setconfig rpcpassword 'password'```
- Specify a port for electrum to listen on
```electrum setconfig rpcport 7777```
- Restart the electrum daemon at this point so that the new settings will be in effect.
- Then tell electrum to load the wallet 
```electrum load_wallet```
- Note: If you want to test your configuration, add the --testnet flag at the end of each of the commands above.
- At this point your electrum wallet is ready to receive RPC commands.
Here is a linux script to automate the process, note you have to replace the values specified in between < >

```
#!/bin/sh
electrum daemon -d --testnet
electrum setconfig rpcuser <username> --testnet
electrum setconfig rpcpassword <password> --testnet
electrum setconfig rpcport 7777 --testnet
electrum stop --testnet
electrum daemon -d --testnet
electrum load_wallet --testnet
```
#### Using the client vs object manager interfaces
There are 2 ways to make calls to the electrum interface. It is always recommended that you use the 
client object for all of your calls, but in some instances you may want to create your own object managers. Below are examples of both using the Address manager to get a new receive address.

#### Example using the client object (recommended method):
```
$client = new ElectrumClient('user','pass','localhost',7777,false);
try {
    $client->Init();
} catch(ElectrumClientConfigurationException $e) {
    echo 'Electrum is not running';
    return;
}
// get a new payment address using the client object manager
$address = $client->getManager(AddressManager::class)->GetNewPaymentAddress();
````
#### Example by creating the object manager (alternative method):
```
$client = new ElectrumClient('user','pass','localhost',7777,false);
try {
    $client->Init();
} catch(ElectrumClientConfigurationException $e) {
    echo 'Electrum is not running';
    return;
}
// create an address manager 
$addressManager = new AddressManager($client);
$address = $addressManager->GetNewPaymentAddress();
```

The rest of this document describes the APIs available within the library, I tried to make the documentation as clean as possible but if you find a mistake please email me and let me know <ogbitblt at pm.me>. 
### Address Functions 
Address functions are available via the AddressManager object :
```
$addressManager = $client->getManager(AddressManager::class);
```
- ##### AddressManager->GetNewPaymentAddress() : string 
    - Creates a new bitcoin payment address
    - return string containing the new receive address
```
// ...create client and initialize
$address = $client->getManager(AddressManager::class)->GetNewPaymentAddress();
```
- ##### AdressManager->GetAddressBalance(string $address, bool $confirmed) : float
    - Get the balance of the bitcoin address
    - param: $address string value of the bitcoin address
    - param: $confirmed bool value, true if you only want to see confirmed transactions in the balance and false otherwise
    - return: a float value of the balance for the address
```
// ... create client and initialize
// ... get address
// pass true as the second argument if you only want confirmed transactions
// pass false if you want confirmed and unconfirmed transactions included in the balance
$balance = $client->getManager(AddressManager::class)->GetAddressBalance($address, false);
```
- ##### AddressManager->IsValidAddress(string $address) : bool
    - Determines if the address is valid or not
    - param: $address a string containing the address to check
    - return: true if is a valid address, false otherwise
- ##### AddressManager->IsMyAddress(string $addres) : bool
    - Determines if the address is associated with your wallet or not
    - param: $address a string holding the bitcoin address to check
    - return: true if the address belongs to your wallet, false otherwise.
- ##### AddressManager->GetAddressHistory(string $address) : array
    - Get the transaction history for an address
    - param: $address a string holding the address you want to get the history for
    - Returns a history of transactions for the adddress in an associative array that looks like:
```
array(3) {
    [0]=>
        array(2) {
            ["height"] => 
            int(2348296)
            ["tx_hash"] => 
            string(64) "ae0722e99cc8c7759c0eff973dabdd247c94edf111259f11babe02aae629f3a8"
        }
    [1]=>
        array(2) {
            ["height"] => 
            int(2348296)
            ["tx_hash"] => 
            string(64) "1583791b040ba101e7fbb71c8dd07ecc0b6166e2217d2f5c1f426fb0d40e7077"
        }
    [2]=>
        array(2) {
            ["height"] => 
            int(2348296)
            ["tx_hash"] => 
            string(64) "787a0e4e5bcab07524da9ac60802c163b86fcd297f9649ced6fafbb2005c5adf"
        }
}
```

### Wallet Functions
Wallet functions are available via the WalletManager object:
```
$walletManager = $client->getManager(WalletManager::class);
```
- ##### WalletManger->GetWalletBalance(bool $confirmed = false) : float
    - param: $confirmed (optional) defaults to false; set to true if you only want to include confirmed transactions in the wallet balance.
    - return: float balance of the wallet.

### Transaction Functions
Transaction functions are available via the TransactionManager object:
```
$transactionManager = $client->getManager(TransactionManager::class);
```
- ##### TransactionManager->IsFeeAmountValid(float $fee_level) : bool
- ##### TransactionManager->GetRecommendedTransactionFee(float $level = 0.5) : float
- ##### TransactionManager->GetTransactionConfirmations(string $transaction) : int
### Payment Functions
Payment functions are available via the PaymentManager object:
```
$paymentManager = $client->getManager(PaymentManager::class);
```
- ##### PaymentManager->PayTo(string $address, float $amount, float $fee) : string
    - Pays a specified amount to the specified address
    - param: $address string value holding the bitcoin address to pay
    - param: $amount float amount to be paid
    - param: $fee float amount of the transaction fee to pay
    - return: a string containing the transaction id
- ##### PaymentManager->PayMax(string $address, float $fee) : string
    - Pays the total wallet balance amount to the specified address
    - param: $address string value holding the bitcoin address to pay
    - param: $fee float amount of the transaction fee to pay
    - return: a string containing the transaction id
### Version Functions
Version functions are available via the VersionManager object:
```
$versionManager = $client->getManager(VersionManager::class);
```
- ##### VersionManager->GetElectrumVersion() : string
    - Provides version info for the Electrum wallet
    - return: a string containing the version info formatted as nn.nn.nn (major.minor.revision)
- ##### VersionManager->GetVersionInfo(string $versionInfo) : int
    - Returns specific version info (major, minor, revision)
    - param: VersionManager has 3 constants that can be used to retrieve version info
        - VersionManager::MAJOR_VERSION
        - VersionManager::MINOR_VERSION
        - VersionManager::REVISION
    - return: an integer value of the version info requested
```
$major = $versionManager->GetVersionInfo(VersionManager::MAJOR_VERSION);
$minor = $versionManager->GetVersionInfo(VersionManager::MINOR_VERSION);
$revision = $versionManager->GetVersionInfo(VersionManager::REVISION);
```