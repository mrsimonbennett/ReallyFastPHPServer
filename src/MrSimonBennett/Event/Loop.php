<?php
namespace MrSimonBennett\Event;

use Closure;
class Loop
{
	private $functions = array();
	public function add(Closure $function,$every = null)
	{
		$this->functions[] = $function;
		return $this;
	}

	public function Run()
	{ 
		while(true)
		{
			//var_dump($this->_functions);
			foreach($this->functions as $function)
			{
				$function();
			}
			
		}
	}
}