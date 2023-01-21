<?php

const ALPHANUM = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';

function println ($t='') {print "$t\n";}

function generate_hash ( $parms )
{
   // Get the lengths required
   $lens = array_values(array_filter(preg_split('/[^\d]+/', $parms)));

   // Replace lengths by a sequential rank
   $i    = 0;
   $seps = preg_replace_callback('/\d+/', function() use(&$i) {return '%' . $i++;}, $parms);

   $c  = str_split(ALPHANUM,1);
   $lc = count($c);
   for ($i = 0, $l = count($lens); $i < $l; $i++)
   {
      $len = intval($lens[$i]);
      $str = '';
      while ($len--) $str .= $c[floor(rand() % $lc)];
      $seps = str_replace("%{$i}", $str, $seps);
   }

   return trim($seps);
}

if ( php_sapi_name() === 'cli' && empty($_SERVER['DOCUMENT_ROOT']) ):
   $args = array_slice($argv, 1);

   if (count($args) !== 1)
   {
      println('usage: ' . basename(__FILE__) . ' length[[separator][length]...]');
      println('   length        The generated string length required (! > 0)');
      println('   separator     Some optional separators between other lengths');
      println();
      println('   exemple:');
      println('      4-5-4      2RWM-EHO7C-902L');
      println('      10         R2HMH0K69W');
      println();
      exit(-1);
   }

   println(generate_hash($args[0]));
endif;