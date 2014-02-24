<?php
//require 'vendor/autoload.php';
class LushHTTPPostGetServerTest extends \PHPUnit_Framework_TestCase
{
	public function testServerGetSet()
	{
				//echo 'test';
		$server = new \MrSimonBennett\HTTP\Server();
		$var = 'test';

		$server->test = $var;
		$this->assertEquals($server->test,$var);
	}
	public function testGetGetSet()
	{
		$get = new \MrSimonBennett\HTTP\Get();
		$var = 'test';
		$get->test = $var;
		$this->assertEquals($get->test,$var);
	}
	public function testPostGetSet()
	{
		$post = new \MrSimonBennett\HTTP\Post();
		$var = 'test';
		$post->test = $var;
		$this->assertEquals($post->test,$var);
	}
}