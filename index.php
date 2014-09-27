<?php
if(!file_exists('config.php')){
	header('Location: install/');
}

define('ROOT', dirname(__FILE__));
define('DS', DIRECTORY_SEPARATOR);

session_start();
date_default_timezone_set('Europe/Paris');
header('Content-type: text/html; charset=utf-8');

require_once 'lib/imagine/Exception/Exception.php';
require_once 'lib/imagine/Exception/RuntimeException.php';
require_once 'lib/imagine/Exception/InvalidArgumentException.php';
require_once 'lib/imagine/Image/ManipulatorInterface.php';
require_once 'lib/imagine/Image/ImageInterface.php';
require_once 'lib/imagine/Image/ImagineInterface.php';
require_once 'lib/imagine/Image/BoxInterface.php';
require_once 'lib/imagine/Image/Color.php';
require_once 'lib/imagine/Image/Box.php';
require_once 'lib/imagine/Image/PointInterface.php';
require_once 'lib/imagine/Image/Point.php';
require_once 'lib/imagine/Gd/Image.php';
require_once 'lib/imagine/Gd/Imagine.php';

require_once 'lib/controller.php';
require_once 'controller/AuthController.php';
require_once 'controller/PagesController.php';
require_once 'controller/PostsController.php';
require_once 'lib/database.php';
require_once 'lib/file.php';
require_once 'lib/template.php';
require_once 'lib/dispatcher.php';
require_once 'config.php';

$dispatcher = new Dispatcher;
?>