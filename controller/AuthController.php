<?php Class AuthController{

	private $database;

	function __construct(){
		$this->database = new Database;
	}

	public function isLoged(){
		return (isset($_SESSION['__key__']) && !empty($_SESSION['__key__']));
	}

	public function isValid(){
		if(isset($_SESSION['__key__']) && !empty($_SESSION['__key__'])){
			$date = new DateTime(); $time = $date->getTimestamp();
			if($time - (substr($_SESSION['__key__'],-10)) >= '18000'){
				echo 'session expiré';
				//Controller::signin($res);
			}
		}else{
			echo 'identifie toi !';
			$this->login();
		}
	}

	public function login(){
		if(isset($_POST['login']) && isset($_POST['passwd'])){
			$result = current($this->database->sqlquery('SELECT * FROM '.CONFIG::PREFIX.'_users WHERE name="'.$this->database->secure($_POST["login"]).'" AND type IS NOT NULL','query'));
			if($result){
				if($_POST['login'] == $result->name && crypt($_POST['passwd'] . Config::KEY, $result->password) == $result->password){
					$date = new DateTime(); $time = $date->getTimestamp();
					$_SESSION['__key__'] = $time;
					$_SESSION['user_id'] = $result->id;
					header('Location: '.Dispatcher::base());
				}else{
					$_SESSION['e_out'] = '<div class="m-error"><span>Wrong username or password</span><div class=".clearfloat"></div></div>';
				}
			}else{
				$_SESSION['e_out'] = '<div class="m-info"><span>Wrong username or unactived account</span><div class=".clearfloat"></div></div>';
			}
		}
		return 'login';
	}

	public function register(){
		if(isset($_POST['login']) && isset($_POST['passwd']) && isset($_POST['email'])){
			if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
				if(strlen($_POST['passwd']) >= '7' && preg_match('/^[a-zA-Z0-9\@_-]{6,}$/', $_POST['passwd'])){
					$nick = trim($_POST['login']);
					$pass = crypt($_POST['passwd'] . Config::KEY);
					$email = trim($_POST['email']);
					$test = $this->database->sqlquery('SELECT * FROM '.CONFIG::PREFIX.'_users WHERE name="'.$this->database->secure($nick).'" OR email="'.$this->database->secure($email).'"','query');
					
					if(empty($test)){
						if(Controller::privacy() == '0' || Controller::privacy() == '1'){
							$this->database->sqlquery('INSERT INTO '.CONFIG::PREFIX.'_users (name,surname,password,email,type) VALUES("'.$this->database->secure($nick).'","'.$this->database->secure($nick).'","'.$this->database->secure($pass).'","'.$this->database->secure($email).'","user")');
							$result = current($this->database->sqlquery('SELECT * FROM '.CONFIG::PREFIX.'_users WHERE name="'.$this->database->secure($nick).'"','query'));
							$date = new DateTime(); $time = $date->getTimestamp();
							$_SESSION['__key__'] = $time;
							$_SESSION['user_id'] = $result->id;
							header('Location: '.Dispatcher::base());
						}elseif(Controller::privacy() == '2'){
							$root = $this->database->sqlquery('SELECT email FROM '.CONFIG::PREFIX.'_users WHERE type="root"','query');
							$this->database->sqlquery('INSERT INTO '.CONFIG::PREFIX.'_users (name,surname,password,email,avatar) VALUES("'.$this->database->secure($nick).'","'.$this->database->secure($nick).'","'.$this->database->secure($pass).'","'.$this->database->secure($email).'","template/images/settee.png")');
							foreach ($root as $k => $v) {
								mail($v->email,CONFIG::WEBSITE.' Registration ask', 'The user '.$nick.' would like going into your settee app','From: contact@netart.fr.nf' . "\r\n" . 'Reply-To: contact@netart.fr.nf' . "\r\n");
							}
							$_SESSION['e_out'] = '<div class="m-info"><span>Your account is awaiting of an administrator validation</span><div class=".clearfloat"></div></div>';
						}
					}else{
						$_SESSION['e_out'] = '<div class="m-error"><span>User already exist</span><div class=".clearfloat"></div></div>';
					}
				}else{
					$_SESSION['e_out'] = '<div class="m-error"><span>Password is too short</span><div class=".clearfloat"></div></div>';
				}
			}else{
				$_SESSION['e_out'] = '<div class="m-error"><span>Wrong email</span><div class=".clearfloat"></div></div>';
			}
		}
		return 'register';
	}
}