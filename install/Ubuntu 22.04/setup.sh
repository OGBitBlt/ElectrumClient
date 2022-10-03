#!/bin/sh --

# sets environment variables we need
source env.sh

# setup electrum
electrum daemon -d 
electrum setconfig rpcuser $ELECTRUM_RPC_USER
electrum setconfig rpcpassword $ELECTRUM_RPC_PASSWORD
electrum setconfig rpcport $ELECTRUM_RPC_PORT
electrum stop 

# create the service file
cat << EOF > electrum.service 
[Unit]
After=network.service
[Service]
ExecStart=/usr/bin/electrum daemon -d;/usr/bin/electrum load_wallet;
[Install]
WantedBy=default.target
EOF

# install the service file  wih the correct permissions
cp electrum.service /etc/systemd/system
chmod 664 /etc/systemd/system/electrum.service 
systemctl daemon-reload
systemctl enable electrum.service

# clean up
rm electrum.service
