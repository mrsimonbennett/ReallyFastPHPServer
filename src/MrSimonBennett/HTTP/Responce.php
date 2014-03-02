<?php

namespace MrSimonBennett\HTTP;

class Responce
{
	private $_HTTPCode 		= 200;
	private $_HTTPMessage 	= 'OK';
	private $_ContentType 	= "Content-Type: text/html";
	private $_HTTPVerson 	= 'HTTP/1.1';

	public function __construct()
	{

	}
	public function HTTPCode($code)
	{
		$this->_HTTPCode = $code;
	}

	public function Content($content)
	{
		$this->_content = $content;
		return $this;
	}
	public function Go($client)
	{
		$response = array(	
			"head" => array(
				"HTTP/1.0 200 OK",
				"Content-Type: text/html"
			), 
			"body" => array()
		);

		//socket_getpeername($client, $address, $port);

		
		$response["body"] = $this->_content . "\r\n";
		$response["head"][] = sprintf("Content-Length: %d", strlen($response["body"]));
		$response["head"] = implode("\r\n", $response["head"]);

		//var_dump($response);

		socket_write($client, $response["head"]);
		socket_write($client, "\r\n\r\n");
		socket_write($client, $response["body"]."\n");
	}
}