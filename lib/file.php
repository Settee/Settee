<?php Class File extends Controller{

	function index(){
		if($this->auth->isLoged() || Controller::privacy() == 0){
			Template::theme('index');
		}else{
			Template::theme($this->auth->login());
		}
	}

	function addpostform(){
		if($this->auth->isLoged()){
			if(Dispatcher::whaturl() == 'addpostform/html'){
				echo '<article><div id="newpost">'.$this->pages->getPostForm().'</div></article>';
			}else{
				Template::theme('add');
			}
		}else{
			Template::theme($this->auth->login());
		}
	}

	function notification(){
		if($this->auth->isLoged()){
			Template::theme('notification');
		}else{
			Template::theme($this->auth->login());
		}
	}

	function invite_email(){
		if($this->auth->isLoged() && (Controller::privacy() == 3 || $this->user->getActiveUser('type') == 'root')){
			if(isset($_POST['email']) && !empty($_POST['email']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
				if(mail($_POST['email'], $this->user->getActiveUser('surname').' Vous invite à vous inscrire sur settee', 'Votre ami vous propose de venir sur settee: http://'.$_SERVER['SERVER_NAME'].substr($_SERVER['SCRIPT_NAME'],0,-9))){
					$this->notif->setNotification('Email send','info');
				}else{
					$this->notif->setNotification('Error email','error');
				}
			}else{
				$this->notif->setNotification('Not an email','error');
			}
			header('Location: '.Dispatcher::base());
		}
	}

	function confirmation_email(){
		$param = explode('/', Dispatcher::whaturl());
		if(count($param) == 3){
			if(sha1('confirmation_email-'.$param[1]) == $param[2]){
				$this->database->sqlquery('UPDATE '.CONFIG::PREFIX.'_users SET type="user" WHERE email="'.$this->database->secure($param[1]).'"');
				$this->notif->setNotification('Account activated','info');
			}else{
				$this->notif->setNotification('Error, sorry','error');
			}
		}
		header('Location: '.Dispatcher::base());
	}

	function login(){
		if($this->auth->isLoged()){
			header('Location: '.Dispatcher::base());
		}else{
			Template::theme($this->auth->login());
		}
	}

	function category(){
		if($this->auth->isLoged() || Controller::privacy() == 0){
			Template::theme('categorie');
		}else{
			Template::theme($this->auth->login());
		}
	}

	function addpost(){
		if($this->auth->isLoged()){
			if(isset($_POST) && !empty($_POST)){
				$image_file = $_FILES['image'];
				$image = (isset($image_file) && !empty($image_file) && !empty($image_file['name']) && !empty($image_file['type']) && !empty($image_file['tmp_name']));
				if($image){
					if($image_file['error'] == '0'){
						$this->posts->addpost($this->user->getActiveUser('id'),$_POST['post'],$_POST['categories'],$image_file['name'],$image_file['tmp_name']);
						$this->notif->setNotification('Post Added','info');
					}else{
						$this->notif->setNotification('Upload Fail','error');
					}
				}else{
					$this->posts->addpost($this->user->getActiveUser('id'),$_POST['post'],$_POST['categories']);
					$this->notif->setNotification('Post Added','info');
				}
			}
			header('Location: '.Dispatcher::base());
		}else{
			Template::theme($this->auth->login());
		}
	}

	function addcomment(){
		if($this->auth->isLoged()){
			$url = explode('/',Dispatcher::whaturl());
			if(isset($_POST['comment']) && !empty($_POST['comment']) && isset($url[1]) && !empty($url[1]) && is_numeric($url[1])){
				$test = $this->database->sqlquery('SELECT * FROM '.CONFIG::PREFIX.'_posts WHERE id="'.$this->database->secure($url[1]).'"','query');
				if(!empty($test)){
					$this->posts->addcomment($this->user->getActiveUser('id'),$_POST['comment'],$url[1]);
					$this->notif->setNotification('Comment Added','info');
				}else{
					$this->notif->setNotification('Comment error','error');
				}
			}
			header('Location: '.Dispatcher::base());
		}else{
			Template::theme($this->auth->login());
		}
	}

	function editpost(){
		if($this->auth->isLoged()){
			$param = explode('/', Dispatcher::whaturl());
			if($this->posts->getPostInfo($param[1])->author_id == $this->user->getActiveUser('id')){
				if(isset($_POST) && !empty($_POST)){
					$url = explode('/', Dispatcher::whaturl());
					$image_file = $_FILES['file'];
					$image = (isset($image_file) && !empty($image_file) && !empty($image_file['name']) && !empty($image_file['type']) && !empty($image_file['tmp_name']));
					if($image){
						if($image_file['error'] == '0'){
							$this->posts->editpost($url[1],$this->user->getActiveUser('id'),$_POST['post'],$_POST['categories'],$image_file['name'],$image_file['tmp_name']);
							$this->notif->setNotification('Post Edited','info');
						}else{
							$this->notif->setNotification('Upload Fail','error');
						}
					}else{
						$this->posts->editpost($url[1],$this->user->getActiveUser('id'),$_POST['post'],$_POST['categories']);
						$this->notif->setNotification('Post Edited','info');
					}
					header('Location: '.Dispatcher::base());
				}
				Template::theme('edit');
			}else{
				Template::theme('404');
			}
		}else{
			Template::theme($this->auth->login());
		}
	}

	function deletepost(){
		if($this->auth->isLoged()){
			$param = explode('/', Dispatcher::whaturl());
			if(isset($param[1]) && !empty($param[1])){
 				$test = $this->database->sqlquery('SELECT * FROM '.CONFIG::PREFIX.'_posts WHERE id="'.$this->database->secure($param[1]).'" AND author_id="'.$this->user->getActiveUser("id").'"','query');
 				if(!empty($test) || $this->user->getActiveUser('type') == 'root'){
 					$this->database->sqlquery('DELETE FROM '.CONFIG::PREFIX.'_posts WHERE id="'.$this->database->secure($param[1]).'"');
 					echo "Deleted";
 				}
 			}
 			if(!isset($param[2]) || $param[2] != 'js'){
 				header('Location: '.Dispatcher::base());
 			}
 		}else{
			Template::theme($this->auth->login());
		}
	}

	function deletenotification(){
		if($this->auth->isLoged()){
			$param = explode('/', Dispatcher::whaturl());
			if(isset($param[1]) && !empty($param[1])){
 				$test = $this->database->sqlquery('SELECT * FROM '.CONFIG::PREFIX.'_notification WHERE id="'.$this->database->secure($param[1]).'" AND dest_id="'.$this->user->getActiveUser("id").'"','query');
 				if(!empty($test) || $this->user->getActiveUser('type') == 'root'){
 					$this->database->sqlquery('DELETE FROM '.CONFIG::PREFIX.'_notification WHERE id="'.$this->database->secure($param[1]).'"');
 					echo "Deleted";
 				}
 			}
 			header('Location: '.Dispatcher::base().'notification');
 		}else{
			Template::theme($this->auth->login());
		}
	}

	function settings(){
		if($this->auth->isLoged()){
			$param = explode('/', Dispatcher::whaturl());
			if(isset($param[1]) && $param[1] == "update"){
				if(isset($_FILES['avatar']) && !empty($_FILES['avatar']) && $_FILES['avatar']['size'] > '0'){
					$file = $_FILES['avatar'];
					if($file['type'] == 'image/gif' || $file['type'] == 'image/jpeg' || $file['type'] == 'image/png'){
						if($file['error'] == '0'){
							$filename = pathinfo($file['name']);
							$ext = $filename['extension'];
							$name = strtolower($this->user->getActiveUser('name')).'.'.$ext;
							if(file_exists(ROOT.DS.$this->user->getActiveUser('avatar')) && $this->user->getActiveUser('avatar') != 'template/images/settee.png'){
								unlink(ROOT.DS.$this->user->getActiveUser('avatar'));
							}
							$this->posts->addImage($file['tmp_name'],'avatar',$name);
							$this->database->sqlquery('UPDATE '.CONFIG::PREFIX.'_users SET avatar = "static/avatar/'.$name.'" WHERE id="'.$this->user->getActiveUser('id').'"');
						}
					}else{
						$this->notif->setNotification('Image type is bad, only .png, .jpg or .gif','error');
					}
				}
				if(isset($_POST['names']) && !empty($_POST['names'])){
					$_POST['names'] = htmlspecialchars(strip_tags($_POST['names']));
					$this->database->sqlquery('UPDATE '.CONFIG::PREFIX.'_users SET surname = "'.$this->database->secure(strip_tags($_POST['names'])).'" WHERE id="'.$this->user->getActiveUser('id').'"');
				}
				if(isset($_POST['email']) && !empty($_POST['email'])){
					if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
						$this->database->sqlquery('UPDATE '.CONFIG::PREFIX.'_users SET email = "'.$this->database->secure($_POST['email']).'" WHERE id="'.$this->user->getActiveUser('id').'"');
					}else{
						$this->notif->setNotification('Wrong email','error');
					}
				}
				if(isset($_POST['language']) && !empty($_POST['language'])){
					$tab = array();
					foreach(glob('static/language/*') as $k => $v){
						array_push($tab, basename($v,".php"));
					}
					if(in_array($_POST['language'], $tab)){
						$this->database->sqlquery('UPDATE '.CONFIG::PREFIX.'_users SET lang = "'.$this->database->secure($_POST['language']).'" WHERE id="'.$this->user->getActiveUser('id').'"');
					}else{
						$this->notif->setNotification('Wrong language','error');
					}
				}
				if(isset($_POST['password']) && !empty($_POST['password']) && isset($_POST['passwordagain']) && !empty($_POST['passwordagain'])){
					if(strlen($_POST['passwd']) >= '7' && preg_match('/^[a-zA-Z0-9\@_-]{6,}$/', $_POST['passwd'])){
						$password = crypt($this->database->secure($_POST['password']) . CONFIG::KEY);
						$this->database->sqlquery('UPDATE '.CONFIG::PREFIX.'_users SET password = "'.$password.'" WHERE id="'.$this->user->getActiveUser('id').'"');
					}else{
						$this->notif->setNotification('Password is wrong','error');
					}
				}
				header('Location: '.Dispatcher::base().'settings');
			}else{
				Template::theme('settings');
			}
 		}else{
 			Template::theme($this->auth->login());
 		}
	}

	function logout(){
		session_unset();
		session_destroy();
		header('Location: '.Dispatcher::base());
	}

	function comments(){
		if($this->auth->isLoged() || Controller::privacy() == 0){
			$url = explode('/', Dispatcher::whaturl());
			echo $this->posts->getComments($url[1],'list');
 		}else{
 			Template::theme($this->auth->register());
 		}
	}

	function register(){
		if($this->auth->isLoged()){
 			header('Location: '.Dispatcher::base());
 		}else{
 			Template::theme($this->auth->register());
 		}
	}

	function post(){
		if($this->auth->isLoged() || Controller::privacy() == 0){
			$url = explode('/', Dispatcher::whaturl());
			$pattern = explode('_',$url[2]);
			if($url[1] == 'home'){
				echo $this->posts->getAllPosts($pattern[0]);
			}elseif($url[1] == 'category'){
				echo $this->posts->getCategoryPosts($pattern[0],$pattern[2]);
			}elseif($url[1] == 'profile'){
				echo $this->posts->getProfilePosts($pattern[0],$pattern[1]);
			}
 		}else{
 			Template::theme($this->auth->register());
 		}
	}

	function share(){
		if($this->auth->isLoged() || Controller::privacy() == 0){
			Template::theme('post');
 		}else{
 			Template::theme($this->auth->register());
 		}
	}

	function profile(){
		if($this->auth->isLoged()){
 			Template::theme('profile');
 		}else{
 			Template::theme($this->auth->login());
 		}
	}

	function like(){
		if($this->auth->isLoged()){
			$url = explode('/', Dispatcher::whaturl());
			if(isset($url[1]) && !empty($url[1]) && is_numeric($url[1])){
				$test = $this->database->sqlquery('SELECT * FROM '.CONFIG::PREFIX.'_likes WHERE post_id="'.$url[1].'" AND user_id="'.$this->user->getActiveUser("id").'"','query');
				if(empty($test)){
					$this->database->sqlquery('INSERT INTO '.CONFIG::PREFIX.'_likes (post_id,user_id) VALUES("'.$url[1].'","'.$this->user->getActiveUser("id").'")');
					echo json_encode(array("Liked"));
				}
			}
			if(!isset($url[2]) || $url[2] != 'js'){
 				header('Location: '.Dispatcher::base());
 			}
		}
	}

	function dislike(){
		if($this->auth->isLoged()){
			$url = explode('/', Dispatcher::whaturl());
			if(isset($url[1]) && !empty($url[1]) && is_numeric($url[1])){
				$test = current($this->database->sqlquery('SELECT * FROM '.CONFIG::PREFIX.'_likes WHERE post_id="'.$url[1].'" AND user_id="'.$this->user->getActiveUser("id").'"','query'));
				if(!empty($test)){
					$this->database->sqlquery('DELETE FROM '.CONFIG::PREFIX.'_likes WHERE id="'.$test->id.'"');
					echo json_encode(array("Disliked"));
				}
			}
			if(!isset($url[2]) || $url[2] != 'js'){
 				header('Location: '.Dispatcher::base());
 			}
		}
	}

	function admin(){
		if($this->auth->isLoged() && $this->user->getActiveUser('type') == 'root'){
			$url = explode('/', Dispatcher::whaturl());
			if(isset($url[1])){
				switch ($url[1]) {
					case 'settings':
						if(isset($_POST) && !empty($_POST)){
							$this->admin->update_name($_POST['names']);
							$this->admin->update_privacy($_POST['accesslevel']);
							$this->admin->update_save();
							header('Location: '.Dispatcher::base().Dispatcher::whaturl());
						}
						Template::theme('settings','admin');
						break;
					case 'users':
						Template::theme('users','admin');
						break;
					case 'categories':
						if(isset($_POST) && !empty($_POST)){
							$this->admin->add_category($_POST['category']);
							header('Location: '.Dispatcher::base().Dispatcher::whaturl());
						}
						Template::theme('categories','admin');
						break;
					case 'invite':
						if(isset($_POST['email']) && !empty($_POST['email'])){
							if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
								if(mail($_POST['email'], $this->user->getActiveUser('surname').' Vous invite à vous inscrire sur settee', 'Votre ami vous propose de venir sur settee: http://'.$_SERVER['SERVER_NAME'].substr($_SERVER['SCRIPT_NAME'],0,-9))){
									$this->notif->setNotification('Email send','info');
								}else{
									$this->notif->setNotification('Error email','error');
								}
							}else{
								$this->notif->setNotification('Not an email','error');
							}
						}
						Template::theme('invite','admin');
						break;
					case 'setadmin':
						if(isset($url[2]) && !empty($url[2]) && is_numeric($url[2])){
							$etat = ($this->user->getUserById($url[2])->type == 'root')? 'user' : 'root';
							$this->database->sqlquery('UPDATE '.CONFIG::PREFIX.'_users SET type = "'.$etat.'" WHERE id="'.$url[2].'"');
							header('Location: '.Dispatcher::base().'admin/users');
						}else{
							Template::theme('404');
						}
						break;
					case 'deletecategory':
						if(isset($url[2]) && !empty($url[2]) && is_numeric($url[2])){
							$this->database->sqlquery('DELETE FROM '.CONFIG::PREFIX.'_categorie WHERE id="'.$url[2].'"');
							header('Location: '.Dispatcher::base().'admin/categories');
						}else{
							Template::theme('404');
						}
						break;
					case 'editcategorie':
						if(isset($url[2]) && !empty($url[2]) && is_numeric($url[2])){
							if(isset($_POST['name']) && isset($_POST['slug']) && !empty($_POST['name']) && !empty($_POST['slug'])){
								$this->database->sqlquery('UPDATE '.CONFIG::PREFIX.'_categorie SET name = "'.$_POST['name'].'", url = "'.$_POST['slug'].'" WHERE id="'.$url[2].'"');
								header('Location: '.Dispatcher::base().'admin/categories');
							}
							Template::theme('edit','admin');
						}else{
							Template::theme('404');
						}
						break;
					default:
						Template::theme('index','admin');
						break;
				}
			}else{
				Template::theme('index','admin');
			}
		}else{
			Template::theme('404');
		}
	}

	function deleteuser(){
		if($this->auth->isLoged()){
			$url = explode('/', Dispatcher::whaturl());
			if(isset($url[1]) && !empty($url[1]) && ($this->user->getActiveUser('id') == $url[1] ||$this->user->getActiveUser('type') == 'root')){
				var_dump($this->user->getActiveUser('id'));
				$this->database->sqlquery('DELETE FROM '.CONFIG::PREFIX.'_posts WHERE author_id="'.$url[1].'"');
				$this->database->sqlquery('DELETE FROM '.CONFIG::PREFIX.'_likes WHERE user_id="'.$url[1].'"');
				$this->database->sqlquery('DELETE FROM '.CONFIG::PREFIX.'_comments WHERE user_id="'.$url[1].'"');
				$this->database->sqlquery('DELETE FROM '.CONFIG::PREFIX.'_notification WHERE dest_id="'.$url[1].'"');
				$this->database->sqlquery('DELETE FROM '.CONFIG::PREFIX.'_users WHERE id="'.$url[1].'"');

				if($this->user->getActiveUser('type') != 'root'){
					$this->logout();
				}else{
					header('Location: '.Dispatcher::base().'admin/users');
				}
			}else{
				Template::theme('404');
			}
		}
	}
}
?>