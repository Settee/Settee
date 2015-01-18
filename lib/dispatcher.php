<?php Class Dispatcher extends Template{
	
	public $database,$auth,$pages,$posts,$general,$user,$admin;
	
	public function __construct(){
		$this->notif = Controller::loading_controller('NotificationController');
		$this->general = Controller::loading_controller('GeneralController');
		$this->admin = Controller::loading_controller('AdminController');
		$this->pages = Controller::loading_controller('PagesController');
		$this->posts = Controller::loading_controller('PostsController');
		$this->auth = Controller::loading_controller('AuthController');
		$this->lang = Controller::loading_controller('LangController');
		$this->user = Controller::loading_controller('UserController');
		$this->database = Controller::loading_controller('Database');
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