<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';

use PHPSvelte\php\Session;

Session::savePath(__DIR__ . '/sessions');
Session::gcLifetime(1);
Session::start();
sleep(2);
$sessions = glob(__DIR__ . '/sessions/*');
printf('%u/%u sessions(s) killed', Session::gc(), count($sessions));
Session::destroy();
Session::abort();