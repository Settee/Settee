<?php Class File extends Controller{

	function index(){
		if(Controller::isloged()){
			Template::theme('index');
		}else{
			Controller::auth();
		}
	}

	function login(){
 		if(Controller::isloged()){
 			header('Location: '.Dispatcher::base());
 		}else{
 			Controller::auth();
 		}
	}

	function settings(){
		if(Controller::isloged()){
			$param = explode('/', Dispatcher::whaturl());
			if(isset($param[1]) && $param[1] == "update"){
				$database = new Database;
				if(isset($_FILES['avatar']) && !empty($_FILES['avatar']) && $_FILES['avatar']['size'] > '0'){
					$file = $_FILES['avatar'];
					if($file['type'] == 'image/gif' || $file['type'] == 'image/jpeg' || $file['type'] == 'image/png'){
						if($file['error'] == '0'){
							$ext = explode('.', $file['name']);
							$name = strtolower(Template::me('name')).'.'.$ext[1];
							move_uploaded_file($file['tmp_name'], ROOT.DS.'images'.DS.'avatar'.DS.$name);
							$database->sqlquery('UPDATE '.CONFIG::PREFIX.'_users SET avatar = "images/avatar/'.$name.'" WHERE id="'.Template::me('id').'"');
						}
					}else{
						$_SESSION['e_out'] = '<div class="m-error"><span>Image type is bad, only .png, .jpg or .gif</span><div class=".clearfloat"></div></div>';
					}
				}
				if(isset($_POST['names']) && !empty($_POST['names'])){
					$database->sqlquery('UPDATE '.CONFIG::PREFIX.'_users SET surname = "'.$database->secure($_POST['names']).'" WHERE id="'.Template::me('id').'"');
				}
				if(isset($_POST['email']) && !empty($_POST['email'])){
					if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
						$database->sqlquery('UPDATE '.CONFIG::PREFIX.'_users SET email = "'.$database->secure($_POST['email']).'" WHERE id="'.Template::me('id').'"');
					}else{
						$_SESSION['e_out'] = '<div class="m-error"><span>Wrong email</span><div class=".clearfloat"></div></div>';
					}
				}
				if(isset($_POST['password']) && !empty($_POST['password']) && isset($_POST['passwordagain']) && !empty($_POST['passwordagain'])){
					if(strlen($_POST['passwd']) >= '7' && preg_match('/^[a-zA-Z0-9\@_-]{6,}$/', $_POST['passwd'])){
						$password = crypt($database->secure($_POST['password']) . CONFIG::KEY);
						$database->sqlquery('UPDATE '.CONFIG::PREFIX.'_users SET password = "'.$password.'" WHERE id="'.Template::me('id').'"');
					}else{
						$_SESSION['e_out'] = '<div class="m-error"><span>Password is wrong</span><div class=".clearfloat"></div></div>';
					}
				}
				header('Location: '.Dispatcher::base().'settings');
			}else{
				Template::theme('settings');
			}
 		}else{
 			Controller::auth();
 		}
	}

	function logout(){
		session_unset();
		session_destroy();
		header('Location: '.Dispatcher::base());
	}

	function register(){
		if(Controller::isloged()){
 			header('Location: '.Dispatcher::base());
 		}else{
 			Controller::signup();
 		}
	}

	function profile(){
		if(Controller::isloged()){
 			Template::theme('profile');
 		}else{
 			Controller::auth();
 		}
	}

	function post(){
		if(Controller::isloged()){
 			$param = explode('/', Dispatcher::whaturl());
 			if(isset($param[1])){
 				$database = new Database;
				if(is_numeric($param[1])){
	 				Template::theme('post');
	 			}elseif($param[1] == 'add'){
	 				$image = false;
	 				$error = false;
	 				if(isset($_POST['post']) && !empty($_POST['post']) && isset($_POST['categories'])){
		 				if($_FILES['file']['size'] > '0'){
		 					$file = $_FILES['file'];
							if($file['type'] == 'image/gif' || $file['type'] == 'image/jpeg' || $file['type'] == 'image/png'){
								if($file['error'] == '0'){
									$ext = explode('.', $file['name']);
									$id = current($database->sqlquery('SELECT id FROM '.CONFIG::PREFIX.'_posts WHERE author_id="'.Template::me('id').'" ORDER BY id DESC LIMIT 1','query'));
									$name = $id->id.'.'.$ext[1];
									move_uploaded_file($file['tmp_name'], ROOT.DS.'images'.DS.'post'.DS.$name);
									$image = Dispatcher::base().'images/post/'.$name;
								}
							}else{
									$error = true;
									$_SESSION['e_out'] = '<div class="m-error"><span>Oups, check your image type only .png, .jpg or .gif</span><div class=".clearfloat"></div></div>';
								}
		 				}
		 				if($error == false){
			 				if($image == true){
			 					$database->sqlquery('INSERT INTO '.CONFIG::PREFIX.'_posts (date,post,author_id,categorie_id,image) VALUES("'.date("Y-m-d").'","'.$database->secure($_POST["post"]).'","'.Template::me("id").'","'.$database->secure($_POST['categories']).'","'.$image.'")');
			 				}else{
			 					$database->sqlquery('INSERT INTO '.CONFIG::PREFIX.'_posts (date,post,author_id,categorie_id) VALUES("'.date("Y-m-d").'","'.$database->secure($_POST["post"]).'","'.Template::me("id").'","'.$database->secure($_POST['categories']).'")');
			 				}
		 				}
		 			}else{
		 				$_SESSION['e_out'] = '<div class="m-error"><span>Wrong post</span><div class=".clearfloat"></div></div>';
		 			}
	 				header('Location: '.Dispatcher::base());
	 			}elseif($param[1] == 'list' && isset($param[2]) && isset($param[3]) && $param[2] == 'last_id'){
	 				$html = '';
	 				if(isset($param[4]) && !empty($param[4])){
	 					$user = ' AND author_id="'.$database->secure($param[4]).'" ';
	 				}else{
	 					$user = '';
	 				}
	 				$posts = $database->sqlquery('SELECT * FROM '.CONFIG::PREFIX.'_posts WHERE id < '.$param[3].$user.' ORDER BY id DESC LIMIT 0,10','query');
	 				
	 				foreach($posts as $k => $v){
	 					$me = Template::user($v->author_id);
						$nb_comment = count($database->sqlquery('SELECT * FROM '.CONFIG::PREFIX.'_comments WHERE post_id="'.$v->id.'"','query'));
						$nb_like = count($database->sqlquery('SELECT * FROM '.CONFIG::PREFIX.'_likes WHERE post_id="'.$v->id.'"','query'));

		 				$html .= '<article class="post" id="'.$v->id.'"><div class="posthead"><div class="avatar"><a href="'.Dispatcher::base().'profile/'.$me->name.'" title="Profil"><img src="'.Template::avatar($me->name).'" alt="avatar" /></a></div><div class="postinfos"><div class="name"><a href="'.Dispatcher::base().'profile/'.$me->name.'" title="" class="name">'.$me->surname.'</a></div><div class="datecat">'.Template::date($v->date).' in <a href="'.Dispatcher::base().'cat/'.Template::categorie($v->categorie_id)->url.'" title="">'.Template::categorie($v->categorie_id)->url.'</a></div></div></div><div class="posttext">'.nl2br($v->post).'</div>';
						if($v->image != null){
							$html .= '<div class="postimage"><img src="'.$v->image.'" /><div class="downarrow"></div><a href="" title="Extend"></a></div>';
						}
						$html .= '<div class="postfooter"><div class="permalink"><a href="'.Dispatcher::base().'post/'.$v->id.'" title="Permalink">Permalink</a></div><div class="postinteractions"><ul><li><a id="'.$v->id.'" href="#" title="'.$nb_comment.' comment(s)" class="comments">'.$nb_comment.'</a></li><li><a href="'.Dispatcher::base().'likes/'.$v->id.'" title="Like it" class="likes">'.$nb_like.'</a></li></ul></div><div class="clearfloat"></div></div></article>';
					}
					echo $html;
	 			}
	 		}
 		}else{
 			Controller::auth();
 		}
	}

	function comments(){
		if(Controller::isloged() || Controller::privacy() == '0'){
			header('Content-Type: text/json;');
			$url = explode('/', Dispatcher::whaturl());
			$param = $url[1];
			$database = new Database;
			if(is_numeric($param)){
				$data = $database->sqlquery('SELECT comments.date,comments.post,users.surname,users.avatar FROM '.CONFIG::PREFIX.'_comments AS comments,'.CONFIG::PREFIX.'_users AS users WHERE comments.post_id='.$param.' AND comments.user_id=users.id ORDER BY comments.date ASC','query');
				foreach ($data as $key => $value) {
					foreach ($data[$key] as $k => $v){
						if($k == "date"){
							$data[$key]->$k = Template::date($data[$key]->$k);
						}
						if($k == "post"){
							$data[$key]->$k = nl2br(htmlentities(trim($data[$key]->$k,"'")));
						}
					}
				}
				echo json_encode($data);
			}elseif($param == 'post'){
				if(isset($_POST['comment']) && !empty($_POST['comment']) && isset($url[2]) && !empty($url[2]) && is_numeric($url[2])){
					$test = $database->sqlquery('SELECT * FROM '.CONFIG::PREFIX.'_posts WHERE id="'.$url[2].'"','query');
					if(!empty($test)){
						$database->sqlquery('INSERT INTO '.CONFIG::PREFIX.'_comments (date,post,user_id,post_id) VALUES("'.date("Y-m-d").'","'.$database->secure($_POST["comment"]).'","'.Template::me("id").'","'.$database->secure($url[2]).'")');
					}
				}
				header('Location: '.Dispatcher::base().'#'.$url[2]);
			}
		}
	}

	function likes(){
		$url = explode('/', Dispatcher::whaturl());
		$database = new Database;
		if(isset($url[1]) && !empty($url[1]) && is_numeric($url[1])){
			$test = $database->sqlquery('SELECT * FROM '.CONFIG::PREFIX.'_likes WHERE post_id="'.$url[1].'" AND user_id="'.Template::me("id").'"','query');
			if(empty($test)){
				$database->sqlquery('INSERT INTO '.CONFIG::PREFIX.'_likes (post_id,user_id) VALUES("'.$url[1].'","'.Template::me("id").'")');
			}
		}
		header('Location: '.Dispatcher::base().'#'.$url[1]);
	}
}
?>