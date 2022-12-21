<?php
require dirname(__DIR__).'/vendor/autoload.php';

$output = null;
$retval = null;
// ensure that electrum is running as a daemon
exec('electrum daemon -d --testnet',$output,$retval);
fwrite(STDOUT, "BOOTSTRAP: setting up electrum\n\t" . implode("=",$output) ."\n");

exec('electrum setconfig rpcuser elrpcusr --testnet', $output, $retval);
fwrite(STDOUT, "BOOTSTRAP: set rpc user\n\t" . implode("=",$output) ."\n");

exec('electrum setconfig rpcpassword elrpcusr-d3adb33f --testnet', $output, $retval);
fwrite(STDOUT, "BOOTSTRAP: set rcp password\n\t" . implode("=",$output) ."\n");

exec('electrum setconfig rpcport 7777 --testnet', $output, $retval);
fwrite(STDOUT, "BOOTSTRAP: set port\n\t" . implode("=",$output) ."\n");

exec('electrum stop --testnet',$output, $retval);
fwrite(STDOUT, "BOOTSTRAP: shutting down electrum\n\t" . implode("=",$output) ."\n");

exec('electrum daemon -d --testnet; electrum load_wallet --testnet',$output, $retval);
fwrite(STDOUT, "BOOTSTRAP: restarted electrum and loaded wallet\n\t" . implode("=",$output)."\n");

?>
