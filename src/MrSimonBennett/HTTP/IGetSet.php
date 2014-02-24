<?php
namespace MrSimonBennett\HTTP
{
	interface IGetSet 
	{
		public function __get($key);
		public function __set($key,$value);
	}
}