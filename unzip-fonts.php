<?php

require_once __DIR__ . '/sources/php/includes/php/disable-opcache.inc.php';

define('SYS_SLASH', DIRECTORY_SEPARATOR);
define('WEB_SLASH', '/');

/*
   Extract font files from zip.
      - ZIP_FONTS   : The path to the zip files (glob format)
      - WEB_FONTS   : The path where to extract the font files (direct)
      - WEB_CSS     : The web path where will be the css on web side
      - BUILD_CSS   : The path where create the css for building (undirect)
      - BUILD_FONTS : The path where to extract the fonts for building (undirect)

   (1) An empty char in array end, to add a last DIRECTORY_SEPARATOR to the path.
   (2) Not relative to the site, but to the root directory

   CAUTION !!! The subdirectories of the font files are removed from the destination folder.
   ex: the zipped font in `subdir/font.ttf` will reside as `font.ttf` in the destination folder.
*/
const USE_BUILD = true;
define('ZIP_FONTS', implode(SYS_SLASH,array('sources','fonts','*.zip')));
define('WEB_CSS', implode(SYS_SLASH,array('www','css',''))); // (1)
define('WEB_FONTS', implode(SYS_SLASH,array('www','fonts',''))); // (1) (2)
define('BUILD_CSS', implode(SYS_SLASH,array('sources','svelte','assets','[fontfamily]',''))); // (1)
define('BUILD_FONTS', implode(SYS_SLASH,array('sources','svelte','assets','[fontfamily]','fonts',''))); // (1)

if ( USE_BUILD )
{
   define('DST_CSS', BUILD_CSS);
   define('DST_FONTS', BUILD_FONTS);
}
else
{
   define('DST_CSS', WEB_CSS);
   define('DST_FONTS', WEB_FONTS);
}

// Function parts === BEG

function clearscreen () : void
{
   print "\e[1;1H\e[2J";
}

function usage ( string $text ) : void
{
   print "{$text}\n";
   print "\e[1mQ to quit:\e[0m\n";
}

function format_directory ( string $dir, array $tags ) : string
{
   return str_replace(array_keys($tags), array_values($tags), $dir);
}

function fontfiles () : array
{
   return array('ttf' => array(), 'otf' => array(), 'woff' => array(), 'woff2' => array());
}

function fontformats () : array
{
   return array('ttf' => 'truetype', 'otf' => 'truetype', 'woff' => 'woff', 'woff2' => 'woff2');
}

function select_a_zip_file () : string
{
   $zip = '';

   $zips = glob(ZIP_FONTS);
   if ( $zips === false )
   {
      print "\e[31mError during the access to the zip font files directory\e[0m\n";
      exit;
   }

   if ( empty($zips) )
   {
      print "\e[31mNo zip file in the directory\e[0m\n";
      exit;
   }

   foreach ( $zips as $index => $zipfile )
   {
      $name = basename($zipfile);
      printf("%u: %s\n", 1 + $index, $name);
   }

   $choose = sprintf('Choose a file number from 1 to %u', count($zips));
   usage($choose);
   while (1)
   {
      print '> ';
      $input = trim(fgets( STDIN ));
      switch ( $input )
      {
         case 'Q':
            print "Bye bye...\n";
            break 2;

         case 'help':
         default:
            if ( ctype_digit($input) && $input > 0 && $input <= count($zips) )
            {
               $zip = $zips[$input - 1];
               break 2;
            }
            usage($choose);
      }
   }

   return $zip;
}

function scan_zip_for_fonts ( ZipArchive $archive ) : array
{
   $fontFiles = fontfiles();

   for ( $i = 0; $i < $archive->numFiles; $i++ )
   {
      $filename = $archive->getNameIndex($i);
      $fileinfo = pathinfo($filename);
      if ( ! key_exists('extension', $fileinfo) ) continue;
      if ( ! in_array($fileinfo['extension'],array_keys($fontFiles))) continue;

      array_push($fontFiles[$fileinfo['extension']], $filename);
   }

   return array_filter($fontFiles);
}

