<?php

if ( php_sapi_name() !== 'cli' || ! empty($_SERVER['DOCUMENT_ROOT']) ) die('cli only !');

include_once implode(DIRECTORY_SEPARATOR, [dirname(__DIR__),'includes','php','disable-opcache.inc.php']);
include_once implode(DIRECTORY_SEPARATOR, [dirname(__DIR__),'vendor','autoload.php']);

error_reporting(E_ALL);

define('CACHE', implode(DIRECTORY_SEPARATOR,[__DIR__,'routes.bin']));

if ( @filemtime(__FILE__) <= @filemtime(CACHE) ) exit("\e[34mroutes.bin cache is up to date. Nothing to do.\e[0m\n");

use Phroute\Phroute\RouteCollector;
use PHPSvelte\bundles\Main;
use PHPSvelte\bundles\Login;

$router = new RouteCollector();

$router->get('/info', 'phpinfo');

new Main($router);
new Login($router);

print "\e[32mGenerating routes cache in: `". CACHE . "`\e[0m\n";

if ( file_exists(CACHE) ) unlink(CACHE);
file_put_contents(CACHE, serialize($router->getData()));