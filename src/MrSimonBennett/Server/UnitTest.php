<?php

namespace MrSimonBennett\Server;

class UnitTest
{
	private $runonfail = false;
	public function setRunOnFail($run)
	{
		$this->runonfail = $run;
	}
	public function RunAllTests($dir = '/home/simon/lushserver/tests')
	{
		require_once (__DIR__ . '/../../../vendor/phpunit/phpunit/PHPUnit/Autoload.php');
		$_SERVER['argv'][2] = $dir; 
		$_SERVER['argv'][1] = "--colors";
		$testresults = \PHPUnit_TextUI_Command::main(false);

		if(!$this->runonfail && $testresults !== 0)
			exit('Unit Test Failed Server not started!'. PHP_EOL);
	}
}
