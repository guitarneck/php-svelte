<?php

interface iDataContainer
{
   function content () : string;
   function contentType () : string;
   function contentLength () : int;
}

class WWW
{
   const FORM_DATA       = 'multipart/form-data';
   const FORM_URLENCODED = 'application/x-www-form-urlencoded';
   const HTML            = 'text/html';
   const JSON            = 'application/json';
   const TEXT            = 'text/plain';
   const XML             = 'text/xml';

   protected   $handle;

   protected   $host,
               $port,
               $root;

   function __construct ( $root=null, $host=null, $port=null )
   {
      $this->root = ( null === $root ) ? __DIR__ : $root;
      $this->host = ( null === $host ) ? 'localhost' : $host;
      $this->port = ( null === $port ) ? $this->getAvailablePort() : $port;

      $nul = PHP_OS !== 'WINNT' ? '/dev/null &' : 'nul';
      $bck = PHP_OS !== 'WINNT' ? '' : 'start /wait /B ';

      $opt = array();
      if ( extension_loaded('Zend OPcache') ) array_push($opt, 'opcache.enable=0');
      $opt = empty($opt) ? '' : ' -d ' . implode(' -d ', $opt);

      $this->handle = proc_open("{$bck}php -S {$this->host}:{$this->port} -t {$this->root} {$opt} 2>$nul", array(), $pipes);
   }

   function __destruct()
   {
      $this->close();
   }

   static
   function createDataContainer ( $content, $mimetype='application/x-www-form-urlencoded', $encoding='' ) : iDataContainer
   {
      return new Class($content, $mimetype, $encoding) implements iDataContainer
      {
         protected $content, $mimetype, $encoding;

         function __construct ( $content, $mimtype, $encoding )
         {
            $this->content  = $content;
            $this->mimetype = $mimtype;
            $this->encoding = $encoding;
         }

         function content () : string
         {
            return $this->content;
         }

         function contentType () : string
         {
            $encoding = empty($this->encoding) ? '' : ";encoding: {$this->encoding}";
            return "Content-Type: {$this->mimetype}$encoding";
         }

         function contentLength () : int
         {
            return 'Content-Length: ' . strlen($this->content);
         }
      };
   }

   /**
    * Access to a page, and get its result.
    *
    * @param string        $page          The page to get.
    * @param array         $parameters    The query parameters.
    * @param string|array  $headers       Optional HTTP header.
    */
   function get ( string $page, array $parameters=array(), $headers=null ) : array
   {
      $parms   = $this->queryParameters($parameters);
      $context = null;
      if ( !empty($headers) )
      {
         $context = array(
            'http' => array(
              'method' => 'GET',
              'header' => is_array($headers) ? implode("\r\n", $headers) : $headers
            )
          );
          $context = stream_context_create($context);
      }
      ob_start();
      echo file_get_contents("http://{$this->host}:{$this->port}/$page$parms", false, $context);
      return array(ob_get_clean(), $http_response_header);
   }

   /**
    * Access to a page in post method, get its result.
    *
    * @param string           $page          The page to get.
    * @param array            $parameters    The query parameters.
    * @param iDataContainer   $datas         A data container.
    * @param string|array     $headers       Optional HTTP header.
    */
   function post ( string $page, array $parameters=array(), iDataContainer $datas, $headers='' ) : array
   {
      $parms   = $this->queryParameters($parameters);
      $headers = (is_array($headers) ? implode("\r\n", $headers) : $headers)
               . implode("\r\n", array('',$datas->contentType(),$datas->contentLength()));
      $context = array(
         'http' => array(
            'method' => 'POST',
            'header' => $headers,
            'content'=> $datas->content()
         )
      );
      $context = stream_context_create($context);
      ob_start();
      echo file_get_contents("http://{$this->host}:{$this->port}/$page$parms", false, $context);
      return array(ob_get_clean(), $http_response_header);
   }

   private
   function queryParameters ( array $parameters ) : string
   {
      return count($parameters) > 0 ? '?' . implode('&',$parameters) : '';
   }

   private
   function getAvailablePort ()
   {
      $socket = \socket_create_listen(0);
      \socket_getsockname($socket, $address, $port);
      \socket_close($socket);
      return $port;
   }

   private
   function kill ( $pid )
   {
      // tasklist
      ob_start();
      if ( PHP_OS === 'WINNT' )
         system("taskkill /PID $pid /T /F");
      else
         system("kill -9 $pid");
      ob_end_clean();
   }

   public
   function __toString()
   {
      return "Development Server (http://{$this->host}:{$this->port}) running";
   }

   function close ()
   {
      if ($this->handle)
      {
         $status = proc_get_status($this->handle);
         $this->kill($status['pid']);
         proc_terminate($this->handle);
         proc_close($this->handle);
      }
      $this->handle = false;
   }
}