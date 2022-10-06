# ElectrumClient
## Easily accept bitcoin payments using your Electrum wallet
### No 3rd party libraries required, uses Electrum v4xx 
This is a fairly simple class library, developed so that all functionality can be accessed from a single object. Completely functional as a standalone library, the original design and architecture of the library is that this will be a specific component of a crypto payment processing platform, still under development as of the time of this writing.

#### Setting up Electrum 
Electrum must be configured so that it will run as a daemon and accept JSONRPC calls. 
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

##### Example using the client object (recommended method):
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
##### Example by creating the object manager (alternative method):
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
#### Address Functions
- ##### GetNewPaymentAddress() : string 
    - Creates a new bitcoin payment address
```
// ...create client and initialize
$address = $client->getManager(AddressManager::class)->GetNewPaymentAddress();
```
- ##### GetAddressBalance(string $address) : float
    - Get the balance of the bitcoin address
```
// ... create client and initialize
// ... get address
// pass true as the second argument if you only want confirmed transactions
// pass false if you want confirmed and unconfirmed transactions included in the balance
$balance = $client->getManager(AddressManager::class)->GetAddressBalance($address, false);
```
- ##### IsValidAddress(string $address) : bool
    - Determines if the address is valid or not
- ##### IsMyAddress(string $addres) : bool
    - Determines if the address is associated with your wallet or not
- ##### GetAddressHistory(string $address) : array
    - Returns a history of transactions for the adddress

#### Wallet Functions
##### GetWalletBalance(bool $confirmed = false) : float

#### Transaction Functions
##### IsFeeAmountValid(float $fee_level) : bool
##### GetRecommendedTransactionFee(float $level = 0.5) : float
##### GetTransactionConfirmations(string $transaction) : int

#### Payment Functions
##### PayTo(string $address, float $amount, float $fee) : string
##### PayMax(string $address, float $fee) : string

#### Version Functions
##### GetElectrumVersion() : string
##### GetVersionInfo(string $versionInfo) : int