<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';

use PHPSvelte\php\Session;

Session::savePath(__DIR__ . '/sessions');

const SESSNAME = 'MYSESS1234';

Session::start(SESSNAME);
echo Session::name() === SESSNAME ? 'ok' : 'ko';