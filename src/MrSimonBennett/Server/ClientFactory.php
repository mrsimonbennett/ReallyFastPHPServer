<?php
namespace MrSimonBennett\Server;
class ClientFactory
{
    private $clients;
    public function add(Client $client)
    {
        $this->clients[] = $client;
        return $this;
    }
    public function clients()
    {
        return $this->clients;
    }
    public function ClientCount()
    {
        return count($this->clients);
    }
}
