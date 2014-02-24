<?php
class HomeController
{
	public function actionGetHomepage()
	{
		
	}
	public function actionBurn($time)
	{
		$burn = new MrSimonBennett\PHPCPUBurn\Burn();
		return "PI: " . $burn->run($time);
	}
	
	public function actioneditBlogPost($name)
	{
		return 'Title: ' . $name;
	}
}
