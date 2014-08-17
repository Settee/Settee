<?php Class Dispatcher extends Template{

	function __construct(){
		Dispatcher::url();
	}

	static function whaturl(){
		$baseurl = substr($_SERVER['SCRIPT_NAME'],0,-9);
		if($_SERVER['REQUEST_URI'] == $baseurl){
			return 'index';
		}else{
			$basesuppr = strlen($baseurl);
			$url = substr($_SERVER['REQUEST_URI'], $basesuppr);
			return $url;
		}
	}

	function url(){
		$baseurl = substr($_SERVER['SCRIPT_NAME'],0,-9);
		if($_SERVER['REQUEST_URI'] == $baseurl){
			Dispatcher::render('index');
		}else{
			$basesuppr = strlen($baseurl);
			$url = substr($_SERVER['REQUEST_URI'], $basesuppr);
			Dispatcher::render($url);
		}
	}

	function render($page){
		$page = explode('/',$page);
		$url = array_slice($page,0);
		Template::load($url[0],$url);
	}

	static function base(){
		return substr($_SERVER['SCRIPT_NAME'],0,-9);
	}
}
?>