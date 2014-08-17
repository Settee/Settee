<?php
if(!file_exists('config.php')){
	header('Location: install/');
}

define('ROOT', dirname(__FILE__));
define('DS', DIRECTORY_SEPARATOR);

session_start();
date_default_timezone_set('Europe/Paris');
iconv_set_encoding("internal_encoding", "UTF-8");
header('Content-type: text/html; charset=utf-8');


require_once 'lib/database.php';
require_once 'lib/controller.php';
require_once 'lib/file.php';
require_once 'lib/template.php';
require_once 'lib/dispatcher.php';
require_once 'config.php';

$dispatcher = new Dispatcher;
?>