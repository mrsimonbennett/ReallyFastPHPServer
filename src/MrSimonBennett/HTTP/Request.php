<?php

use MrSimonBennett\HTTP\Get as Get;
use MrSimonBennett\HTTP\Post as Post;
use MrSimonBennett\HTTP\Server as Server;
use MrSimonBennett\HTTP\Responce as Responce;
namespace MrSimonBennett\HTTP;

class Request
{
	private $_rawheader;
	private $_headers;
	private $_headinfo;
	private $_responce;

	/**
	 * @var Bennett\HTTP\Get
	 **/
	private $_get;
	/**
	 * @var Bennett\HTTP\Post
	 */
	private $_post;
	/**
	 * @var Bennett\HTTP\Server
	 */
	private $_server;

	public function __construct(Responce $responce, Get $get, Post $post, Server $server)
	{
		$this->_responce = $responce;
		$this->_get = $get;
		$this->_post = $post;
		$this->_server = $server;
	}

	public function Read(&$client)
	{
		$timeout = 100;

		$start = microtime(true);
		$header = 0;
		//$chars = socket_read($client, 1024, PHP_BINARY_READ);
		
		//$lines = explode("\n",$chars);
		//print_r($lines);
		//var_dump($lines);
		$headerread = false;
		while(($chars = socket_read($client, 1024, PHP_BINARY_READ))) {


			if ((microtime(true) - $start) > $timeout)
				break;

			//echo bin2hex($chars);
			/*echo $chars;
			echo "\r\n";
			echo bin2hex("\r\n");
			echo "\r\n";
			echo "\r\n";
			echo substr(bin2hex($chars), -4);
			*/
			if($headerread == false)
			{
				$lines = explode("\n",trim($chars));
				//var_dump($lines[0]);
				
				//echo 'blah';
				list($this->_server->method,$this->_server->uri,$this->_server->httpverson) = explode(' ' , $lines[0],3);
				socket_getpeername($client,$ip,$port);
				$this->_server->clientip = $ip;
				$this->_server->clientport =$port;
				unset($lines[0]);
				foreach($lines as $line)
				{
					list($key,$value) = explode(':',$line);
					$this->_server->{strtolower($key)} = trim($value);
				}
				//debug Ingo
				//echo  "\r\n" . 'Lush Server Debug' . "\r\n";
				//echo 'Request:' . $this->_server->uri . "\r\n";
				//echo 'Method:' . $this->_server->method . "\r\n";

			}
			if(substr(bin2hex($chars), -4) == bin2hex("\r\n"))
			{
				break;
			}

			
		}
		return $this;
	}
	/** 
	 * This is where the magic happens the code is now going to interact with normal php code
	 */
	public function Process($function,$args = null)
	{
		
		//becuase the process with buffer and we want the buffer in the broswer not here we will catch it.
		ob_start( );
		//echo 'It Fuckings works';
		$function($this->_get,$this->_post,$this->_server,$args);
		$output = ob_get_clean();
		
			
		$this->_responce->Content($output);

	}
}