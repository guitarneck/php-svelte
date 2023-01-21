<?php

include_once implode(DIRECTORY_SEPARATOR, [dirname(__DIR__),'sources','php','vendor','autoload.php']);

use PHPSvelte\php\Session;

$lifeTime = 60 * 12;
Session::useCookie(true);
Session::cookieLifetime($lifeTime);
Session::cookieHttpOnly(true);
Session::cookieSecure(true);
Session::gcLifetime($lifeTime);
Session::gcProbability(1,50);
Session::start('PHPSVLT');

define('ROUTES', implode(DIRECTORY_SEPARATOR,[dirname(__DIR__),'sources','php','routes','routes.bin']));
$dispatcher = new Phroute\Phroute\Dispatcher(unserialize(file_get_contents(ROUTES)));

try
{
   $response = $dispatcher->dispatch($_SERVER['REQUEST_METHOD'], parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
}
catch ( \Exception $error )
{
   $page = [dirname(__DIR__),'sources','html'];

   switch ( basename(get_class($error)) )
   {
      // Phroute\Phroute\Exception\HttpRouteNotFoundException
      case 'HttpRouteNotFoundException':
         http_response_code(404);
         $page[] = '404.html';
         break;

      // Phroute\Phroute\Exception\HttpMethodNotAllowedException
      case 'HttpMethodNotAllowedException':
         http_response_code(405);
         $page[] = '405.html';
         break;

      default:
         http_response_code(500);
         $page[] = '500.html';
   }

   $response = file_get_contents(implode(DIRECTORY_SEPARATOR,$page));
}

print $response;

?>