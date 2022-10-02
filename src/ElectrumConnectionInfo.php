<?php
namespace OGBitBlt\Electrum;

/**
 * Creates an object to store the parameters for connecting 
 * to an Electrum wallet and completing RPC calls.
 * @package OGBitBlt\Electrum
 * @author Anthony Davis <anthonyjohndavis@outlook.com>
 * @version $ $ 
 * @access public  
 */
class ElectrumConnectionInfo 
{
    private $_host;
    private $_username;
    private $_password;
    private $_port;
    private $_useSSL;

    /**
     * Creates the ElectrumConnectionInfo object
     * @param   string  $username   The RPC User value
     * @param   string  $password   The PRC Password value
     * @param   string  $host       The Electrum wallet host defaults to 
     *                              localhost.
     * @param   int     $port       The RPC port that electrum is configured
     *                              to listen on
     * @param   bool    $useSSL     flag to use SSL or not
     */
    public function __construct(
        string $username, 
        string $password, 
        string $host = 'localhost', 
        int $port = 7777, 
        bool $useSSL = false
        )
    {
        $this->_username = $username;
        $this->_password = $password;
        $this->_port = $port;
        $this->_host = $host;
        $this->_useSSL = $useSSL;
    }

    /**
     * @return a formatted url to be used in curl request
     */
    public function getUrl() : string 
    {
        return sprintf("%s://%s:%s@%s:%d",
            ($this->_useSSL) ? "https" : "http",
            $this->_username,
            $this->_password,
            $this->_host, 
            $this->_port
        ); 
    }

    /**
     * @return a string formatted to be used as basic HTTPAUTH 
     */
    public function getBasicAuthCredentials() : string 
    {
        return sprintf("%s:%s",$this->_username,$this->_password);
    }

    /**
     * @return getter for the host member
     */
    public function getHost() : string
    {
        return $this->_host;
    }

    /**
     * @param   string  $host   host value
     * @return  ElectrumConnectionInfo
     */
    public function setHost(string $host) : self
    {
        $this->_host = $host;
        return $this;
    }

    /**
     * @return getter for the username member
     */
    public function getUserName() : string 
    {
        return $this->_username;
    }

    /**
     * @param   string  $userName   username value
     * @return ElectrumConnectionInfo
     */
    public function setUserName(string $userName) : self
    {
        $this->_username = $userName;
        return $this;
    }

    /**
     * @return ElectrumConnectionInfo
     */
    public function getPassword() : string
    {
        return $this->_password;
    }

    public function setPassword(string $password) : self
    {
        $this->_password = $password;
        return $this;
    }

    public function getPort() : int
    {
        return $this->_port;
    }

    public function setPort(int $port) : self
    {
        $this->_port = $port;
        return $this;
    }

    public function isUseSSL() : bool
    {
        return $this->_useSSL;
    }

    public function setUseSSL(bool $useSSL) : self
    {
        $this->_useSSL = $useSSL;
        return $this;
    }
}
?>