<?php

require_once 'bootstrap.php';
require_once 'WWW.class.php';

use PHPSvelte\http\HTTPHeader;

$www = new WWW(__DIR__);

/*
function getTagFromHTTPHeader ( $header, $tag )
{
   return array_filter($header, function ($h) use ($tag) { return strpos($h,$tag)===0; }); // 'Set-Cookie:' only
}

function getCookieCrumbsFromHTTPHeader ( $header )
{
   $cookies = getTagFromHTTPHeader($header, 'Set-Cookie:'); // 'Set-Cookie:' only
   return array_map(function ($c) { return trim(preg_split('/:|;/',$c,3)[1]); }, $cookies); // Cookies variables only
}
*/
function getSetCookieCrumbs ( HTTPHeader $header )
{
   $cookies = $header->get(HTTPHeader::SET_COOKIE);
   return array_map(function ($c) { return trim(preg_split('/:|;/',$c,3)[1]); }, $cookies); // Cookies variables only
}

test('Session - start',function(TAPHP $t) use($www) {
   $t->equal($www->get('Session00.test.php')[0],'done','Session should be started');
   $t->end();
});

test('Session - set/get/has',function(TAPHP $t) use($www) {
   $t->equal($www->get('Session01.test.php')[0],'missing','No parameter so far');
   list($body, $head) = $www->get('Session01.test.php?parm=ok');
   $t->equal($body,'done','Parameter has been sets');

   // $t->comment(var_export($head,true));
   // $cookies = getCookieCrumbsFromHTTPHeader($head);
   // $t->equal($www->get('Session01.test.php',array(),array('Cookie: '.implode('; ',$cookies)))[0],'ok');

   $respHead = new HTTPHeader($head);
   $crumbs = getSetCookieCrumbs($respHead);

   $rqstHead = new HTTPHeader();
   $rqstHead->add(HTTPHeader::COOKIE, implode('; ',$crumbs));

   $t->equal($www->get('Session01.test.php',array(),$rqstHead->get())[0],'ok','Parameter has been restored');

   $t->end();
});

test('Session - name',function(TAPHP $t) use($www) {
   list($body, $head) = $www->get('Session02.test.php');
   $t->equal($body,'ok');

   $respHead = new HTTPHeader($head);
   $cookies = $respHead->get(HTTPHeader::SET_COOKIE);
   $cookies = array_filter($cookies,function($v){return strpos($v,'MYSESS1234') !== false;});
   $t->equal(count($cookies),1,'Session name found');

   $t->end();
});

test('closing $www',function($t) use($www) {
   $t->comment($www->get('Session99.test.php')[0]);
   $www->close();
   $t->pass();
   $t->end();
});