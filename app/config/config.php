<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// DB Params
define("DB_HOST", "localhost");
define("DB_USER", "user_access");
define("DB_PASS", "Ionut.3feb");
define("DB_NAME", "user-access");

// App Root
define('APPROOT', dirname(dirname(__FILE__)));
// URL Root (eg. http://myapp.com or http://localhost/myapp)
define('URLROOT', 'http://localhost/user-access');
// Site Name
define('SITENAME', 'User Access APP with PHP');
  