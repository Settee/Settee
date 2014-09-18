<?php Class PagesController extends AuthController{

	static $month = array('','Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre');
	private $database,$auth;

	function __construct(){
		$this->database = new Database;
		$this->auth = new AuthController;
	}

	public function setNotification($msg,$type){
		$_SESSION['e_out'] = '<div class="m-'.$type.'"><span>'.$msg.'</span><div class=".clearfloat"></div></div>';
	}

	public function getNotification(){
		if(isset($_SESSION['e_out']) && !empty($_SESSION['e_out'])){
			$notif = $_SESSION['e_out'];
			unset($_SESSION['e_out']);
		}else{
			$notif = '';
		}
		return $notif;
	}

	public function fullDate($date){
		$d = explode('-', $date);
		return $d[2].' '.PagesController::$month[trim($d[1],'0')].' '.$d[0]; 
	}

	public function getStyleDirectory($dir){
		if(isset($dir) && !empty($dir)){
			$dir .= "/";
		} 
		return Dispatcher::base()."static/".$dir;
	}

	public function getAvatar($id){
		if(is_numeric($id)){
			$avatar = current($this->database->sqlquery('SELECT avatar FROM '.CONFIG::PREFIX.'_users WHERE id="'.$this->database->secure($id).'"','query'));
			$return = Dispatcher::base()."static/images/settee.png";

			if(!empty($avatar)){
				if($avatar->avatar != null){
					if(file_exists(ROOT.DS.$avatar->avatar)){
						$return = Dispatcher::base().$avatar->avatar;
					}
				}
			}
			return $return;
		}
	}

	public function getInfo($opt){
		if(isset($_SESSION["user_id"]) && !empty($_SESSION["user_id"])){
			return current($this->database->sqlquery('SELECT * FROM '.CONFIG::PREFIX.'_users WHERE id='.$this->database->secure($_SESSION["user_id"]),'query'))->$opt;
		}
	}

	public function getUserInfo($id){
		if(is_numeric($id)){
			return current($this->database->sqlquery('SELECT * FROM '.CONFIG::PREFIX.'_users WHERE id="'.$this->database->secure($id).'"','query'));
		}else{
			return current($this->database->sqlquery('SELECT * FROM '.CONFIG::PREFIX.'_users WHERE name="'.$this->database->secure($id).'"','query'));
		}
	}

	public function getHeaderNavBar(){
		$headernavonline = '<nav><ul><li><a href="'.Dispatcher::base().'profile/'.$this->getInfo('name').'" title="Profil" class="avatar"><img src="'.$this->getAvatar($this->getInfo("id")).'" alt="Profil" /><span>Profil</span></a></li><li><a href="'.Dispatcher::base().'settings" title="Settings" class="settings"><img src="'.Dispatcher::base().'static/images/ico-settings.svg" alt="Settings" /><span>Settings</span></a></li></ul></nav>';
		$headernavsignin = '<nav><ul id="connections"><li><a href="'.Dispatcher::base().'" title="">Home</a></li><li><a href="'.Dispatcher::base().'register" title="">Register</a></li></ul></nav>';
		$headernavsignup = '<nav><ul id="connections"><li><a href="'.Dispatcher::base().'" title="">Home</a></li><li><a href="'.Dispatcher::base().'login" title="">Login</a></li></ul></nav>';
		$headernavoffline = '<nav><ul id="connections"><li><a href="'.Dispatcher::base().'register" title="">Register</a></li><li><a href="'.Dispatcher::base().'login" title="">Login</a></li></ul></nav>';
		$headernavsettings = '<nav><ul><li><a href="'.Dispatcher::base().'profile/'.$this->getInfo('name').'" title="Profil" class="avatar"><img src="'.$this->getAvatar($this->getInfo("id")).'" alt="Profil"></a></li></ul><ul id="connections"><li><a href="'.Dispatcher::base().'logout" title="">Logout</a></li></ul></nav>';

		if(Dispatcher::whaturl() == "register"){
			echo $headernavsignup;
		}elseif(Dispatcher::whaturl() == "login"){
			echo $headernavsignin;
		}elseif(Dispatcher::whaturl() == "settings" && $this->auth->isLoged() == true){
			echo $headernavsettings;
		}elseif($this->auth->isLoged() == true){
			echo $headernavonline;
		}else{
			echo $headernavoffline;
		}
	}

}