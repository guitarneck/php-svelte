<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';

use PHPSvelte\php\Session;

Session::savePath(__DIR__ . '/sessions');

Session::start();
echo 'done';