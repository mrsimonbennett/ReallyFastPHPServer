<?php

namspace MrSimonBennett\Server
{
	class Client
	{
		protected $_socket;
		protected $_client;
		public function Construct(Socket $socket)
		{
			$this->_socket = $socket;
		}
		/**
		 * Accept a Client from the socket.
		 * This is IO Blocking till a client is accepted
		 */
		
		public function Accept()
		{
			$this->_client = socket_accept($this->_socket);
		}
		public functin 
	}

}