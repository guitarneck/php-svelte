<?php
require 'bootstrap.php';
// require dirname(__DIR__).'/vendor/guitarneck/taphp-color/taphp-color.php';

use PHPSvelte\php\Session;

function TrapOutput ( $filename )
{
   $curdir = __DIR__;
   $nul = PHP_OS !== 'WINNT' ? '/dev/null' : 'nul';
   ob_start();
   system("php -f $curdir/$filename.test.php 2>$nul");
   return ob_get_clean();
}

// echo 'this should takes a couple of seconds...',TAP_EOL;

test('testing ...', function (TAPHP $t) {

   $all = [
      'Session'         => 7
   ];

   foreach ( $all as $tst => $pass )
   {
      $output = TrapOutput($tst);

      $t->comment("--- testing `$tst`");
      $t->ok( strpos($output,'TAP version 13') !== false, 'TAPHP has runned' );
      $t->ok( strpos($output,"# pass  $pass") !== false, "`$tst` should succeed" );
   }

   $t->end();
});