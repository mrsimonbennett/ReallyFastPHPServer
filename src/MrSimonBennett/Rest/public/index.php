<?php
$app = require_once __DIR__.'/../bootstrap/boot.php';

/**
 * This is the default but here to make it clear for when it is not
 */
$app->httpFromGlobals(true);
//$app->httpFromManual([],[],[],[],['REQUEST_METHOD' => 'get', 'REQUEST_URI' => '/blog/tom-test'],[]);
$app->run();

$app->stop();