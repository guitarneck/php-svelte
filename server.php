<?php

include_once 'sources/php/includes/php/disable-opcache.inc.php';
include_once 'sources/php/tests/WWW.class.php';

$server = new WWW('./www');

function usage ($server)
{
   print "{$server}\n";
   print "\e[1mQ to quit:\e[0m\n";
}

usage($server);

while (1)
{
   print '> ';
   $input = fgets( STDIN );
   switch ( trim($input) )
   {
      case 'Q':
         print "Bye bye...\n";
         break 2;

      case 'help':
      default:
         usage($server);
   }
}
$server->close();
exit;