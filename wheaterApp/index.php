<?php

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);

// Require composer autoloader
require __DIR__ . '/vendor/autoload.php';

//define("DIR", __FILE__);

define("DIR", "");

date_default_timezone_set("Asia/Tehran");

$action = $_SERVER['REQUEST_URI'];

require_once 'src/Core/Static.php';
require_once 'src/Core/Loader.php';

