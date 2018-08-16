<?php
define('SITE_ROOT', "../");
define('WWW_ROOT', SITE_ROOT . '/public');

/* DB config 
 * Старые конфиги - оставлены для совместимости
 * */
define('HOST', 'localhost');
define('USER', 'gb');
define('PASS', 'gb2018');
define('DB', 'test5');

/* Database connectivity setup */
define('DB_PERSISTENCY', 'true');
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'gb');
define('DB_PASSWORD', 'gb2018');
define('DB_DATABASE', 'test5');
define('PDO_DSN', 'mysql:host=' . DB_SERVER . ';dbname=' . DB_DATABASE);

define('DATA_DIR', SITE_ROOT . 'data');
define('LIB_DIR', SITE_ROOT . 'engine');
define('TPL_DIR', SITE_ROOT . 'templates');

define('PRESENTATION_DIR', SITE_ROOT . 'presentation');


define('USE_SSL', 'no');
define('HTTP_SERVER_PORT', '80');
/* Name of the virtual directory the site runs in, for example:
 '/tshirtshop/' if the site runs at http://www.example.com/tshirtshop/
 '/' if the site runs at http://www.example.com/ */
define('VIRTUAL_LOCATION', '/');

//подгружаем основные функции
require_once(LIB_DIR . '/functions.php');
require_once(LIB_DIR . '/db.php');
require_once(LIB_DIR . '/template.php');
require_once(PRESENTATION_DIR. '/page.php');
require_once(PRESENTATION_DIR. '/link.php');
/*
require_once(PRESENTATION_DIR. '/link.php');
require_once(PRESENTATION_DIR. '/page_controller.php');
*/