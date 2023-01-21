<?php

namespace PHPSvelte\php;

class Session
{
   static
   function isAvailable ()
   {
      return extension_loaded('session');
   }

   /*
   Limiter:

      nocache
         Expires: Thu, 19 Nov 1981 08:52:00 GMT
         Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0
         Pragma: no-cache

      private_no_expire
         Cache-Control: private, max-age=(session.cache_expire in the future), pre-check=(session.cache_expire in the future)
         Last-Modified: (the timestamp of when the session was last saved)

      private
         Expires: Thu, 19 Nov 1981 08:52:00 GMT
         Cache-Control: private, max-age=(session.cache_expire in the future), pre-check=(session.cache_expire in the future)
         Last-Modified: (the timestamp of when the session was last saved)

      public
         Expires: (sometime in the future, according session.cache_expire)
         Cache-Control: public, max-age=(sometime in the future, according to session.cache_expire)
         Last-Modified: (the timestamp of when the session was last saved)

      disabled
         turn off automatic sending of cache headers entirely
   */
   const DISABLED          = '';
   const NOCACHE           = 'nocache';
   const PRIVATE           = 'private';
   const PRIVATE_NOEXPIRE  = 'private_no_expire';
   const PUBLIC            = 'public';

   static
   function expire ( int $minutes=null )
   {
      return session_cache_expire($minutes ?? null);
   }

   /**
    * @see DISABLED, NOCACHE, PRIVATE, PRIVATE_NOEXPIRE, PUBLIC
    */
   static
   function limiter ( $limit=null )
   {
      return session_cache_limiter($limit ?? null);
   }

   static
   function isEnabled ()
   {
      if ( php_sapi_name() !== 'cli' || ! empty($_SERVER['DOCUMENT_ROOT']) )
      {
         return version_compare(phpversion(), '5.4.0', '>=') ? session_status() === PHP_SESSION_ACTIVE : session_id() === '';
      }
      return false;
   }

   /**
    * @param string $name
    * @return bool
    */
   static
   function start ( $name = null )
   {
      $enabled = Session::isEnabled();
      if ( ! $enabled )
      {
         Session::name($name);
         $enabled = session_start();
      }
      return $enabled;
   }

   /**
    * @param string|null $name   The name to set.
    * @return string             The name of the session.
    */
   static
   function name ( $name = null )
   {
      return $name ? session_name($name) : session_name();
   }

   static
   function abort ()
   {
      return session_abort();
   }

   static
   function commit ()
   {
      return session_commit();
   }

   static
   function reset ()
   {
      return session_reset();
   }

   static
   function clear ( $name = null )
   {
      if ( $name === null )
      {
         session_unset();
         $_SESSION = array();
      }
      else
         unset($_SESSION[$name]);
   }

   static
   function destroy ()
   {
      if ( ini_get("session.use_cookies") )
      {
         /*
            "lifetime" : durée de vie du cookie.
            "path" : le chemin où les informations sont stockées.
            "domain" : le domaine du cookie.
            "secure" : le cookie ne doit être envoyé que sur des connexions sécurisées.
            "httponly" : le cookie ne sera accessible que via le protocole HTTP.
            "samesite" : Contrôle l'envoie entre domaine (cross-domain) du cookie.
         */
         $params = session_get_cookie_params();
         $params["lifetime"] = time() - 42000;
         setcookie(Session::name(), '', $params["lifetime"], $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
      }
      session_destroy();
   }

   static
   function get ( $name )
   {
      return $_SESSION[$name] ?? null;
   }

   static
   function set ($name, $value)
   {
      return $_SESSION[$name] = $value;
   }

   static
   function has ( $name )
   {
      return array_key_exists($name, $_SESSION);
   }

   static
   function setid ( $id )
   {
      $active = Session::isEnabled();

      if ( $active ) Session::commit();

      ini_set('session.use_strict_mode', 0);
      $id = session_id($id);
      ini_restore('session.use_strict_mode');

      if ( $active ) Session::start(session_name());

      return $id;
   }

   static
   function getid ()
   {
      return session_id();
   }

   static
   function newid ( $prefix = '' )
   {
      return session_create_id($prefix);
   }

   /**
    * Set/get the save path where the session are stored.
    *
    * @param string $path  The path where session are stored.
    * @return string       The path where session are stored, or false when it failed.
    */
   static
   function savePath ( $path=null )
   {
      return session_save_path($path ?? null);
   }

   /**
    * Set the probability to launch the garbage collector at each start.
    * gcProbability(1,100) will give, 1/100 = 1% of probability.
    *
    * @param int $prob  The probability.
    * @param int $div   The probability divisor.
    */
   static
   function gcProbability ( int $prob=1, int $div=100 )
   {
      ini_set('session.gc_probability', $prob);
      ini_set('session.gc_divisor', $div);
   }

   /**
    * Set the lifetime of a session.
    *
    * @param int $minutes  The lifetime in minutes.
    */
   static
   function gcLifetime ( int $minutes )
   {
      ini_set('session.gc_maxlifetime', $minutes);
   }

   /**
    * Run the garbage collector.
    */
   static
   function gc ()
   {
      return session_gc();
   }

   static
   function useCookie ( bool $useCookie )
   {
      ini_set('session.use_cookies', $useCookie);
   }

   /**
    * Set the lifetime of cookie session.
    * The value 0 means "until the browser is closed.
    *
    * @param int $minutes  The lifetime in minutes.
    */
   static
   function cookieLifetime ( int $minutes )
   {
      ini_set('session.cookie_lifetime', $minutes);
   }

   /**
    * Set the http only of cookie session.
    *
    * @param bool $httpOnly  The lifetime in minutes.
    */
   static
   function cookieHttpOnly ( bool $httpOnly )
   {
      ini_set('session.cookie_httponly', $httpOnly);
   }

   static
   function cookiePath ( string $path )
   {
      ini_set('session.cookie_path', $path);
   }

   static
   function cookieDomain ( string $domain )
   {
      ini_set('session.cookie_domain', $domain);
   }

   static
   function cookieSecure ( bool $secure )
   {
      ini_set('session.cookie_secure', $secure);
   }

   const LAX = 'lax';
   const STRICT = 'strict';

   /**
    * Note that this is not supported by all browsers.
    * An empty value means that no SameSite cookie attribute will be set.
    * Lax and Strict mean that the cookie will not be sent cross-domain for POST requests;
    * Lax will sent the cookie for cross-domain GET requests, while Strict will not.
    */
   static
   function cookieSameSite ( string $sameSite='' )
   {
      ini_set('session.cookie_samesite', $sameSite);
   }
}