<?php
namespace MrSimonBennett\Thread;
use MrSimonBennett\Thread\Worker;

/**
 * Thread Pool
 * Used to manage threads in the application
 * @package MrSimonBennett\Thread
 */
class Pool
{
    private $size;

    private $workers;

    /**
     * @param $size Thread Pool Worker Size
     */
    public function __construct($size,$loader)
    {
        $this->size = $size;
        $this->loader = $loader;
    }
    public function SpawnWorker($task)
    {
        echo 'test';
        for($i = 0; $i < $this->size; $i++)
        {
            $this->workers[$i] = new Worker($i,$this->loader);

            $this->workers[$i]->start();

            $this->workers[$i]->stack($task); //We use dummy tasks to warm the pool up
            echo $i;
        }
    }

    public function submit(Stackable $task)
    {

    }
    public function setLoader(ClassLoader $loader)
    {
        $this->loader = $loader;
        return $this;
    }
    public function getLoader()
    {
        return $this->loader;
    }
}