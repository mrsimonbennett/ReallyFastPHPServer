<?php
/**
 * The Simplest Thread Socket Server
 * Each Time a new client connects it creates a new Thread to handel the request 
 */

    $loader = require __DIR__ . '/../vendor/autoload.php';



	$error = new MrSimonBennett\Server\Error();
	register_shutdown_function(array($error,'shut'));
	set_error_handler(array($error,'handler'));


	$socket = new MrSimonBennett\Server\Socket();
	$stats = new MrSimonBennett\Server\Stats();
	$socket->port(8000);
	$socket->address(0);
	$socket->Create();
	$runcount = 0;


	//Dumbys to make sure the autoloader works before starting a loop. Not perfect i know. Well It might be as including files while running is a bad idea I/O errors
	//Maybe need a dummy run first.
  
	
	$responce = new MrSimonBennett\HTTP\Responce();
	$request = 	new MrSimonBennett\HTTP\Request($responce,new MrSimonBennett\HTTP\Get(),new MrSimonBennett\HTTP\Post(),new MrSimonBennett\HTTP\Server());
	$app = new MrSimonBennett\RestFrameWork\Bootstrap\Application();
	unset($responce,$request,$restserver,$routes,$app);
	$app = new MrSimonBennett\RestFrameWork\Bootstrap\Application();		
	new MrSimonBennett\RestFrameWork\Controller\ControllerNotFoundException();
	$app->httpFromManual([],[],[],[],['REQUEST_METHOD' => 'get', 'REQUEST_URI' => '/'],[]);
	$app->run();
	$app->stop();
	$burn = new MrSimonBennett\PHPCPUBurn\Burn();
	$burn->run(0.001);

	//Dummy load the site every type of resquest needs to be run for object loading.
	//This make also benifit things from a testing point of view
	

	class Process extends Thread
	{
        /*
         * @var \Composer\Autoload\ClassLoader
         */
        public $loader;
		public function __construct($client,$routes,$loader = null)
		{
            $this->loader = $loader;
			$this->client = $client;
			$this->routes = $routes;
			unset($client);
			$this->start();
			         
		}
		public function Run()
		{
            if ($this->loader) {
                $this->loader->register();
            }

		
			$response = new MrSimonBennett\HTTP\Responce();
			$request = 	new MrSimonBennett\HTTP\Request($response,new MrSimonBennett\HTTP\Get(),new MrSimonBennett\HTTP\Post(),new MrSimonBennett\HTTP\Server());
			
			$request->read($this->client);
			$routes = $this->routes;
			$process = function($get,$post,$server,$args) 
			{	
				$app = new MrSimonBennett\RestFrameWork\Bootstrap\Application();
				$app->httpFromManual([],[],[],[],['REQUEST_METHOD' => $server->method, 'REQUEST_URI' => $server->uri],[]);
				$app->run();
				$app->stop();
				    	
		    	
			};
			$request->process($process);	
			$response->Go($this->client);
			@socket_shutdown($this->client,STREAM_SHUT_WR);
			
		}


	}
	
	$routes =  file_get_contents(__DIR__ . "/route.php", "r");

	$loop = new MrSimonBennett\Event\Loop();
	$clients = [];

	$count = 0;

	$run = function() use ($socket,&$routes,&$count,&$clients,$stats,&$loader) {
		//echo '.';
		$client = socket_accept($socket->socket);
		$clients[$count] = ['thread' => new Process($client,$routes,$loader), 'client' => $client ];
		unset($client);
		$count++;

        if(($count % 100) == 0)
        {
            //$stats->memory_usage();
        }
	};
	$cleanup =  function() use(&$clients){
		foreach($clients as $key => $client)
		{
			if(!$client['thread']->isRunning())
			{
				//echo '#';
				@socket_shutdown($client['client'],STREAM_SHUT_WR);
				@socket_close($client['client']);
				$client['thread']->join();
				unset($clients[$key]);


			}
		}
	};

	$loop->add($run)->add($cleanup)->run();
