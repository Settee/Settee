<?php Class PostsController extends PagesController{

	private $database,$pages,$auth;

	function __construct(){
		$this->database = new Database;
		$this->pages = new PagesController;
		$this->auth = new AuthController;
	}

	public function getPost($id,$share=null){
		if($this->auth->isLoged()){
			if(is_numeric($id)){
 				$post = current($this->database->sqlquery('SELECT * FROM '.CONFIG::PREFIX.'_posts WHERE id = '.$id.' LIMIT 1','query'));
 				if(!empty($post)){
					$me = $this->pages->getUserInfo($post->author_id);
					$nb_comment = count($this->database->sqlquery('SELECT * FROM '.CONFIG::PREFIX.'_comments WHERE post_id="'.$post->id.'"','query'));
					$like = $this->database->sqlquery('SELECT * FROM '.CONFIG::PREFIX.'_likes WHERE post_id="'.$post->id.'"','query');
					$like_html = '<li><a href="'.Dispatcher::base().'like/'.$post->id.'" title="Like it" class="likes likes_'.$post->id.'">'.count($like).'</a></li>';
					$delete = '';$link = '';
					foreach($like as $k => $v) {
						if($v->user_id == $this->pages->getInfo('id')){
							$like_html = '<li><a href="'.Dispatcher::base().'dislike/'.$post->id.'" title="Dislike it" class="likes active likes_'.$post->id.'">'.count($like).'</a></li>';
						}
					}
					if(!isset($share) && $share != true){
						$link = '<div class="permalink"><a href="'.Dispatcher::base().'share/'.$post->id.'" title="Permalink">Permalink</a></div>';
					}
					
	 				$html = '<article class="post" id="'.$post->id.'_'.$me->name.'_'.$this->getComments($post->categorie_id,'info')->id.'"><div class="posthead"><div class="avatar"><a href="'.Dispatcher::base().'profile/'.$me->name.'" title="Profil"><img src="'.$this->pages->getAvatar($me->id).'" alt="avatar" /></a></div><div class="postinfos"><div class="name"><a href="'.Dispatcher::base().'profile/'.$me->name.'" title="" class="name">'.strip_tags($me->surname).'</a></div><div class="datecat">'.$this->pages->fullDate($post->date).' in <a href="'.Dispatcher::base().'category/'.$this->getComments($post->categorie_id,'info')->url.'" title="">'.$this->getComments($post->categorie_id,'info')->name.'</a></div></div></div><div class="posttext">'.nl2br(strip_tags($post->post)).'</div>';
					if($post->image != null){
						$html .= '<div class="postimage"><div class="downarrow"></div><a href="" title="Extend"><img src="'.$post->image.'" /></a></div>';
					}
					if(($post->author_id == $this->pages->getInfo('id')) || $this->pages->getInfo('type') == 'root'){
						$delete = '<li><a href="'.Dispatcher::base().'post/delete/'.$post->id.'" title="Delete this post" class="delete '.$me->name.'_'.$post->id.'">Delete</a></li>';
					}
					$html .= '<div class="postfooter">'.$link.'<div class="postinteractions"><ul><li><a href="'.Dispatcher::base().'editpost" title="Edit this post">Edit</a></li>'.$delete.'<li><a id="'.$post->id.'" href="#" title="'.$nb_comment.' comment(s)" class="comments">'.$nb_comment.'</a></li>'.$like_html.'</ul></div><div class="clearfloat"></div></div></article>';
					
					echo $html;
				}else{
					echo 'nop';
				}
			}
 		}else{
 			$this->auth->isValid();
 		}
	}

	public function addPost(){
		
	}

	public function editPost(){

	}

	public function getPostInfo($id){
		if(is_numeric($id)){
 			return current($this->database->sqlquery('SELECT * FROM '.CONFIG::PREFIX.'_posts WHERE id = '.$this->database->secure($id).' LIMIT 1','query'));
 		}
	}

	public function addImage($path,$type,$name){
		if($type == 'post' || $type == 'avatar'){
			$imagine = new Imagine\Gd\Imagine();
			$size = new Imagine\Image\Box(240, 240);
			$mode = Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND;
			$imagine->open($path)->thumbnail($size, $mode)->save(ROOT.DS.'static'.DS.$type.DS.$name);
			
			$result = true;
		}else{
			$result = false;
		}
	}

	public function getAllPosts($last_id){
		if(isset($last_id) && !empty($last_id) && is_numeric($last_id)){
			$posts = $this->database->sqlquery('SELECT * FROM '.CONFIG::PREFIX.'_posts WHERE id < '.$this->database->secure($last_id).' ORDER BY id DESC LIMIT 10','query');
			foreach($posts as $k => $v){
				$this->getPost($v->id);
			}
		}
	}

	public function getPosts(){
		$posts = $this->database->sqlquery('SELECT * FROM '.CONFIG::PREFIX.'_posts ORDER BY id DESC LIMIT 10','query');
		foreach($posts as $k => $v){
			$this->getPost($v->id);
		}
	}

	public function getPostsCategory($cat_id){
		$posts = $this->database->sqlquery('SELECT * FROM '.CONFIG::PREFIX.'_posts WHERE categorie_id="'.$this->database->secure($cat_id).'" ORDER BY id DESC LIMIT 10','query');
		foreach($posts as $k => $v){
			$this->getPost($v->id);
		}
	}

	public function getCategoryPosts($last_id,$cat_id){
		$posts = $this->database->sqlquery('SELECT * FROM '.CONFIG::PREFIX.'_posts WHERE categorie_id="'.$this->database->secure($cat_id).'" AND id < '.$this->database->secure($last_id).' ORDER BY id DESC LIMIT 10','query');
		foreach($posts as $k => $v){
			$this->getPost($v->id);
		}
	}

	public function getPostsProfile($user){
		$user_id = $this->pages->getUserInfo($user)->id;
		$posts = $this->database->sqlquery('SELECT * FROM '.CONFIG::PREFIX.'_posts WHERE author_id="'.$this->database->secure($user_id).'" ORDER BY id DESC LIMIT 10','query');
		foreach($posts as $k => $v){
			$this->getPost($v->id);
		}
	}

	public function getProfilePosts($last_id,$user){
		$user_id = $this->pages->getUserInfo($user)->id;
		$posts = $this->database->sqlquery('SELECT * FROM '.CONFIG::PREFIX.'_posts WHERE author_id="'.$this->database->secure($user_id).'" AND id < '.$this->database->secure($last_id).' ORDER BY id DESC LIMIT 10','query');
		foreach($posts as $k => $v){
			$this->getPost($v->id);
		}
	}

	public function getComments($id,$type){
		if($this->auth->isLoged() || Controller::privacy() == '0'){
			if($type == 'list'){
				$data = $this->database->sqlquery('SELECT comments.date,comments.post,users.surname,users.avatar,users.id FROM '.CONFIG::PREFIX.'_comments AS comments,'.CONFIG::PREFIX.'_users AS users WHERE comments.post_id='.$id.' AND comments.user_id=users.id ORDER BY comments.date ASC','query');
				foreach ($data as $key => $value) {
					foreach ($data[$key] as $k => $v){
						if($k == "date"){
							$data[$key]->$k = $this->pages->fullDate($data[$key]->$k);
						}
						if($k == "post"){
							$data[$key]->$k = nl2br(htmlspecialchars(strip_tags(trim($data[$key]->$k,"'"))));
						}
						if($k == "surname"){
							$data[$key]->$k = strip_tags($data[$key]->$k);
						}
						if($k == "avatar"){
							$data[$key]->$k = $this->pages->getAvatar($data[$key]->id);
						}
					}
				}
				return json_encode($data);
			}elseif($type == 'info'){
				return current($this->database->sqlquery('SELECT * FROM '.CONFIG::PREFIX.'_categorie WHERE id="'.$this->database->secure($id).'"','query'));
			}elseif($param == 'post'){
				if(isset($_POST['comment']) && !empty($_POST['comment']) && isset($url[2]) && !empty($url[2]) && is_numeric($url[2])){
					$test = $this->database->sqlquery('SELECT * FROM '.CONFIG::PREFIX.'_posts WHERE id="'.$url[2].'"','query');
					if(!empty($test)){
						$this->database->sqlquery('INSERT INTO '.CONFIG::PREFIX.'_comments (date,post,user_id,post_id) VALUES("'.date("Y-m-d").'","'.$this->database->secure($_POST["comment"]).'","'.Template::me("id").'","'.$this->database->secure($url[2]).'")');
					}
				}
				header('Location: '.Dispatcher::base().'#'.$url[2]);
			}
		}
	}

	public function getCategories($opt,$type){
		if(is_numeric($opt)){
			$end = current($database->sqlquery('SELECT * FROM '.CONFIG::PREFIX.'_categorie WHERE id="'.$database->secure($opt).'"','query'));
		}elseif($type == 'list' || $type == 'post'){
			if($type == "post"){
				$end = "<select name=\"categories\">\n";
			}elseif($type == "list"){
				$end = "<ul>\n";
			}

			foreach ($this->database->sqlquery('SELECT * FROM '.CONFIG::PREFIX.'_categorie','query') as $k => $v) {
				if($type == "post"){
					$end .= "<option value=\"$v->id\">$v->name</option>\n";
				}elseif($type == "list"){
					$end .= "<li><a href=\"".Dispatcher::base()."category/$v->url\">$v->name</a></li>\n";
				}
			}
			
			if($type == "post"){
				$end .= "</select>";
			}elseif($type == "list"){
				$end .= "<ul>";
			}

			if($type == "list" && $this->pages->getInfo('type') == "root"){
				$end .= '<div class="addbutton"><a href="" title="">Add more</a></div>';
			}
		}
		return $end;
	}

}