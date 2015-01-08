<?php
if(!file_exists('config.php')){
	header('Location: install/');
}

define('ROOT', dirname(__FILE__));
define('DS', DIRECTORY_SEPARATOR);

session_start();
date_default_timezone_set('Europe/Paris');
header('Content-type: text/html; charset=utf-8');


spl_autoload_register(function($class_name) {
    if(file_exists(ROOT.DS.'controller/'.$class_name . '.php')){
        require_once 'controller/'.$class_name . '.php';
    }elseif(file_exists(ROOT.DS.'lib/'.strtolower($class_name) . '.php')){
        require_once 'lib/'.strtolower($class_name) . '.php';
    }elseif(file_exists(ROOT.DS.'static/language/'.strtolower($class_name) . '.php')){
        require_once 'static/language/'.strtolower($class_name) . '.php';
    }
});

require_once 'vendor/autoload.php';
require_once 'config.php';

$dispatcher = new Dispatcher;
?>