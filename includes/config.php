<?php
session_start();
ob_start();

// DATABASE
/* Set this up using your own db provider */
define('DBHOST', '');
define('DBUSER', '');
define('DBPASS', '');
define('DBNAME', '');

//set timezone
date_default_timezone_set('Europe/Prague');

//load classes automatically
spl_autoload_register(function ($class) {
   $class = strtolower($class);
   $classpath = 'classes/class.' . $class . '.php';
   if (file_exists($classpath)) {
      require_once $classpath;
   }
   $class = strtolower($class);
   $classpath = '../classes/class.' . $class . '.php';
   if (file_exists($classpath)) {
      require_once $classpath;
   }
   $class = strtolower($class);
   $classpath = '../../classes/class.' . $class . '.php';
   if (file_exists($classpath)) {
      require_once $classpath;
   }

   $class = strtolower($class);
   $classpath = '../../../classes/class.' . $class . '.php';
   if (file_exists($classpath)) {
      require_once $classpath;
   }
});

// new database connection && user
$db = new Db(DBHOST, DBUSER, DBPASS, DBNAME);
$user = new User($db);

// global variables
$WEB_ROOT = '/oneplusnoone/'; # change to / for hosting, /oneplusnoone/ for localhost
$FS_ROOT = realpath(__DIR__ . '/../') . '/';
$UPLOADS_URL = 'images/';
