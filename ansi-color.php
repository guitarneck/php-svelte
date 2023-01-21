<?php
/*
   PHP and ANSI colors, demo

   https://en.wikipedia.org/wiki/ANSI_escape_code

   usage:
      php -f ansi-color.php [-- [--4][--8][--24]]
*/

// Options ===
$do4  = false;
$do8  = false;
$do24 = false;

if ( $argc > 1 )
{
   $do4  = in_array('--4', $argv);
   $do8  = in_array('--8', $argv);
   $do24 = in_array('--24', $argv);
}
else
{
   $do4 = $do8 = $do24 = true;
}

// Helpers ===
function title ( $text )
{
   $text = strtoupper($text);
   print "\n\e[1m{$text}\e[0m\n";
}

function section ( $text )
{
   static $count = 1;
   print "\n{$count}) \e[4m{$text}\e[0m\n\n";
   $count++;
}

function clearscreen ()
{
   print "\e[1;1H\e[2J";
}

// Main ===
clearscreen();

title('PHP and ANSI colors, demo');

if ( $do4 )
{
   section('3-bit and 4-bit, ESC[...m');

   // Fareground colors
   foreach ( range(0,7) as $unit )
   {
      $unit_pad = str_pad("3$unit", 3, ' ', STR_PAD_LEFT);
      print "\e[3{$unit}m{$unit_pad}\e[0m\t";
   }
   print "\n";

   // Background colors
   foreach ( range(0,7) as $unit )
   {
      $unit_pad = str_pad("4$unit", 3, ' ', STR_PAD_LEFT);
      print "\e[4{$unit}m{$unit_pad}\e[0m\t";
   }
   print "\n";

   // Bright fareground colors
   foreach ( range(0,7) as $unit )
   {
      $unit_pad = str_pad("9$unit", 3, ' ', STR_PAD_LEFT);
      print "\e[9{$unit}m{$unit_pad}\e[0m\t";
   }
   print "\n";

   // Bright background colors
   foreach ( range(0,7) as $unit )
   {
      $unit_pad = str_pad("10$unit", 3, ' ', STR_PAD_LEFT);
      print "\e[10{$unit}m{$unit_pad}\e[0m\t";
   }
   print "\n";
}

if ( $do8 )
{
   if ( $do4 ) print "\n";

   section('8-bit, fareground, ESC[38;5;...m');

   // Fareground colors
   foreach ( range(0,7) as $unit ) // standard : as in ESC [ 30–37 m
   {
      printf("\e" . '[38;5;%1$um%1$\' 4u' . "\e[0m", $unit);
   }
   print "\n";

   foreach ( range(8,15) as $unit ) // bright : as in ESC [ 90–97 m
   {
      printf("\e" . '[38;5;%1$um%1$\' 4u' . "\e[0m", $unit);
   }

   foreach ( range(0,215) as $unit ) // 216 colors : 6 × 6 × 6 (216 colors), 16 + 36 × r + 6 × g + b (0 ≤ r, g, b ≤ 5)
   {
      if ( $unit % 36 === 0 ) print "\n";
      $u = $unit + 16;
      printf("\e" . '[38;5;%1$um%1$\' 4u' . "\e[0m", $u);
   }
   print "\n";

   foreach ( range(232,255) as $unit ) // greyscale : grayscale from dark to light in 24 steps
   {
      printf("\e" . '[38;5;%1$um%1$\' 4u' . "\e[0m", $unit);
   }

   print "\n\n";

   section('8-bit, background, ESC[48;5;...m');

   // Background colors
   foreach ( range(0,7) as $unit ) // standard
   {
      printf("\e" . '[48;5;%1$um%1$\' 4u' . "\e[0m", $unit);
   }
   print "\n";

   foreach ( range(8,15) as $unit ) // bright
   {
      printf("\e" . '[48;5;%1$um%1$\' 4u' . "\e[0m", $unit);
   }

   foreach ( range(0,215) as $unit ) // 216 colors
   {
      if ( $unit % 36 === 0 ) print "\n";
      $u = $unit + 16;
      printf("\e" . '[48;5;%1$um%1$\' 4u' . "\e[0m", $u);
   }
   print "\n";

   foreach ( range(232,255) as $unit ) // greyscale
   {
      printf("\e" . '[48;5;%1$um%1$\' 4u' . "\e[0m", $unit);
   }

   print "\n";
}

if ( $do24 )
{
   if ( $do4 || $do8 ) print "\n";

   section('24-bit, background, ESC[48;2;...;...;...m');

   // Backround colors
   foreach ( range(0,255,16) as $r )
   {
      foreach ( range(0,255,8) as $g )
      {
         foreach ( range(0,255,4) as $b )
         {
            print "\e[48;2;{$r};{$g};{$b}m \e[0m";
         }
         print "\n";
      }
   }
}