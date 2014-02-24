<?php 
/**
 * Socket
 */
namespace MrSimonBennett\Server;

class Stats
{
	public function memory_usage($peak = false)
	{
		if($peak)
        	$mem_usage = memory_get_peak_usage(false); 
    	else
    		$mem_usage = memory_get_usage(true);
        
        if ($mem_usage < 1024) 
            echo $mem_usage." bytes"; 
        elseif ($mem_usage < 1048576) 
            echo round($mem_usage/1024,2)." kilobytes"; 
        else 
            echo round($mem_usage/1048576,2)." megabytes"; 
            
       
    
	}
	public function start()
	{
		
		$this->_start_time = microtime(true);
	}
	public function end()
	{
		$this->_end_time = microtime(true);
	}
	public function RunTime()
	{
		return $this->_end_time - $this->_start_time;
	}
	public function RunTime_ToString()
	{
		return "\r\nRun Time:" . round($this->RunTime(),5) . 'ms';
	}
}