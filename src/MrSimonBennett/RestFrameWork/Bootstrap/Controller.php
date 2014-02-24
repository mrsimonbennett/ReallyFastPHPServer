<?php

namespace MrSimonBennett\RestFrameWork\Bootstrap;

use MrSimonBennett\RestFrameWork\Controller\ControllerNotFoundException;

class Controller
{
	/**
	 * An array of all the values needed to load a controller
	 * @var [type]
	 */
	protected $controllerSettings;

	/**
	 * Controller Return Value
	 * @var mixed (Json,Plain,int)
	 */
	protected $controllerResult;

	public function setParams($controlerSettings)
	{
		$this->controllerSettings = $controlerSettings;
		return $this;
	}

	public function Load()
	{
		$cs = $this->controllerSettings;
		$controllername = $cs['controller'];
		$methodname = $cs['method'];
		if (!class_exists($controllername)) {
			//throw new ControllerNotFoundException($controllername);
			$responce = 'not found';
		} else {
			$controller = new $controllername();
			if (!method_exists($controller, $methodname)) {
				throw new ControllerNotFoundException();
			} else {
				$responce = $this->CallMethod($controller,$methodname,$cs['args']);
				echo($responce);
			}

		}

	}
	/**
	 * Call the method on the controller
	 * @param  ControllerClass $controller The Controller Class to use	 
	 * @param string $methodname The name of the method to call
	 * @param array $args       The arguments the function needs loaded from the routes
	 * Note: This method runs like this for speed gain. loading diamicaly is slow
	 * See https://github.com/vanillaforums/Garden/blob/master/library/core/class.factory.php
	 */
	protected function CallMethod($controller,$methodname,$args)
	{
		return call_user_func_array(array($controller, $methodname), $args);
	}


}