function select_one_font_format ( array $formats ) : array
{
   $choose = sprintf('Choose a format between: %s', implode(',',$formats));
   usage($choose);
   while(1)
   {
      print '> ';
      $input = trim(fgets( STDIN ));
      switch ( $input )
      {
         case 'Q':
            print "Bye bye...\n";
            break 2;

         case 'help':
         default:
            if ( ctype_alnum($input) && in_array($input,$formats) )
            {
               $formats = array_values(array_filter($formats,function($v) use($input) {
                  return strcmp($v, $input) === 0;
               }));
               break 2;
            }
            usage($choose);
      }
   }

   return $formats;
}

function one_extension_only_or_exit ( array $fontFiles ) : string
{
   // Available extensions...
   $extensions = array_keys($fontFiles);
   if ( count($extensions) === 0 )
   {
      print "\e[31mNo acceptable font format found...\e[0m\n";
      exit;
   }

   // More than one extension found, only one required !
   if ( count($extensions) > 1 ) $extensions = select_one_font_format($extensions);

   // Again, Only one extension required !
   if ( count($extensions) !== 1 )
   {
      print "\e[31mOops... No font format to extract\e[0m\n";
      exit;
   }

   return $extensions[0];
}

function extract_fonts_from_zip ( string $zip, array $filenames, string $destDir ) : array
{
   print "\n\e[1;4mExtracting the font files :\e[0m\n";

   $extracted = array();
   foreach ( $filenames as $filename )
   {
      $basename = basename($filename);
      $dest = $destDir . $basename;
      if ( @copy("zip://{$zip}#{$filename}", $dest) )
      {
         printf("\e[32m[SUCCESS]\e[0m `%s` => \e[32m%s\e[0m\n", $filename, $dest);
         $extracted[] = $basename;
      }
      else
         printf("\e[31m[FAILURE]\e[0m `%s` was not extracted\e[0m\n", $filename);
   }

   return $extracted;
}

function create_stylesheet ( array $extracted, string $fontFamily, string $fontFormat, string $destDir ) : bool
{
   $fontFace = '';
   foreach ( $extracted as $name )
   {
      $fontWeight = stripos($name, 'bold') !== false ? 'bold' : 'normal';
      $fontStyle = stripos($name, 'italic') !== false ? 'italic' : 'normal';
      $url = implode(WEB_SLASH, array('',basename(WEB_FONTS),$name));
      $fontFace .= "@font-face {\n\tfont-family: $fontFamily;\n\tsrc: url($url) format('$fontFormat');\n\tfont-weight: $fontWeight;\n\tfont-style: $fontStyle;\n}\n";
   }

   $css = $destDir . $fontFamily . '.css';
   return @file_put_contents($css, rtrim($fontFace)) && print "\e[32m`{$css}` created\e[0m\n";
}

// Function parts === END

clearscreen();
print "\e[1mLet's extract font files from zip\e[0m\n";
printf("\e[34mFrom: %s\e[0m\n", dirname(ZIP_FONTS));
printf("\e[34mInto: %s\e[0m\n\n", substr(DST_FONTS,0,-1));

// Select the zip file
$zip = select_a_zip_file();
if ( empty($zip) ) exit;

$fontFamily = strtolower(basename($zip, '.zip'));

$tags = [
   '[fontfamily]' => $fontFamily
];

$archive = new ZipArchive();
if ( $archive->open($zip, ZipArchive::RDONLY) === true )
{
   // whatever happens, we need to close the archive
   register_shutdown_function(function() use($archive) {
      $archive->close();
   });

   // Scan the archive for available font files
   $fontFiles = scan_zip_for_fonts($archive);

   // Available extensions, but only one must be used
   $extension = one_extension_only_or_exit($fontFiles);

   // Extract can goes on
   $extracted = extract_fonts_from_zip($zip, $fontFiles[$extension], format_directory(DST_FONTS, $tags));
   if ( empty($extracted) )
   {
      print "\e[31m[ERROR]\e[0m No font extracted.\e[0m\n";
      exit;
   }

   // Creating the font-face css file
   create_stylesheet($extracted, $fontFamily, fontformats()[$extension], format_directory(DST_CSS, $tags));
}