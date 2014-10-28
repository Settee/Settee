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

	public function fullDate($d){
		$date = explode(' ',$d);
		$day = explode('-', $date[0]);
		$hour = explode(':', $date[1]);

		$month = ($day[1] < 10)? trim($day[1],0) : $day[1];
		return $day[2].' '.PagesController::$month[$month].' '.$day[0].' '.$hour[0].'h '.$hour[1]; 
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

	public function getSideNavBar(){
		$in_home = (Dispatcher::whaturl() == 'index')? 'class="actived"' : '';
		$in_login = (Dispatcher::whaturl() == 'login')? 'class="actived"' : '';
		$in_register = (Dispatcher::whaturl() == 'register')? 'class="actived"' : '';
		$in_settings = (Dispatcher::whaturl() == 'settings')? 'class="actived"' : '';
		$in_post = (Dispatcher::whaturl() == 'profile/'.$this->getInfo('name'))? 'class="actived"' : '';

		$html = '<li><a href="'.Dispatcher::base().'" title="Home" '.$in_home.'><i class="fa fa-home"></i><span>Home</span></a></li>';

		if(!$this->auth->isLoged()){
			$html .= '<li><a href="'.Dispatcher::base().'register" title="Register" '.$in_register.'><i class="fa fa-pencil"></i><span>Register</span></a></li>';
			$html .= '<li><a href="'.Dispatcher::base().'login" title="Login" '.$in_login.'><i class="fa fa-sign-in"></i><span>Login</span></a></li>';
		}else{
			$html .= '<li><a href="'.Dispatcher::base().'profile/'.$this->getInfo('name').'" title="Profil" '.$in_post.'><i class="fa fa-user"></i><span>My posts</span></a></li>';
			$html .= '<li><a href="'.Dispatcher::base().'settings" title="Settings" '.$in_settings.'><i class="fa fa-cog"></i><span>Settings</span></a></li>';
			$html .= '<li><a href="'.Dispatcher::base().'logout" title="Logout"><i class="fa fa-sign-out"></i><span>Logout</span></a></li>';
		}

		return $html;
	}

}