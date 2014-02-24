//<?php
$routes->Get('/',function(){
	$start = microtime(true);
	
	$limit = 0.001;

	$pi = 4; $top = 4; $bot = 3; $minus = TRUE;
	$accuracy = 10000000;

	for($i = 0; $i < $accuracy; $i++)
	{
		$pi += ( $minus ? -($top/$bot) : ($top/$bot) );
		$minus = ( $minus ? FALSE : TRUE);
		$bot += 2;
		if(($start + $limit) < microtime(true))
			break;
	}
	print "Pi ~=: " . $pi;
});
