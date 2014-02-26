<?php

namespace MrSimonBennett\RestFrameWork\Bootstrap;

use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;

class Routing
{
	/**
	 * @var Symfony\Component\Routing\RouteCollection
	 */
	protected $routeCollection;

	protected $requestContext;


	public function __construct()
	{
		$this->configRoutes = new \Config_Routes($this);
		$this->routeCollection = new RouteCollection();

		$this->requestContext = new RequestContext();

		$this->configRoutes->DefaultRoutes();


		//$this->routeCollection->add('home', new Route('/', ['_controller' => 'home', 'function' => function(){}], [], [], '', [], array('get')));
		//$this->routeCollection->add('posthome', new Route('/', ['_controller' => 'home', '_method' => 'test'], [], [], '', [], array('post')));
		//$this->routeCollection->add('blog_show', new Route('/blog/{slug}', array('_controller' => 'AcmeBlogBundle:Blog:show')));


	}
	public function add($name, $uri, $params, $method)
	{
		$this->routeCollection->add($name, new Route($uri, $params, [], [], '', [], array($method)));
	}
	public function getRequestContext()
	{
		return new RequestContext();
	}

	public function setContext($request)
	{
		$this->requestContext->fromRequest($request);

	}
	public function urlMatcher()
	{
		return new UrlMatcher($this->routeCollection,$this->requestContext);
	}
	/**
	 * Sets an array with the controller params needed
	 * @param  Symfony\Component\HttpFoundation\Request $request The Request
	 * @return array          Controller settings
	 */
	public function controllerParam($request)
	{
		$matcher = $this->urlMatcher();

		try {
		    $parameters = $matcher->matchRequest($request);
		} catch (ResourceNotFoundException $e) {
			$parameters = ['_controller' => "error", '_method' => 'actionNotFound', '_route' => '404'];
        }
        catch (MethodNotAllowedException $e) {
            $parameters = ['_controller' => "error", '_method' => 'actionNotFound', '_route' => '404'];
        } catch (Exception $e) {
			$parameters = ['_controller' => "error", '_method' => 'actionError', '_route' => '500'];
		}


		return $this->sanitiseParams($parameters);

	}
	protected function sanitiseParams($parameters)
	{
		$params = [
			'controller' => '',
			'method' => '',
			'name' => '',
			'closure' => null,
			'args' => [],
		];
		$params['name'] = $parameters['_route'];
		$params['controller'] = $parameters['_controller'];
		$params['method'] = $parameters['_method'];
		$params['closure'] = (isset($parameters['_closure'])? $parameters['closure'] : null);
		unset($parameters['_controller'],$parameters['_method'],$parameters['_closure'],$parameters['_route']);

		if(count($parameters) > 0)
			foreach($parameters as $argkey => $argvalue)
				$params['args'][] = $argvalue;

		return $params;
	}
}
