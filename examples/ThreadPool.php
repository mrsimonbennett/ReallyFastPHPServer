<?php
    /**
     * This is the event loop multithreaded approach to the server
     * We will have a thread pool of workers that will implement the work
     */

    $loader = require __DIR__ . '/../vendor/autoload.php';
    $loader->register();

    $burn = new MrSimonBennett\PHPCPUBurn\Burn();
    $burn->run(0.001);

    /**
     * We are just overriding the error catching stuff (basily making sure that warning don't kill the server
     */
    $error = new MrSimonBennett\Server\Error();
    register_shutdown_function(array($error,'shut'));
    set_error_handler(array($error,'handler'));


    class ExampleWork extends Stackable {
        public function __construct($data) {
            $this->local = $data;

        }
        public function reset($data) {
            $this->local = $data;
            var_dump($data);
        }
        public function run() {
            /**
             * Register Composer autoloader
             */
            $data = $this->local;


            $start = microtime(true);
            $runTime = 0.001;
            if($runTime !== -1)
                $limit = $runTime;



            $pi = 4; $top = 4; $bot = 3; $minus = TRUE;
            $accuracy = 1000000000000;

            for($i = 0; $i < $accuracy; $i++)
            {
                $pi += ( $minus ? -($top/$bot) : ($top/$bot) );
                $minus = ( $minus ? FALSE : TRUE);
                $bot += 2;
                if(($start + $limit) < microtime(true) && $runTime !== -1)
                    break;
            }
            $post = $data['post'];
            $get = $data['get'];
            $server = $data['server'];

            //Process the request

            $this->local = 'Website Loaded';


            $this->worker->addAttempt();
            $this->worker->addData(
                $this->local
            );
        }
        public function getData() 				{ return $this->local; }
    }
    class ExampleWorker extends Worker {

        public function __construct($name) {
            $this->name = $name;
            $this->data = array();
            $this->setup = false;
            $this->attempts = 0;
        }
        public function run(){
            $this->setName(sprintf("%s (%lu)", $this->getName(), $this->getThreadId()));
        }
        public function setSetup($setup)	{ $this->setup = $setup; }
        public function getName() 			{ return $this->name; }
        public function setName($name)		{ $this->name = $name; }
        public function addAttempt() 		{ $this->attempts++; }
        public function getAttempts()		{ return $this->attempts; }
        public function setData($data)		{ $this->data = $data; }
        public function addData($data)		{ $this->data = array_merge($this->data, array($data)); }
        public function getData()			{ return $this->data; }
    }
    /* Dead simple pthreads pool */
    class Pool {
        /* to hold worker threads */
        public $workers;
        /* to hold exit statuses */
        public $status;
        /* prepare $size workers */
        public function __construct($size = 10) {
            $this->size = $size;
        }
        /* submit Stackable to Worker */
        public function submit(Stackable $stackable) {
            if (count($this->workers)<$this->size) {
                $id = count($this->workers);
                $this->workers[$id] = new ExampleWorker(sprintf("Worker [%d]", $id));
                $this->workers[$id]->start(PTHREADS_INHERIT_NONE);

                if ($this->workers[$id]->stack($stackable))
                {
                    return $stackable;
                }
                else
                {
                    trigger_error(sprintf("failed to push Stackable onto %s", $this->workers[$id]->getName()), E_USER_WARNING);
                }
            }
            if ($select = $this->workers[array_rand($this->workers)])
            {
                if ($select->stack($stackable)) {
                    return $stackable;
                }
                else
                {
                    trigger_error(sprintf("failed to stack onto selected worker %s", $select->getName()), E_USER_WARNING);
                }
            }
            else
            {
                trigger_error(sprintf("Failed to select a worker for Stackable"), E_USER_WARNING);
            }
            return false;
        }
        /* Shutdown the pool of threads cleanly, retaining exit status locally */
        public function shutdown() {
            foreach($this->workers as $worker) {
                $this->status[$worker->getThreadId()]=$worker->shutdown();
            }
        }
    }
    $start = microtime(true);
    /* Create a pool of ten threads */
    $pool = new Pool(5);
    /* Create and submit an array of Stackables */
    $work = array();
    for ($target = 0; $target < 1; $target++)
    {
        $work[] = $pool->submit(new ExampleWork(['get'=>'','post'=>'','server' => ''], $loader));

    }
$exit = false;
do
{
    foreach($work as $worker)
    {
        if($worker->isRunning())
            continue;
        else
        {
            $time = time();
            $data = ['get'=> $time,'post'=>'','server' => ''];
            $worker->reset($data);
            $pool->submit($worker);
           // $exit = true;
        }
    }
}while($exit == false);


    $pool->shutdown();

    /*
    * Look inside

    $runtime = (microtime(true)-$start);
    $sapi = isset($_SERVER["HTTP_HOST"]);
    if ($sapi) echo "<pre>";
    printf("---------------------------------------------------------\n");
    printf("Executed %d tasks in %f seconds in %d threads\n", count($work), $runtime, 10);
    printf("---------------------------------------------------------\n");
    if ($sapi)
        printf("%s | %.3fMB RAM\n", $_SERVER["SERVER_SOFTWARE"], memory_get_peak_usage(true)/1048576);
    else printf("%.3fMB RAM\n", memory_get_peak_usage(true)/1048576);
    printf("---------------------------------------------------------\n");
    $attempts = 0;
    foreach($pool->workers as $worker) {
        printf("%s made %d attempts ...\n", $worker->getName(), $worker->getAttempts());
        print_r($worker->getData());
        $attempts+=$worker->getAttempts();
    }
    printf("---------------------------------------------------------\n");
    printf("Average processing time of %f seconds per task\n", $runtime/$attempts);
    printf("---------------------------------------------------------\n");
    if ($sapi) echo "</pre>";
    */