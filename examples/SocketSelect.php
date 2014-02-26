<?php
/**
 * The Simplest Socket server
 * I/O Blocking
 * The server has to finish with one connection before the next connection can begin
 */
require __DIR__ . '/../vendor/autoload.php';

$error = new MrSimonBennett\Server\Error();
//register_shutdown_function(array($error,'shut'));
//set_error_handler(array($error,'handler'));

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
$process = function($get,$post,$server,$args) {
    $app = new MrSimonBennett\RestFrameWork\Bootstrap\Application();

    $app->httpFromManual([],[],[],[],['REQUEST_METHOD' => $server->method, 'REQUEST_URI' => $server->uri],[]);
    $app->run();
    $app->stop();
};

$sock = $socket->socket;

//array of client sockets
$clients = array($sock);

$write = NULL;
$except = NULL;
//start loop to listen for incoming connections and process existing connections
while (true)
{
    // create a copy, so $clients doesn't get modified by socket_select()
    $read = $clients;

    // get a list of all the clients that have data to be read from
    // if there are no clients with data, go to next iteration
    if (socket_select($read, $write, $except, 0) < 1)
        continue;

    //if ready contains the master socket, then a new connection has come in
    if (in_array($sock, $read))
    {
        $clients[] = $newsock = socket_accept($sock);

        socket_getpeername($newsock, $ip);

        // remove the listening socket from the clients-with-data array
        $key = array_search($sock, $read);
        unset($read[$key]);
    }

    // loop through all the clients that have data to read from
    foreach ($read as $read_sock) {
        // read until newline or 1024 bytes
        // socket_read while show errors when the client is disconnected, so silence the error messages




        $response = new MrSimonBennett\HTTP\Responce();
        $request = 	new MrSimonBennett\HTTP\Request($response,new MrSimonBennett\HTTP\Get(),new MrSimonBennett\HTTP\Post(),new MrSimonBennett\HTTP\Server());

        $request->read($read_sock);
        $request->process($process);



        $response->Go($read_sock);

        //Really Important Line. This Closes the socket correctly
        @socket_shutdown($read_sock, STREAM_SHUT_WR);

        @socket_close($read_sock);
        $key = array_search($read_sock, $clients);
        unset($clients[$key]);


    } // end of reading foreach

}
