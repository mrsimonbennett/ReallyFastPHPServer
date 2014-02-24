<?php 
/**
 * Socket
 */
namespace MrSimonBennett\Server;

class Socket
{
	/**
	 * [$_port description]
	 * @var [type]
	 */
	private $_port;

	/**
	 * [$_interface description]
	 * @var int default 0
	 */
	private $_interface = 0;

	/**
	 * [$_clients description]
	 * @var array
	 */
	private $_clients = array(); 

	public $socket;


	public function __construct()
	{
	}
	/**
	 * Set the Port for the socket to lision on
	 * @param int $port 
	 * @return  $this 
	 */
	public function Port($port)
	{
		$this->_port = $port;
		return $this;
	}

	/**
	 * Select which interface you wish to bind the socket to
	 * @param string $interface IP Address of the interface
	 */
	public function Address($interface)
	{
		$this->_interface = $interface;
		return $this;
	}

	public function Clients()
	{
		return $this->_clients;
	}

	public function Create()
	{
		$this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
		
		socket_set_option($this->socket, SOL_SOCKET, SO_REUSEADDR, 1);
        
        socket_bind($this->socket, $this->_interface, $this->_port);
        socket_listen($this->socket);
	}
	public function Distroy()
	{
		socket_close($this->socket);
		$this->socket = null; //Unit test shows that without this the socket is still a resouce of unknow type.
	}

	
}