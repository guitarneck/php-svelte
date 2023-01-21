<?php

namespace PHPSvelte\bundles;

use PHPSvelte\controllers\Controller;
use Phroute\Phroute\RouteCollector;
use PHPSvelte\php\Session;

class Login extends Controller
{
   function __construct ( RouteCollector $router )
   {
      parent::__construct($router);

      // $router->filter('auth', __NAMESPACE__ . '\\Login::isLogged');

      $router->get('/logout', array($this,'logout')/*, ['before' => 'auth']*/);

      $router->post('/login', array($this,'login'));
   }

   static
   function isLogged ()
   {
      return Session::isEnabled() && Session::has('User');
   }

   function login ()
   {
      $login = filter_input(INPUT_POST,'login',FILTER_SANITIZE_ADD_SLASHES);
      $paswd = filter_input(INPUT_POST,'passwd',FILTER_SANITIZE_ADD_SLASHES);

      Session::start();
      Session::set('User', array('login' => $login));
      Session::commit();

      // parent::nocache();
      // $location = sprintf('Location: %s%s', parent::server(), $this->router->route('home'));
      // header($location, true, 303);
      // exit;
      $this->goHome();
   }

   function logout ()
   {
      Session::destroy();

      // parent::nocache();
      // $location = sprintf('Location: %s%s', parent::server(), $this->router->route('home'));
      // header($location, true, 303);
      // exit;
      $this->goHome();
   }
}