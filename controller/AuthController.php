<?php Class AuthController extends Controller{

	private $database,$notif;

	function __construct(){
		$this->notif = Controller::loading_controller('NotificationController');
		$this->database = Controller::loading_controller('Database');
	}

	// Check if user id logged
	public function isLoged(){
		return (isset($_SESSION['__settee_key__']) && !empty($_SESSION['__settee_key__']));
	}

	// Login function
	public function login(){
		if(isset($_POST['login']) && isset($_POST['passwd'])){
			$result = current($this->database->sqlquery('SELECT * FROM '.CONFIG::PREFIX.'_users WHERE name="'.$this->database->secure($_POST["login"]).'" AND type IS NOT NULL','query'));
			if($result){
				if($_POST['login'] == $result->name && crypt($_POST['passwd'] . Config::KEY, $result->password) == $result->password){
					$date = new DateTime(); $time = $date->getTimestamp();
					$_SESSION['__settee_key__'] = $time;
					$_SESSION['user_id'] = $result->id;
					header('Location: '.Dispatcher::base());
				}else{
					$this->notif->setNotification('Wrong username or password','error');
				}
			}else{
				$this->notif->setNotification('Wrong username or unactived account','info');
			}
		}
		return 'login';
	}

	// Register function
	public function register(){
		if(isset($_POST['login']) && isset($_POST['passwd']) && isset($_POST['email'])){
			if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
				if(strlen($_POST['passwd']) >= '6'){
					$nick = trim($_POST['login']);
					$pass = crypt($_POST['passwd'] . Config::KEY);
					$email = trim($_POST['email']);
					$test = $this->database->sqlquery('SELECT * FROM '.CONFIG::PREFIX.'_users WHERE name="'.$this->database->secure($nick).'" OR email="'.$this->database->secure($email).'"','query');
					
					if(empty($test)){
						if(Controller::privacy() == '0' || Controller::privacy() == '1'){
							$this->database->sqlquery('INSERT INTO '.CONFIG::PREFIX.'_users (name,surname,password,email,type) VALUES("'.$this->database->secure($nick).'","'.$this->database->secure($nick).'","'.$this->database->secure($pass).'","'.$this->database->secure($email).'","")');
							$result = current($this->database->sqlquery('SELECT * FROM '.CONFIG::PREFIX.'_users WHERE name="'.$this->database->secure($nick).'"','query'));
							mail($email,CONFIG::WEBSITE.' Registration confirmation', 'Hey, '.$nick.' follow this link to confirm your email. url: '.$_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].substr($_SERVER['SCRIPT_NAME'],0,-9).'confirmation_email/'.$email.'/'.sha1('confirmation_email-'.$email),'From: contact@netart.fr.nf' . "\r\n" . 'Reply-To: contact@netart.fr.nf' . "\r\n");
							header('Location: '.Dispatcher::base());
						}elseif(Controller::privacy() == '2'){
							$root = $this->database->sqlquery('SELECT email FROM '.CONFIG::PREFIX.'_users WHERE type="root"','query');
							$this->database->sqlquery('INSERT INTO '.CONFIG::PREFIX.'_users (name,surname,password,email) VALUES("'.$this->database->secure($nick).'","'.$this->database->secure($nick).'","'.$this->database->secure($pass).'","'.$this->database->secure($email).'")');
							foreach ($root as $k => $v) {
								mail($v->email,CONFIG::WEBSITE.' Registration ask', 'The user '.$nick.' would like going into your settee app','From: contact@netart.fr.nf' . "\r\n" . 'Reply-To: contact@netart.fr.nf' . "\r\n");
							}
							$this->notif->setNotification('Your account is awaiting of an administrator validation','info');
						}
					}else{
						$this->notif->setNotification('User already exist','error');
					}
				}else{
					$this->notif->setNotification('Password is too short','error');
				}
			}else{
				$this->notif->setNotification('Wrong email','error');
			}
		}
		return 'register';
	}
}