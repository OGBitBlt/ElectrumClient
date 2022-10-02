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
- You must specify a username and password for the RPC calls, to do this on the command line enter
```electrum setconfig rpcuser 'user name'```
```electrum setconfig rpcpassword 'password'```
- You must specify a port number for electrum to listen on, to do this on the command line enter
```electrum setconfig rpcport 7777```
- Restart the electrum daemon at this point so that the new settings will be in effect.
- Then tell electrum to load the wallet by entering the command
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
electrum daemon -d -testnet
```


