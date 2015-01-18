<?php Class UserController extends Controller{

	private $database;
	static $activeUser = array();

	public function __construct(){
		$this->database =Controller::loading_controller('Database');
	}

	public function getActiveUser($opt){
		if(isset(UserController::$activeUser['user'])){
			return UserController::$activeUser['user']->$opt;
		}
		if(isset($_SESSION["user_id"]) && !empty($_SESSION["user_id"])){
			$actUser = current($this->database->sqlquery('SELECT * FROM '.CONFIG::PREFIX.'_users WHERE id='.$this->database->secure($_SESSION["user_id"]),'query'));
			UserController::$activeUser['user'] = $actUser;
			return $actUser->$opt;
		}
	}

	public function getUserById($id){
		return current($this->database->sqlquery('SELECT * FROM '.CONFIG::PREFIX.'_users WHERE id="'.$this->database->secure($id).'"','query'));
	}

	public function getUserByName($name){
		return current($this->database->sqlquery('SELECT * FROM '.CONFIG::PREFIX.'_users WHERE name="'.$this->database->secure($name).'"','query'));
	}

	public function getUserAvatar($id){
		if(is_numeric($id)){
			$avatar = current($this->database->sqlquery('SELECT avatar FROM '.CONFIG::PREFIX.'_users WHERE id="'.$this->database->secure($id).'"','query'));
			$return = Dispatcher::base()."template/images/settee.png";

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

	public function getAllUsers(){
		return $this->database->sqlquery('SELECT surname, name FROM '.CONFIG::PREFIX.'_users','query');
	}

}