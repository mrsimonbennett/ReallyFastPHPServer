<?php
//require 'vendor/autoload.php';
class StatsTest extends PHPUnit_Framework_TestCase
{
	public function testStatsinstantiate()
	{
		$stats = new \MrSimonBennett\Server\Stats();

		$this->assertInstanceOf('\MrSimonBennett\Server\Stats',$stats);
	}
	public function testMemory_usage()
	{
		$stats = new \MrSimonBennett\Server\Stats();

		$stats->start();
		
		$stats->end();
		$this->assertTrue(is_numeric($stats->RunTime()), 'RunTime is not a number');
	}

}