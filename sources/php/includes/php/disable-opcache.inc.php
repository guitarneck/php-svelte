<?php
// Disable opcache during tests
if ( extension_loaded('Zend OPcache') )
{
   ini_set('opcache.enable', false);
}