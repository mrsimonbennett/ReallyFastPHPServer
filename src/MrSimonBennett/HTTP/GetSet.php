<?php
namespace MrSimonBennett\HTTP
{
	abstract Class GetSet implements IGetSet
	{
		private $_store;
		public function __get($key)
		{
			return $this->_store[$key];	
		}
		public function __set($key,$value)
		{
			$this->_store[$key] = $value ;
		
		}
	}
}