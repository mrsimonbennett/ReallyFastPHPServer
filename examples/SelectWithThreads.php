<?php
/**
 * This is the ultimate design, Thread Pool and socket select
 */

$loader = require __DIR__ . '/../vendor/autoload.php';



//Define Server Config

$workers = 4;
$maxclients = 10000;

/**
 * Error Management
 */
$error = new MrSimonBennett\Server\Error();
//register_shutdown_function(array($error,'shut'));
//set_error_handler(array($error,'handler'));


$burn = new MrSimonBennett\PHPCPUBurn\Burn();
echo $burn->run(0.01);



$loop = new MrSimonBennett\Event\Loop();
$socket = new MrSimonBennett\Server\Socket();
$clientFactory = new MrSimonBennett\Server\ClientFactory();
$pool = new MrSimonBennett\Thread\Pool($workers,$loader);
$pool->SpawnWorker(new MrSimonBennett\Thread\DummyTask('test'));


$socket->port(8000);
$socket->address(0);
$socket->Create();

$clientsockets = array($socket->socket);


$loop->add(function() use (&$socket, &$clientsockets,&$clientFactory, $maxclients) {

    if($maxclients > $clientFactory->ClientCount())
    {
        $write = NULL;
        $except = NULL;

        $readClients = $clientsockets; //Copy so we don't override

        if(socket_select($readClients,$write,$except,0) < 1)
            return; //Skip Loop

        if(in_array($socket->socket,$readClients))
        {
            $newsock = socket_accept($socket->socket);

            socket_getpeername($newsock, $ip);

            // remove the listening socket from the clients-with-data array
            $key = array_search($socket->socket, $readClients);
            unset($readClients[$key]);

            $clientFactory->add(new \MrSimonBennett\Server\Client($newsock,$ip));
        }
        echo 'test';
    }
});
/**
 * Read all the sockets and get there requests
 */
$loop->add(function() use (&$clientFactory){
    if(count($clientFactory->clients()))
    {
        foreach($clientFactory->clients() as $client)
        {
            if($client->status() == 'readable')
            {
                $client->readsocket();
            }
        }
    }
});
/** Free Up thread pool and save the responce in the client factory */
$loop->add(function() use (&$clientFactory){
    if(count($clientFactory->clients()))
    {
        foreach($clientFactory->clients() as $client)
        {
            if($client->status() == 'proccessed')
            {

            }
        }
    }
});
//Add other jobs to the thread pool
$loop->add(function(){

});
//Send off responce and kill that socket
$loop->add(function() use (&$clientFactory) {
    if(count($clientFactory->clients()))
    {
        foreach($clientFactory->clients() as $client)
        {
            if($client->status() == 'finished')
            {
                //Send data

                //close socket

                //remove from client factory
            }
        }
    }
});
/** Sleep We can change this value as the loop slows down. Mabye a clock to make sure it does not run to fast */
$loop->add(function(){usleep(2);});
$loop->Run();