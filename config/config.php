<?php
define('SITE_ROOT', "../");
define('WWW_ROOT', SITE_ROOT . '/public');

/* DB config */
define('HOST', 'localhost');
define('USER', 'gb');
define('PASS', 'gb2018');
define('DB', 'test5');

define('DATA_DIR', SITE_ROOT . 'data');
define('LIB_DIR', SITE_ROOT . 'engine');
define('TPL_DIR', SITE_ROOT . 'templates');

define('PRESENTATION_DIR', SITE_ROOT . 'presentation');

define('SITE_TITLE', 'Урок 5');
//подгружаем основные функции
require_once(LIB_DIR . '/functions.php');
require_once(LIB_DIR . '/db.php');
require_once(LIB_DIR . '/template.php');
require_once(PRESENTATION_DIR. '/page.php');
/*
require_once(PRESENTATION_DIR. '/link.php');
require_once(PRESENTATION_DIR. '/page_controller.php');
*/