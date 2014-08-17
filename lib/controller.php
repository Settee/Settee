<?php Class Controller extends Database{


	function auth(){
		if(isset($_SESSION['__key__']) && !empty($_SESSION['__key__'])){
			$date = new DateTime(); $time = $date->getTimestamp();
			if($time - (substr($_SESSION['__key__'],-10)) <= '18000'){
				$res = 'vous êtes logé';
				Template::theme('index',$res);
			}else{
				$res = 'session expiré';
				Controller::signin($res);
			}
		}else{
			$res = 'identifie toi !';
			Controller::signin($res);
		}
	}

	static function isloged(){
		if(isset($_SESSION['__key__']) && !empty($_SESSION['__key__'])){
			$res = true;
		}else{
			$res = false;
		}
		return $res;
	}

	static function privacy(){
		$return = '-1';
		if(Config::PRIVACY == 'public'){
			$return = '0';
		}
		if(Config::PRIVACY == 'publicregistration'){
			$return = '1';
		}
		if(Config::PRIVACY == 'privateadminvalitation'){
			$return = '2';
		}
		if(Config::PRIVACY == 'privatememberinvite'){
			$return = '3';
		}
		if(Config::PRIVACY == 'privateadmininvite'){
			$return = '4';
		}

		return $return;
	}

	function signin(){
		if(isset($_POST['login']) && isset($_POST['passwd'])){
			$database = new Database;
			$result = current($database->sqlquery('SELECT * FROM '.CONFIG::PREFIX.'_users WHERE name="'.$database->secure($_POST["login"]).'" AND type IS NOT NULL','query'));
			if($result){
				if($_POST['login'] === $result->name && crypt($_POST['passwd'] . Config::KEY, $result->password) === $result->password){
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
		Template::theme('login');
	}

	function signup(){
		if(isset($_POST['login']) && isset($_POST['passwd']) && isset($_POST['email'])){
			if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
				if(strlen($_POST['passwd']) >= '7' && preg_match('/^[a-zA-Z0-9\@_-]{6,}$/', $_POST['passwd'])){
					$nick = trim($_POST['login']);
					$pass = crypt($_POST['passwd'] . Config::KEY);
					$email = trim($_POST['email']);
					$database = new Database;
					$test = $database->sqlquery('SELECT * FROM '.CONFIG::PREFIX.'_users WHERE name="'.$database->secure($nick).'" OR email="'.$database->secure($email).'"','query');
					
					if(empty($test)){
						if(Controller::privacy() == '0' || Controller::privacy() == '1'){
							$database->sqlquery('INSERT INTO '.CONFIG::PREFIX.'_users (name,surname,password,email,type) VALUES("'.$database->secure($nick).'","'.$database->secure($nick).'","'.$database->secure($pass).'","'.$database->secure($email).'","user")');
							$result = current($database->sqlquery('SELECT * FROM '.CONFIG::PREFIX.'_users WHERE name="'.$database->secure($nick).'"','query'));
							$date = new DateTime(); $time = $date->getTimestamp();
							$_SESSION['__key__'] = $time;
							$_SESSION['user_id'] = $result->id;
							header('Location: '.Dispatcher::base());
						}elseif(Controller::privacy() == '2'){
							$root = $database->sqlquery('SELECT email FROM '.CONFIG::PREFIX.'_users WHERE type="root"','query');
							$database->sqlquery('INSERT INTO '.CONFIG::PREFIX.'_users (name,surname,password,email,avatar) VALUES("'.$database->secure($nick).'","'.$database->secure($nick).'","'.$database->secure($pass).'","'.$database->secure($email).'","/template/images/settee.png")');
							foreach ($root as $k => $v) {
								mail($v->email,CONFIG::WEBSITE.' Registration ask', 'The user '.$nick.' would like going into your settee app','From: contact@netart.fr.nf' . "\r\n" . 'Reply-To: contact@netart.fr.nf' . "\r\n");
							}
							$_SESSION['e_out'] = '<div class="m-info"><span>your account is awaiting of an administrator validation</span><div class=".clearfloat"></div></div>';
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
		Template::theme('register');
	}
}