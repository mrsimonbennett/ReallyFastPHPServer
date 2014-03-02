<?php
namespace MrSimonBennett\Thread;
use MrSimonBennett\PHPCPUBurn\Burn;
class DummyTask extends \Stackable
{
    public function __construct($data)
    {
        $this->local = $data;
    }
    public function run()
    {

       //echo 'Worker: ' . $this->worker->name . ' Ready to beast it'. PHP_EOL;


        sleep(1);//Really don't ask
        $burn = new \MrSimonBennett\PHPCPUBurn\Burn();
        $burn->run(10);

    }

}