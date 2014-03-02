<?php
define("SECOND", 1000000);
set_time_limit(30);
class Task extends Stackable {
    public function run() {
        /* some random data */
        $this->data = md5(
            mt_rand() * microtime());
    }
}

class Background extends Worker {
    public function run() {}
}

$tasks = [];

/* this allows you to get the next free slot and stack
	a job in that slot while processing the data the previous
	task in that slot generated */
function get_next_task($tasks, &$done) {
    foreach ($tasks as $id => $task ){
        if ($task->data) {
            $done = $task;

            return $id;
        }
    }
    return count($tasks);
}

$background = new Background();
$background->start();
$i = 0;

do {
    ++$i;
    $next = get_next_task($tasks, $done);
    $tasks[$next] = new Task();
    $background
        ->stack($tasks[$next]);
    /* got something to deal with */
    if ($done)
        var_dump($i . ' ' .  $done->data);
} while(1);
?>