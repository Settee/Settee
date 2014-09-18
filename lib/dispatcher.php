<?php Class Dispatcher extends Template{
	
	public $database,$auth,$pages,$posts;
	
	function __construct(){
		$this->database = new Database;
		$this->auth = new AuthController;
		$this->pages = new PagesController;
		$this->posts = new PostsController;
		Dispatcher::render();
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

	function render(){
		$page = explode('/',Dispatcher::whaturl());
		$url = array_slice($page,0);
		Template::load($url[0]);
	}

	static function base(){
		return substr($_SERVER['SCRIPT_NAME'],0,-9);
	}
}
?>