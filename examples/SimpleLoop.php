<?php
/**
 * The Simplest Socket server
 * I/O Blocking 
 * The server has to finish with one connection before the next connection can begin
 */
	require __DIR__ . '/../vendor/autoload.php'; 

	$error = new MrSimonBennett\Server\Error();
	register_shutdown_function(array($error,'shut'));
	set_error_handler(array($error,'handler'));

	$socket = new MrSimonBennett\Server\Socket();
	$stats = new MrSimonBennett\Server\Stats();
	$socket->port(8000);
	$socket->address(0);
	$socket->Create();
	$runcount = 0;

    /**
     * @param $get
     * @param $post
     * @param $server
     * @param $args
     */
    $process = function($get,$post,$server,$args)
	{	
		var_dump($get,$post,$server,$args);
		$app = new MrSimonBennett\RestFrameWork\Bootstrap\Application();

		$app->httpFromManual([],[],[],[],['REQUEST_METHOD' => $server->method, 'REQUEST_URI' => $server->uri],[]);
		$app->run();
		$app->stop();
	};


	$loop = new MrSimonBennett\Event\Loop();
	$loop->add(function() use (&$socket,$process, &$runcount,$stats) {
		$stats->start();
		
		
		$client =  socket_accept($socket->socket);	
		$response = new MrSimonBennett\HTTP\Responce();
		$request = 	new MrSimonBennett\HTTP\Request($response,new MrSimonBennett\HTTP\Get(),new MrSimonBennett\HTTP\Post(),new MrSimonBennett\HTTP\Server());
		
		$request->read($client);
		$request->process($process);

		
		
		$response->Go($client);
		

		//Really Important Line. This Closes the socket correctly
		socket_shutdown($client, STREAM_SHUT_WR); 

		socket_close($client);
		
		$runcount++;
		echo $runcount;

		
		$stats->end();
		foreach (get_defined_vars() as $allVars) { unset($allVars); }
	});
	

	$loop->run();


