<?php
namespace MrSimonBennett\Event;


class Loop
{
	private $_functions = array();
	public function add($function,$every = null)
	{
		$this->_functions[] = $function;
		return $this;
	}

	public function Run()
	{ 
		$i =0;
		while(true)
		{
			//var_dump($this->_functions);
			foreach($this->_functions as $function)
			{
				$function();
			}
			
		}
	}
}