<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';

use PHPSvelte\php\Session;

Session::savePath(__DIR__ . '/sessions');

if ( array_key_exists('parm',$_GET) )
{
   Session::start();
   Session::set('parm', $_GET['parm']);
   Session::commit();
   echo 'done';
}
else
{
   Session::start();
   echo Session::has('parm') ? Session::get('parm') : 'missing';
}