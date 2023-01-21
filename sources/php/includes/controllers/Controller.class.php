<?php

namespace PHPSvelte\controllers;

use Phroute\Phroute\RouteCollector;

abstract
class Controller
{
   protected $router;

   function __construct ( RouteCollector $router )
   {
      $this->router = $router;
   }

   protected
   function render ( $template, $params=[] )
   {
      ob_start();
      include $template;
      return ob_get_clean();
   }

   function goHome ()
   {
      static::nocache();
      $location = sprintf('Location: %s%s', static::server(), $this->router->route('home'));
      header($location, true, 303);
      exit;
   }

   static
   function server ()
   {
      $protocol = array_key_exists('HTTPS',$_SERVER) && !empty($_SERVER['HTTPS']) ? 'https' : 'http';
      $host = $_SERVER['HTTP_HOST'];
      return sprintf('%s://%s', $protocol, $host);
   }

   static
   function nocache ()
   {
      header('Cache-Control: no-cache, no-store, must-revalidate');
      header('Pragma: no-cache');
      header('Expires: 0');
   }
}