<?php
namespace MrSimonBennett\RestFrameWork\Bootstrap;

use Symfony\Component\HttpFoundation\Request;


class Application
{
	/**
	 * @var Symfony\Component\HttpFoundation\Request
	 */
	protected $request;

	/**
	 * Config_Routes
	 * @var Config_Routes
	 */
	protected $routes;

	public function __construct()
	{

		$this->routes = new Routing();
		$this->controller = new Controller();
	}

	public function httpFromGlobals()
	{
		$this->request = Request::createFromGlobals();
		return $this;
	}
	public function httpFromManual($get,$post,$cookies,$files,$server)
	{
		$this->request = new Request($get,$post,[],$cookies,$files,$server);
		return $this;
	}
	/**
	 * The Method that gets called at runtime from the outside.
	 * Performs all the work for MrSimonBennett/RestFrameWork Application
	 * 1) Load Routes
	 * 2) Match Routes
	 * 3) Load matching route or error page 404 then exit
	 * 4) evaulate the return from the controller
	 * 5) return the request correctly
	 * 6) return void
	 */
	public function run()
	{
		$this->routes->setContext($this->request);
		$controllerParams = $this->routes->controllerParam($this->request);

		$this->controller->setParams($controllerParams);
		$this->controller->Load();
	}
	/**
	 * Make sure everything has been closed down. Always nice to clean up memory as well (helpful for my HTTP Server)
	 */
	public function stop()
	{
		//Clean Up All
	}
}
