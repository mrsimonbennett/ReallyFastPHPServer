<?php
use MrSimonBennett\RestFrameWork\Bootstrap\Routing;
class Config_Routes
{
	/**
	 * @var MrSimonBennett\Bootstrap\Routing
	 */
	protected $routing;				


	/**
	 * [__construct description]
	 * @param MrSimonBennett\Bootstrap\Routing $routing 
	 */
	public function __construct(Routing $routing)
	{
		$this->routing = $routing;
	}
	/**
	 * The default routes the application Will Have
	 * I am not happy with the way this works but it is the easies way to get round the multirun issue's BennettHTTP server will sufer.
	 * @link Simon Bennett's Disitation 5.0
	 * @return [type] [description]
	 */
	public function DefaultRoutes()
	{
		$this->routing->add('home', '/', ['_controller' => 'HomeController','_method' => 'actionGetHomepage' ], 'get');

		$this->routing->add('cpuburn', '/burn/{time}', ['_controller' => 'HomeController','_method' => 'actionBurn' ], 'get');


	}
}
