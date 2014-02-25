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

    var_dump($get,$post,$server,$args);
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
        echo "New client connected: {$ip}\n";

        // remove the listening socket from the clients-with-data array
        $key = array_search($sock, $read);
        unset($read[$key]);
    }

    // loop through all the clients that have data to read from
    foreach ($read as $read_sock) {
        // read until newline or 1024 bytes
        // socket_read while show errors when the client is disconnected, so silence the error messages
        $data = @socket_read($read_sock, 1024, PHP_NORMAL_READ);

        // check if the client is disconnected
        if ($data === false) {
            // remove client for $clients array
            $key = array_search($read_sock, $clients);
            unset($clients[$key]);
            echo "client disconnected.\n";
            // continue to the next client to read from, if any
            continue;
        }

        // trim off the trailing/beginning white spaces
        $data = trim($data);

        // check if there is any data after trimming off the spaces
        if (!empty($data)) {

            // send this to all the clients in the $clients array (except the first one, which is a listening socket)
            foreach ($clients as $send_sock) {

                // if its the listening sock or the client that we got the message from, go to the next one in the list
                if ($send_sock == $sock || $send_sock == $read_sock)
                    continue;

                // write the message to the client -- add a newline character to the end of the message
                socket_write($send_sock, $data."\n");

            } // end of broadcast foreach

        }

    } // end of reading foreach
    usleep(0);
}
