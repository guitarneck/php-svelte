<?php

namespace PHPSvelte\bundles;

use PHPSvelte\controllers\Controller;
use Phroute\Phroute\RouteCollector;

class Main extends Controller
{
   function __construct ( RouteCollector $router )
   {
      parent::__construct($router);

      $router->get('/get-number', array($this,'getNumber'));

      $router->any(array('/','home'), array($this,'home'));
   }

   function home ()
   {
      parent::nocache();

      return parent::render(__DIR__ . '/Main.view.php');
   }

   function getNumber ()
   {
      parent::nocache();

      $yocounter = filter_var($_REQUEST['yocounter'], FILTER_VALIDATE_INT);

      return parent::render(__DIR__ . '/Main.view.php', array(
         'yocounter' => $yocounter,
      ));
   }
}