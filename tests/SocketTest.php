<?php
//require 'vendor/autoload.php';
class SocketTest extends PHPUnit_Framework_TestCase
{
	public function testSocketinstantiate()
	{
		$socket = new \MrSimonBennett\Server\Socket();

		$this->assertInstanceOf('\MrSimonBennett\Server\Socket',$socket);
	}
	public function testSocketAddPort()
	{
		$socket = new \MrSimonBennett\Server\Socket();

		$this->assertInstanceOf('\MrSimonBennett\Server\Socket',$socket->Port(2000));
	}
	public function testSocketAddress()
	{
		$socket = new \MrSimonBennett\Server\Socket();

		$this->assertInstanceOf('\MrSimonBennett\Server\Socket',$socket->Address(2000));
	}
	/**
	 * Unit Test the Creation of a socket.
	 * Currently just checks the resource type is a socket
	 */
	public function testSocketCreate()
	{
		$socket = new \MrSimonBennett\Server\Socket();
		$socket->Address(0)->Port(6000)->Create();

		$this->assertEquals('Socket',get_resource_type($socket->socket));
	}	
	/**
	 * Test is the socket is distroyed
	 */
	public function testDistroySocket()
	{
		$socket = new \MrSimonBennett\Server\Socket();
		$socket->Address(0)->Port(6000)->Create();
		$socket->Distroy();
		$this->assertEquals($socket->socket,null);
	}
}