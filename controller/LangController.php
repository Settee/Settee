<?php Class LangController extends Controller{

	private $lang,$user;

	public function __construct(){
		$this->user =Controller::loading_controller('UserController');
		if($this->user->getActiveUser('lang') != '' && in_array($this->user->getActiveUser('lang'), $this->getListLanguage())){
			$langController = ucfirst($this->user->getActiveUser('lang'));
			$this->lang = new $langController;
		}else{
			$this->lang = new English;
		}
	}

	// list language file
	public function getListLanguage(){
		$tab = array();
		foreach(glob('static/language/*') as $k => $v){
			array_push($tab, basename($v,".php"));
		}
		return $tab;
	}

	// use variable for translation
	public function i18n($var){
		if(!property_exists($this->lang, $var)){
			$lang_bck = new English;
			return $lang_bck->$var;
		}else{
			return $this->lang->$var;
		}
	}

}