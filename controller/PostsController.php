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
					$delete = '';$link = '';$edit = '';
					foreach($like as $k => $v) {
						if($v->user_id == $this->pages->getInfo('id')){
							$like_html = '<li><a href="'.Dispatcher::base().'dislike/'.$post->id.'" title="Dislike it" class="likes active likes_'.$post->id.'">'.count($like).'</a></li>';
						}
					}
					if(!isset($share) && $share != true){
						$link = '<div class="permalink"><a href="'.Dispatcher::base().'share/'.$post->id.'" title="Permalink">Permalink</a></div>';
					}
					
	 				$html = '<article class="post" id="'.$post->id.'_'.$me->name.'_'.$this->getComments($post->categorie_id,'info')->id.'"><div class="posthead"><div class="avatar"><a href="'.Dispatcher::base().'profile/'.$me->name.'" title="Profil"><img src="'.$this->pages->getAvatar($me->id).'" alt="avatar" /></a></div><div class="postinfos"><div class="name"><a href="'.Dispatcher::base().'profile/'.$me->name.'" title="" class="name">'.strip_tags($me->surname).'</a></div><div class="datecat">'.$this->pages->fullDate($post->date).' in <a href="'.Dispatcher::base().'category/'.$this->getComments($post->categorie_id,'info')->url.'" title="">'.$this->getComments($post->categorie_id,'info')->name.'</a></div></div></div><div class="posttext">'.$this->convertUrl(nl2br(strip_tags($post->post))).'</div>';
					if($post->image != null){
						$html .= '<div class="postimage"><div class="downarrow"></div><a href="'.Dispatcher::base().'static/post/big/'.$post->image.'" title="Extend"><img src="'.Dispatcher::base().'static/post/thumbnail/'.$post->image.'" /></a></div>';
					}
					if(($post->author_id == $this->pages->getInfo('id')) || $this->pages->getInfo('type') == 'root'){
						$delete = '<li><a href="'.Dispatcher::base().'deletepost/'.$post->id.'" title="Delete this post" class="delete '.$post->id.'_'.$me->name.'_'.$this->getComments($post->categorie_id,'info')->id.'">Delete</a></li>';
					}
					if($post->author_id == $this->pages->getInfo('id')){
						$edit = '<li><a href="'.Dispatcher::base().'editpost/'.$post->id.'" title="Edit this post">Edit</a></li>';
					}
					$html .= '<div class="postfooter">'.$link.'<div class="postinteractions"><ul>'.$edit.$delete.'<li><a id="'.$post->id.'" href="#" title="'.$nb_comment.' comment(s)" class="comments">'.$nb_comment.'</a></li>'.$like_html.'</ul></div><div class="clearfloat"></div></div></article>';
					
					echo $html;
				}else{
					echo 'nop';
				}
			}
 		}else{
 			$this->auth->isValid();
 		}
	}

	public function addPost($id,$post,$cat,$image_name=null,$image_tmp=null){
		$alphabet = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z');
		$have_image = (isset($image_name) && isset($image_tmp));
		if($have_image){
			$filename = pathinfo($image_name);
			$ext = $filename['extension'];
			$name_image = $id.'-';
			for($i=0; $i < 20; $i++){ 
				$name_image .= $alphabet[rand(0,25)];
			}
			$name_image .= '.'.$ext;
			$this->addImage($image_tmp,'thumbnail',$name_image);
			$this->addImage($image_tmp,'post',$name_image);
		}
		$database_image = ($have_image)? $name_image : null ;
		$this->database->sqlquery('INSERT INTO '.CONFIG::PREFIX.'_posts (date,post,author_id,categorie_id,image) VALUES ("'.date('Y-m-d').'","'.$this->database->secure($post).'","'.$id.'","'.$this->database->secure($cat).'","'.$database_image.'")');
	}

	public function addComment($id,$post,$post_id){
		$this->database->sqlquery('INSERT INTO '.CONFIG::PREFIX.'_comments (date,post,user_id,post_id) VALUES("'.date("Y-m-d").'","'.$this->database->secure($post).'","'.$id.'","'.$this->database->secure($post_id).'")');
	}

	public function editPost($id,$user_id,$post,$cat,$image_name=null,$image_tmp=null){
		$alphabet = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z');
		$have_image = (isset($image_name) && isset($image_tmp));
		$last_image = $this->getPostInfo($id)->image;
		if($have_image){
			if(isset($last_image) && !empty($last_image)){
				unlink(ROOT.DS.'static'.DS.'post'.DS.'thumbnail'.DS.$last_image);
				unlink(ROOT.DS.'static'.DS.'post'.DS.'big'.DS.$last_image);
			}
			$filename = pathinfo($image_name);
			$ext = $filename['extension'];
			$name_image = $user_id.'-';
			for($i=0; $i < 20; $i++){ 
				$name_image .= $alphabet[rand(0,25)];
			}
			$name_image .= '.'.$ext;
			$this->addImage($image_tmp,'thumbnail',$name_image);
			$this->addImage($image_tmp,'post',$name_image);
		}
		$database_image = ($have_image)? $name_image : $last_image ;
		$this->database->sqlquery('UPDATE  '.CONFIG::PREFIX.'_posts  SET post="'.$this->database->secure($post).'" ,  categorie_id="'.$this->database->secure($cat).'" ,  image="'.$database_image.'" WHERE id="'.$id.'"');
	}

	public function getPostInfo($id){
		if(is_numeric($id)){
 			return current($this->database->sqlquery('SELECT * FROM '.CONFIG::PREFIX.'_posts WHERE id = '.$this->database->secure($id).' LIMIT 1','query'));
 		}
	}

	public function addImage($path,$type,$name){
		if($type == 'thumbnail' || $type == 'avatar'){
			$imagine = new Imagine\Gd\Imagine();
			if($type == 'avatar'){
				$size = new Imagine\Image\Box(240, 240);
				$mode = Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND;
			}elseif($type == 'thumbnail'){
				$type = 'post'.DS.'thumbnail';
				$size = new Imagine\Image\Box(801, 478);
				$mode = Imagine\Image\ImageInterface::THUMBNAIL_INSET;
			}
			$imagine->open($path)->thumbnail($size, $mode)->save(ROOT.DS.'static'.DS.$type.DS.$name);
		}elseif($type == 'post' ){
			move_uploaded_file($path, ROOT.DS.'static'.DS.'post'.DS.'big'.DS.$name);
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
				$data = $this->database->sqlquery('SELECT comments.date,comments.post,users.surname,users.avatar,users.id FROM '.CONFIG::PREFIX.'_comments AS comments,'.CONFIG::PREFIX.'_users AS users WHERE comments.post_id='.$id.' AND comments.user_id=users.id ORDER BY comments.id DESC','query');
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
			}
		}
	}

	public function getCategories($opt,$type,$active=null){
		if(is_numeric($opt)){
			$end = current($database->sqlquery('SELECT * FROM '.CONFIG::PREFIX.'_categorie WHERE id="'.$database->secure($opt).'"','query'));
		}elseif($type == 'list' || $type == 'post'){
			if($type == "post"){
				$end = '<select name="categories">';
			}elseif($type == "list"){
				$end = '<ul>';
			}

			foreach ($this->database->sqlquery('SELECT * FROM '.CONFIG::PREFIX.'_categorie','query') as $k => $v) {
				if($type == "post"){
					if($active == $v->id){
						$end .= '<option value="'.$v->id.'" selected="selected">'.$v->name.'</option>';
					}else{
						$end .= '<option value="'.$v->id.'">'.$v->name.'</option>';
					}
				}elseif($type == "list"){
					$end .= '<li><a href="'.Dispatcher::base().'category/'.$v->url.'">'.$v->name.'</a></li>';
				}
			}
			
			if($type == "post"){
				$end .= '</select>';
			}elseif($type == "list"){
				$end .= '<ul>';
			}

			if($type == "list" && $this->pages->getInfo('type') == "root"){
				$end .= '<div class="addbutton"><a href="" title="">Add more</a></div>';
			}
		}
		return $end;
	}

	public function convertUrl($data){
		$word = explode(' ', $data);
		$pattern = '/(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?/';
		foreach($word as $k => $v){
			preg_match($pattern, $v, $matches);
			if(isset($matches[0])){
				$data = str_replace($matches[0],'<a target="_blank" href="'.$matches[0].'">'.$matches[0].'</a>', $data);
			}
		}
		return $data;
	}

}