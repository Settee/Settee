<?php Class File{

	function index(){
		if($this->auth->isLoged()){
			Template::theme('index');
		}else{
			Template::theme($this->auth->login());
		}
	}

	function login(){
		if($this->auth->isLoged()){
			header('Location: '.Dispatcher::base());
		}else{
			Template::theme($this->auth->login());
		}
	}

	function category(){
		if($this->auth->isLoged()){
			Template::theme('categorie');
		}else{
			Template::theme($this->auth->login());
		}
	}

	function addpost(){
		if($this->auth->isLoged()){
			if(isset($_POST) && !empty($_POST)){
				$image_file = $_FILES['file'];
				$image = (isset($image_file) && !empty($image_file) && !empty($image_file['name']) && !empty($image_file['type']) && !empty($image_file['tmp_name']));
				if($image){
					if($image_file['error'] == '0'){
						$this->posts->addpost($this->pages->getInfo('id'),$_POST['post'],$_POST['categories'],$image_file['name'],$image_file['tmp_name']);
						$this->pages->setNotification('Post Added','info');
					}else{
						$this->pages->setNotification('Upload Fail','error');
					}
				}else{
					$this->posts->addpost($this->pages->getInfo('id'),$_POST['post'],$_POST['categories']);
					$this->pages->setNotification('Post Added','info');
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
					$this->posts->addcomment($this->pages->getInfo('id'),$_POST['comment'],$url[1]);
					$this->pages->setNotification('Comment Added','info');
				}else{
					$this->pages->setNotification('Comment error','error');
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
			if($this->posts->getPostInfo($param[1])->author_id == $this->pages->getInfo('id')){
				if(isset($_POST) && !empty($_POST)){
					$url = explode('/', Dispatcher::whaturl());
					$image_file = $_FILES['file'];
					$image = (isset($image_file) && !empty($image_file) && !empty($image_file['name']) && !empty($image_file['type']) && !empty($image_file['tmp_name']));
					if($image){
						if($image_file['error'] == '0'){
							$this->posts->editpost($url[1],$this->pages->getInfo('id'),$_POST['post'],$_POST['categories'],$image_file['name'],$image_file['tmp_name']);
							$this->pages->setNotification('Post Edited','info');
						}else{
							$this->pages->setNotification('Upload Fail','error');
						}
					}else{
						$this->posts->editpost($url[1],$this->pages->getInfo('id'),$_POST['post'],$_POST['categories']);
						$this->pages->setNotification('Post Edited','info');
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
 				$test = $this->database->sqlquery('SELECT * FROM '.CONFIG::PREFIX.'_posts WHERE id="'.$this->database->secure($param[1]).'" AND author_id="'.$this->pages->getInfo("id").'"','query');
 				if(!empty($test) || $this->pages->getInfo('type') == 'root'){
 					$this->database->sqlquery('DELETE FROM '.CONFIG::PREFIX.'_posts WHERE id="'.$this->database->secure($param[1]).'"');
 					echo "Deleted";
 				}
 			}
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
							$name = strtolower($this->pages->getInfo('name')).'.'.$ext;
							if(file_exists(ROOT.DS.$this->pages->getInfo('avatar')) && $this->pages->getInfo('avatar') != 'template/images/settee.png'){
								unlink(ROOT.DS.$this->pages->getInfo('avatar'));
							}
							$this->posts->addImage($file['tmp_name'],'avatar',$name);
							$this->database->sqlquery('UPDATE '.CONFIG::PREFIX.'_users SET avatar = "static/avatar/'.$name.'" WHERE id="'.$this->pages->getInfo('id').'"');
						}
					}else{
						$this->pages->setNotification('Image type is bad, only .png, .jpg or .gif','error');
					}
				}
				if(isset($_POST['names']) && !empty($_POST['names'])){
					$_POST['names'] = htmlspecialchars(strip_tags($_POST['names']));
					$this->database->sqlquery('UPDATE '.CONFIG::PREFIX.'_users SET surname = "'.$this->database->secure($_POST['names']).'" WHERE id="'.$this->pages->getInfo('id').'"');
				}
				if(isset($_POST['email']) && !empty($_POST['email'])){
					if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
						$this->database->sqlquery('UPDATE '.CONFIG::PREFIX.'_users SET email = "'.$this->database->secure($_POST['email']).'" WHERE id="'.$this->pages->getInfo('id').'"');
					}else{
						$this->pages->setNotification('Wrong email','error');
					}
				}
				if(isset($_POST['password']) && !empty($_POST['password']) && isset($_POST['passwordagain']) && !empty($_POST['passwordagain'])){
					if(strlen($_POST['passwd']) >= '7' && preg_match('/^[a-zA-Z0-9\@_-]{6,}$/', $_POST['passwd'])){
						$password = crypt($this->database->secure($_POST['password']) . CONFIG::KEY);
						$this->database->sqlquery('UPDATE '.CONFIG::PREFIX.'_users SET password = "'.$password.'" WHERE id="'.$this->pages->getInfo('id').'"');
					}else{
						$this->pages->setNotification('Password is wrong','error');
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
		if($this->auth->isLoged()){
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
		if($this->auth->isLoged()){
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
		if($this->auth->isLoged()){
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
				$test = $this->database->sqlquery('SELECT * FROM '.CONFIG::PREFIX.'_likes WHERE post_id="'.$url[1].'" AND user_id="'.$this->pages->getInfo("id").'"','query');
				if(empty($test)){
					$this->database->sqlquery('INSERT INTO '.CONFIG::PREFIX.'_likes (post_id,user_id) VALUES("'.$url[1].'","'.$this->pages->getInfo("id").'")');
					echo "Liked";
				}
			}
		}
	}

	function dislike(){
		if($this->auth->isLoged()){
			$url = explode('/', Dispatcher::whaturl());
			if(isset($url[1]) && !empty($url[1]) && is_numeric($url[1])){
				$test = current($this->database->sqlquery('SELECT * FROM '.CONFIG::PREFIX.'_likes WHERE post_id="'.$url[1].'" AND user_id="'.$this->pages->getInfo("id").'"','query'));
				if(!empty($test)){
					$this->database->sqlquery('DELETE FROM '.CONFIG::PREFIX.'_likes WHERE id="'.$test->id.'"');
					echo "Disliked";
				}
			}
		}
	}
}
?>