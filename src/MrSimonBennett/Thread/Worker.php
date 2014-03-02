<?php
namespace MrSimonBennett\Thread;

use Worker as PThreadWorker;


class Worker extends PThreadWorker
{
    /**
     * @var \Composer\Autoload\ClassLoader
     */
    protected $loader;

    public function __construct($name,$loader)
    {
        $this->name = $name;

        $this->loader = $loader;

    }
    public function run()
    {
        //var_dump($this->loader);
       // if($this->loader)
         //   $this->loader->register();


    }
}