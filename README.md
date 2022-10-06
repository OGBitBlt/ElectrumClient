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
```
// ...create client and initialize
$address = $client->getManager(AddressManager::class)->GetNewPaymentAddress();
```
- ##### AdressManager->GetAddressBalance(string $address, bool $confirmed) : float
    - Get the balance of the bitcoin address
```
// ... create client and initialize
// ... get address
// pass true as the second argument if you only want confirmed transactions
// pass false if you want confirmed and unconfirmed transactions included in the balance
$balance = $client->getManager(AddressManager::class)->GetAddressBalance($address, false);
```
- ##### AddressManager->IsValidAddress(string $address) : bool
    - Determines if the address is valid or not
- ##### AddressManager->IsMyAddress(string $addres) : bool
    - Determines if the address is associated with your wallet or not
- ##### AddressManager->GetAddressHistory(string $address) : array
    - Returns a history of transactions for the adddress in an associative array that looks like:
```
array(3) {
[0]=>
  	array(2) {
        ["height"] => int(2348296)
        ["tx_hash"] => string(64) "ae0722e99cc8c7759c0eff973dabdd247c94edf111259f11babe02aae629f3a8"
  	}
[1]=>
  	array(2) {
        ["height"] => int(2348296)
     	["tx_hash"] => string(64) "1583791b040ba101e7fbb71c8dd07ecc0b6166e2217d2f5c1f426fb0d40e7077"
  	}
[2]=>
  	array(2) {
    	["height"] => int(2348296)
    	["tx_hash"] => string(64) "787a0e4e5bcab07524da9ac60802c163b86fcd297f9649ced6fafbb2005c5adf"
  	}
}
```

### Wallet Functions
Wallet functions are available via the WalletManager object:
```
$walletManager = $client->getManager(WalletManager::class);
```
- ##### WalletManger->GetWalletBalance(bool $confirmed = false) : float

### Transaction Functions
Transaction functions are available via the TransactionManager object:
```
$transactionManager = $client->getManager(TransactionManager::class);
```
- ##### IsFeeAmountValid(float $fee_level) : bool
- ##### GetRecommendedTransactionFee(float $level = 0.5) : float
- ##### GetTransactionConfirmations(string $transaction) : int
### Payment Functions
- ##### PayTo(string $address, float $amount, float $fee) : string
- ##### PayMax(string $address, float $fee) : string
### Version Functions
- ##### GetElectrumVersion() : string
- ##### GetVersionInfo(string $versionInfo) : int