<?
use PHPSvelte\php\Session;
?>
<!DOCTYPE html>
<html>
   <head>
      <title>First</title>
      <? include dirname(__DIR__,2) . '/includes/html/nocache.html' ?>
      <style>
         img.vendors.php {
            height:60px;
            vertical-align:middle
         }
         img.vendors.svelte {
            height:60px;
            vertical-align:middle
         }
         figure {
            font-family: cursive;
            font-size:2.5em;
            color:#888;
            margin:0
         }
      </style>
   </head>
   <body>
      <figure><img class="vendors php" src="/img/logo-php.svg" /> &amp; <img class="vendors svelte" src="/img/logo-svelte.svg" /></figure>
      <h1>First v1.0.3</h1>
      <h2 style="font-family: bainsley">PHP <?= phpversion() ?></h2>
      <svelte-loger name="<?= Session::has('User') ? Session::get('User')['login'] : '' ?>"></svelte-loger>
      <form method="GET" action="/get-number">
         <svelte-counter name="yocounter" min="2" value="<?= $params['yocounter'] ?? '' ?>"></svelte-counter>
         <br />
         <input type="submit" />
      </form>
      <p><?= var_export($params, true) ?></p>
      <p><a href="/">back</a></p>
      <script src="js/fonts.js" defer></script>
      <script src="js/counter.js" defer></script>
      <script src="js/loger.js" defer></script>
   </body>
</html>