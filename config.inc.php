<?php
// Set configuration parameters

// General Information
define('DS', '/');
define('CHARSET', 'utf-8');
define('LANG', 'en');
define('ROOT', realpath(dirname(__FILE__)) . DS);
define('LOGS_PATH', ROOT . 'logs' . DS . 'php.log');
define('HOMEPAGE', 'http://localhost/psnutrition/');
define('DISTRIBUTORS', 'http://localhost/psnutrition/distributors.php');
define('ADMINISTRATION', 'http://localhost/psnutrition/administration/');

// Database Information
define('DB_HOST','localhost');
define('DB_USER','psnutrition');
define('DB_PASSWORD','psnutrition');
define('DB_NAME','psnutrition');
