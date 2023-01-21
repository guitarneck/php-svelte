<?php
/*
   php -S 192.168.1.50:80 -t www -d opcache.enable=false
 */
ini_set('opcache.enable', false);

include_once implode(DIRECTORY_SEPARATOR, [dirname(__DIR__),'sources','php','vendor','autoload.php']);

use PHPSvelte\php\Memory;

if ( php_sapi_name() === 'cli' && empty($_SERVER['DOCUMENT_ROOT']) )
{
   $hostname = gethostname();
   print "\e[1m{$hostname}\e[0m\n";
   foreach ( gethostbynamel($hostname) as $ip )
   {
      printf("%s : %s\n", str_pad($ip, (3 * 4) + 3, ' '), gethostbyaddr($ip));
   }
   exit;
}

function isRefererOrigin ()
{
   if ( ! key_exists('HTTP_REFERER', $_SERVER) ) return false;
   return substr($_SERVER['HTTP_REFERER'], 0, strlen($_SERVER['HTTP_ORIGIN'])) === $_SERVER['HTTP_ORIGIN'];
}

define('FNAME', 'download');

$saved = '';
$accept = 'image/svg+xml, image/tiff, image/gif, image/png, image/jpeg, image/bmp, image/webp';
if ( key_exists(FNAME,$_FILES) ):
   // var_export($_FILES[FNAME]);
   // array (
   //    'name'      => 'photo.JPG',
   //    'type'      => 'image/jpeg',
   //    'tmp_name'  => 'C:\\Users\\Laurent\\AppData\\Local\\Temp\\phpD7CE.tmp',
   //    'error'     => 0,
   //    'size'      => 1442802,
   // )
   switch ( $_FILES[FNAME]['error'] )
   {
      case UPLOAD_ERR_INI_SIZE:
         // The uploaded file exceeds the upload_max_filesize directive in php.ini.
      case UPLOAD_ERR_FORM_SIZE:
         // The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.

         $saved = '[FAIL] File size too big.';
         break;

      case UPLOAD_ERR_PARTIAL:
         // The uploaded file was only partially uploaded.
      case UPLOAD_ERR_NO_FILE:
         // No file was uploaded.

         $saved = '[FAIL] File was not uploaded.';
         break;

      case UPLOAD_ERR_NO_TMP_DIR:
         // Missing a temporary folder.
      case UPLOAD_ERR_CANT_WRITE:
         // Failed to write file to disk.

         $saved = '[FAIL] System file on error.';
         break;

      case UPLOAD_ERR_EXTENSION:
         // A PHP extension stopped the file upload. PHP does not provide a way to ascertain which
         // extension caused the file upload to stop; examining the list of loaded extensions with phpinfo() may help.

         $saved = '[FAIL] Internal error.';
         break;

      case UPLOAD_ERR_OK:
         // There is no error, the file uploaded with success.
         $info = pathinfo($_FILES[FNAME]['name']);
         $dest = array(
            sys_get_temp_dir(),
            sprintf('%s-%s.%s', $info['filename'], date('Y-m-d,His'), strtolower($info['extension']))
         );
         if ( move_uploaded_file($_FILES[FNAME]['tmp_name'], implode(DIRECTORY_SEPARATOR, $dest)) )
         {
            $saved = 'Saved at ' . implode(DIRECTORY_SEPARATOR, $dest);
         }
   }
endif;
?>
<html>
   <head>
   </head>
   <body>
      <form method="POST" enctype="multipart/form-data">
         <input type="hidden" name="MAX_FILE_SIZE" value="<?= Memory::uploadMaxSize()->__toNumber() ?>"/>
         <input type="file" name="<?= FNAME ?>" accept="<?= $accept ?>"/>
         <input type="submit" />
      </form>
<?    if ( ! empty($saved) ):?>
      <p><b <?= substr($saved,0,6) === '[FAIL]' ? 'style="color:red"': ''?>><?= htmlspecialchars($saved) ?></b></p>
<?    endif; ?>
   </body>
</html>