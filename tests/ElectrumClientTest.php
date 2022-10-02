<?php declare(strict_types=1);
require 'vendor/autoload.php';
use PHPUnit\Framework\TestCase;
use OGBitBlt\Electrum\ElectrumClient;

final class ElectrumClientTest extends TestCase
{
	private $userName='elrpcusr';
	private $password='elrpcusr-d3adb33f';
	private $client = null;

	public static function setupBeforeClass() : void
	{
	}

	public static function tearDownAfterClass() : void
	{
	}

	protected function setUp() : void
	{
		$this->client = new ElectrumClient($this->userName,$this->password);
	}

	protected function tearDown() : void
	{
		$this->client = null;
	}

	public function testSetUserName() : void
	{
		$un = "foobar";
		$this->client->getConnection()->setUserName($un);
		$this->assertSame($this->client->getConnection()->getUserName(),$un);
	}

	public function testSetPassword() : void
	{
		$pw="foobar";
		$this->client->getConnection()->setPassword($pw);
		$this->assertSame($this->client->getConnection()->getPassword(),$pw);
	}

	public function testSetPort() : void
	{
		$port = 12345;
		$this->client->getConnection()->setPort($port);
		$this->assertSame($this->client->getConnection()->getPort(),$port);
	}

	public function testSetHost() : void
	{
		$host="testme.com";
		$this->client->getConnection()->setHost($host);
		$this->assertSame($this->client->getConnection()->getHost(),$host);
	}

	public function testSetUrl() : void
	{
		$un="foo";
		$pw="bar";
		$host="testme.com";
		$port=12345;
		$this->client->getConnection()
			->setUserName($un)
			->setPassword($pw)
			->setHost($host)
			->setPort($port);
		$url = "http://foo:bar@testme.com:12345";
		$this->assertSame($this->client->getConnection()->getUrl(),$url);
	}

	public function testSetBasicAuthCredentials() : void
	{
		$un="foo";
		$pw="bar";
		$this->client->getConnection()
			->setUserName($un)
			->setPassword($pw);
		$auth = "foo:bar";
		$this->assertSame($this->client->getConnection()->getBasicAuthCredentials(),$auth);
	}

	public function testCreateElectrumClient() : void
	{
		$this->assertTrue(($this->client != null), "created electrum client object");
	}

	public function testUserName() : void
	{
		$this->assertSame($this->client->getConnection()->getUserName(),"elrpcusr");
	}

	public function testPassword() : void
	{
		$this->assertSame($this->client->getConnection()->getPassword(),"elrpcusr-d3adb33f");
	}

	public function testPort() : void
	{
		$this->assertSame($this->client->getConnection()->getPort(),7777);
	}

	public function testHost() : void
	{
		$this->assertSame($this->client->getConnection()->getHost(),'localhost');
	}

	public function testUrl() : void
	{
		$url = "http://elrpcusr:elrpcusr-d3adb33f@localhost:7777";
		$this->assertSame($this->client->getConnection()->getUrl(),$url);
	}

	public function testBasicAuthCredentials() : void
	{
		$auth = "elrpcusr:elrpcusr-d3adb33f";
		$this->assertSame($this->client->getConnection()->getBasicAuthCredentials(),$auth);
	}

	public function testPushPopSingleErrorInfo() : void
	{
		$ei = new \OGBitBlt\Electrum\ErrorInfo();
		$this->client->pushErrorInfo($ei);
		$this->assertSame($this->client->getLastError(),$ei);
	}

	public function testPushPopMultipleErrorInfo() : void
	{
		$ei1 = new \OGBitBlt\Electrum\ErrorInfo("msg1",1);
		$ei2 = new \OGBitBlt\Electrum\ErrorInfo("msg2",2);
		$ei3 = new \OGBitBlt\Electrum\ErrorInfo("msg3",3);
		$ei4 = new \OGBitBlt\Electrum\ErrorInfo("msg4",4);
		$ei5 = new \OGBitBlt\Electrum\ErrorInfo("msg5",5);
		$ei6 = new \OGBitBlt\Electrum\ErrorInfo("msg6",6);
		$ei7 = new \OGBitBlt\Electrum\ErrorInfo("msg7",7);


		$this->client->pushErrorInfo($ei1);
		$this->client->pushErrorInfo($ei2);
		$this->client->pushErrorInfo($ei3);
		$this->client->pushErrorInfo($ei4);
		$this->client->pushErrorInfo($ei5);
		$this->client->pushErrorInfo($ei6);
		$this->client->pushErrorInfo($ei7);

		$this->assertSame($this->client->getLastError(),$ei7);
		$this->assertSame($this->client->getLastError(),$ei6);
		$this->assertSame($this->client->getLastError(),$ei5);
		$this->assertSame($this->client->getLastError(),$ei4);
		$this->assertSame($this->client->getLastError(),$ei3);
		$this->assertSame($this->client->getLastError(),$ei2);
		$this->assertSame($this->client->getLastError(),$ei1);
	}
}
?>

