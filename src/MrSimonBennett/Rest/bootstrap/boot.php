<?php
/**
 * Boot the Application up :)
 * Before you ask yes alot of the ideas for how to stucture code with framework's and applications did come from laravel
 */

/**
 * Spin up Composer Autoloader (magic)
 */
require __DIR__.'/../vendor/autoload.php';

$application = new MrSimonBennett\Bootstrap\Application();

return $application;
