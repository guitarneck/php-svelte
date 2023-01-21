<?php

namespace PHPSvelte\http;

class HTTPHeader
{
   const A_IM = 'A-IM';
   const ACCEPT = 'Accept';
   const ACCEPT_CH = 'Accept-CH';
   const ACCEPT_CHARSET = 'Accept-Charset';
   const ACCEPT_DATETIME = 'Accept-Datetime';
   const ACCEPT_ENCODING = 'Accept-Encoding';
   const ACCEPT_LANGUAGE = 'Accept-Language';
   const ACCEPT_PATCH = 'Accept-Patch';
   const ACCEPT_RANGES = 'Accept-Ranges';
   const ACCESS_CONTROL_ALLOW_CREDENTIALS = 'Access-Control-Allow-Credentials';
   const ACCESS_CONTROL_ALLOW_HEADERS = 'Access-Control-Allow-Headers';
   const ACCESS_CONTROL_ALLOW_METHODS = 'Access-Control-Allow-Methods';
   const ACCESS_CONTROL_ALLOW_ORIGIN = 'Access-Control-Allow-Origin';
   const ACCESS_CONTROL_EXPOSE_HEADERS = 'Access-Control-Expose-Headers';
   const ACCESS_CONTROL_MAX_AGE = 'Access-Control-Max-Age';
   const ACCESS_CONTROL_REQUEST_HEADERS = 'Access-Control-Request-Headers';
   const ACCESS_CONTROL_REQUEST_METHOD = 'Access-Control-Request-Method';
   const AGE = 'Age';
   const ALLOW = 'Allow';
   const ALT_SVC = 'Alt-Svc';
   const AUTHORIZATION = 'Authorization';
   const CACHE_CONTROL = 'Cache-Control';
   const CONNECTION = 'Connection';
   const CONTENT_DISPOSITION = 'Content-Disposition';
   const CONTENT_ENCODING = 'Content-Encoding';
   const CONTENT_LANGUAGE = 'Content-Language';
   const CONTENT_LENGTH = 'Content-Length';
   const CONTENT_LOCATION = 'Content-Location';
   const CONTENT_MD5 = 'Content-MD5';
   const CONTENT_RANGE = 'Content-Range';
   const CONTENT_TYPE = 'Content-Type';
   const CONTENT_SECURITY_POLICY = 'Content-Security-Policy';
   const COOKIE = 'Cookie';
   const CORRELATION_ID = 'Correlation-ID';
   const DATE = 'Date';
   const DELTA_BASE = 'Delta-Base';
   const DNT = 'DNT';
   const ETAG = 'ETag';
   const EXPECT = 'Expect';
   const EXPECT_CT = 'Expect-CT';
   const EXPIRES = 'Expires';
   const FORWARDED = 'Forwarded';
   const FROM = 'From';
   const FRONT_END_HTTPS = 'Front-End-Https';
   const HOST = 'Host';
   const HTTP2_SETTINGS = 'HTTP2-Settings';
   const IF_MATCH = 'If-Match';
   const IF_MODIFIED_SINCE = 'If-Modified-Since';
   const IF_NONE_MATCH = 'If-None-Match';
   const IF_RANGE = 'If-Range';
   const IF_UNMODIFIED_SINCE = 'If-Unmodified-Since';
   const IM = 'IM';
   const LAST_MODIFIED = 'Last-Modified';
   const LINK = 'Link';
   const LOCATION = 'Location';
   const MAX_FORWARDS = 'Max-Forwards';
   const NEL = 'NEL';
   const ORIGIN = 'Origin';
   const P3P = 'P3P';
   const PERMISSIONS_POLICY = 'Permissions-Policy';
   const PRAGMA = 'Pragma';
   const PREFER = 'Prefer';
   const PREFERENCE_APPLIED = 'Preference-Applied';
   const PROXY_AUTHENTICATE = 'Proxy-Authenticate';
   const PROXY_AUTHORIZATION = 'Proxy-Authorization';
   const PROXY_CONNECTION = 'Proxy-Connection';
   const PUBLIC_KEY_PINS = 'Public-Key-Pins';
   const RANGE = ' Range';
   const REFERER = 'Referer';
   const REFRESH = 'Refresh';
   const REPORT_TO = 'Report-To';
   const RETRY_AFTER = 'Retry-After';
   const SAVE_DATA = 'Save-Data';
   const SERVER = 'Server';
   const SET_COOKIE = 'Set-Cookie';
   const STATUS = 'Status';
   const STRICT_TRANSPORT_SECURITY = 'Strict-Transport-Security';
   const TE = 'TE';
   const TIMING_ALLOW_ORIGIN = 'Timing-Allow-Origin';
   const TK = 'Tk';
   const TRAILER = 'Trailer';
   const TRANSFER_ENCODING = 'Transfer-Encoding';
   const UPGRADE = 'Upgrade';
   const UPGRADE_INSECURE_REQUESTS = 'Upgrade-Insecure-Requests';
   const USER_AGENT = 'User-Agent';
   const VARY = 'Vary';
   const VIA = 'Via';
   const WARNING = 'Warning';
   const WWW_AUTHENTICATE = 'WWW-Authenticate';
   const X_ATT_DEVICEID = 'X-ATT-DeviceId';
   const X_CONTENT_DURATION = 'X-Content-Duration';
   const X_CONTENT_SECURITY_POLICY = 'X-Content-Security-Policy';
   const X_CONTENT_TYPE_OPTIONS = 'X-Content-Type-Options';
   const X_CORRELATION_ID = 'X-Correlation-ID';
   const X_CSRF_TOKEN = 'X-Csrf-Token';
   const X_CSRFTOKEN = 'X-CSRFToken';
   const X_FORWARDED_FOR = 'X-Forwarded-For';
   const X_FORWARDED_HOST = 'X-Forwarded-Host';
   const X_FORWARDED_PROTO = 'X-Forwarded-Proto';
   const X_FRAME_OPTIONS = 'X-Frame-Options';
   const X_HTTP_METHOD_OVERRIDE = 'X-Http-Method-Override';
   const X_POWERED_BY = 'X-Powered-By';
   const X_REDIRECT_BY = 'X-Redirect-By';
   const X_REQUEST_ID = 'X-Request-ID';
   const X_REQUESTED_WITH = 'X-Requested-With';
   const X_UA_COMPATIBLE = 'X-UA-Compatible';
   const X_UIDH = 'X-UIDH';
   const X_WAP_PROFILE = 'X-Wap-Profile';
   const X_XSRF_TOKEN = 'X-XSRF-TOKEN';
   const X_XSS_PROTECTION = 'X-XSS-Protection';
   const X_WEBKIT_CSP = 'X-WebKit-CSP';

   private $content = array();

   function __construct ( $header=null )
   {
      if ( $header !== null )
         if ( is_array($header) )
            $this->content = array_merge(array(),$header);
         else
            $this->content = explode("\r\n", $header);
   }

   function __toString ()
   {
      return implode("\r\n", $this->content);
   }

   function add ( $headerName, $headerValue )
   {
      array_push($this->content, sprintf('%s: %s', $headerName, $headerValue));
   }

   function clear ()
   {
      $this->content = array();
   }

   function contains ( $headerName )
   {
      return count($this->get($headerName)) > 0;
   }

   function get ( $headerName=null )
   {
      if ( $headerName === null )
         return $this->content;
      else
         return array_filter($this->content, function ($h) use ($headerName) { return strpos($h, $headerName) === 0; });
   }

   function remove ( $headerName )
   {
      $this->content = array_filter($this->content, function ($h) use ($headerName) { return strpos($h, $headerName) === 0 ? false : true; });
   }

   function size ()
   {
      return count($this->content);
   }
}