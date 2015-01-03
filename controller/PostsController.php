<?php Class PostsController extends Controller{

	private $database,$pages,$auth,$lang,$general,$user;

	function __construct(){
		$this->general = Controller::loading_controller('GeneralController');
		$this->pages = Controller::loading_controller('PagesController');
		$this->user = Controller::loading_controller('UserController');
		$this->auth = Controller::loading_controller('AuthController');
		$this->lang = Controller::loading_controller('LangController');
		$this->database = Controller::loading_controller('Database');
	}

	public function getPost($page,$type=null,$param=null){
		if($this->auth->isLoged() || Controller::privacy() == 0){
			if(is_numeric($page) && $page >= 0){
				$html_error = '<div class="pagecontent"><p>'.$this->lang->i18n('site_404_message').'</p></div>';
				$page *= 10; $html = ''; $paginate = ''; $where = '';
				$limit_sql = 'LIMIT '.$page.',11';

				if($type == 'cat' && isset($param) && !empty($param)){
					$where = 'WHERE categorie_id = "'.$this->database->secure($param).'"';
				}elseif($type == 'user' && isset($param) && !empty($param)){
					$where = 'WHERE author_id = "'.$this->database->secure($param).'"';
				}

 				$posts = $this->database->sqlquery('SELECT posts.* FROM '.CONFIG::PREFIX.'_posts as posts '.$where.' ORDER BY id DESC '.$limit_sql,'query');
 				$likes = $this->database->sqlquery('SELECT posts.id as like_post, COUNT(likes.post_id) as nb_like, likes.user_id as user_like FROM '.CONFIG::PREFIX.'_posts as posts, '.CONFIG::PREFIX.'_likes as likes WHERE likes.post_id = posts.id GROUP BY likes.post_id ORDER BY posts.id','query');
 				$comments = $this->database->sqlquery('SELECT posts.id as comments_post, COUNT(comments.post_id) as nb_comments FROM '.CONFIG::PREFIX.'_posts as posts, '.CONFIG::PREFIX.'_comments as comments WHERE comments.post_id = posts.id GROUP BY comments.post_id ORDER BY posts.id','query');
				if(!empty($posts)){
					if(count($posts) == '11'){
						$paginate = $this->paginate(true);
						array_pop($posts);
					}else{
						$paginate = $this->paginate(false);
					}
					foreach($posts as $k => $v){
						$me = $this->user->getUserById($v->author_id);
						$cat = $this->getComments($v->categorie_id,'info');
						$edit = ''; $delete = ''; $like = ''; $like_or_dislike = 'like';
						$nb_like = '0'; $nb_comments = '0';

						foreach($likes as $key => $value){
							if($v->id == $value->like_post){
								$nb_like = $value->nb_like;
								if($this->user->getActiveUser('id') == $value->user_like){
									$like = ' active';
									$like_or_dislike = 'dislike';
								}
							}
						}
						
						foreach($comments as $key => $value){
							if($v->id == $value->comments_post){
								$nb_comments = $value->nb_comments;
							}
						}
						
						$html .= '<article  id="'.$v->id.'_'.$me->name.'_'.$cat->id.'"><div class="post"><div class="posthead"><div class="avatar"><img src="'.$this->user->getUserAvatar($me->id).'" alt="Avatar" /></div><div class="infos"><div class="name"><a href="'.Dispatcher::base().'profile/'.$me->name.'" title="Posted by  '.strip_tags($me->surname).'" class="name">'.strip_tags($me->surname).'</a></div><div class="datecat">'.$this->general->getFullDate($v->date).' in <a href="'.Dispatcher::base().'category/'.$cat->url.'" title="Posted in '.$cat->name.'">'.$cat->name.'</a></div></div><div class="clearfloat"></div></div><div class="posttext">'.$this->convertUrl(nl2br(strip_tags($v->post))).'</div>';

						if($v->image != null){
							$html .= '<div class="postimage"><a target="_blank" href="'.Dispatcher::base().'static/post/big/'.$v->image.'"><img src="'.Dispatcher::base().'static/post/thumbnail/'.$v->image.'" alt="Preview image" /></a></div>';
						}

						if($v->author_id == $this->user->getActiveUser('id')){
							$edit = '<li><a href="'.Dispatcher::base().'editpost/'.$v->id.'" title="Edit this post"><i class="fa fa-pencil"></i><span>Edit</span></a></li>';
						}

						if(($v->author_id == $this->user->getActiveUser('id')) || $this->user->getActiveUser('type') == 'root'){
							$delete = '<li><a href="'.Dispatcher::base().'deletepost/'.$v->id.'" title="Delete this post" class="delete '.$v->id.'_'.$me->name.'_'.$cat->id.'"><i class="fa fa-trash"></i><span>Delete</span></a></li>';
						}

						$html .= '<div class="postfooter"><ul>'.$edit.$delete.'<li class="like'.$like.'"><a href="'.Dispatcher::base().$like_or_dislike.'/'.$v->id.'" title="Like or dislike this post"><i class="fa fa-heart"></i><span>'.$nb_like.'</span></a></li><li class="buttonComments" id="'.$v->id.'"><a href="" title="Read and write comments on this post"><i class="fa fa-comment"></i><span>'.$nb_comments.'</span></a></li><li><a href="'.Dispatcher::base().'share/'.$v->id.'" title="Share this post"><i class="fa fa-share"></i><span>Share</span></a></li></ul><div class="clearfloat"></div></div></div>';
						$html .= '<div class="comments">';
						if($this->auth->isLoged()){
							$html .= '<div class="addcomment"><form method="post" action="'.Dispatcher::base().'addcomment/'.$v->id.'"><textarea name="comment" name="comment" placeholder="Add a comment"></textarea><input type="submit" value="Send" /></form></div>';
						}
						$html .= '<ul>'.$this->getComments($v->id,'list').'</ul></div></article>';

					}
					echo $html.$paginate;
				}else{
					echo $html_error;
				}
			}else{
				echo $html_error;
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
		$this->database->sqlquery('INSERT INTO '.CONFIG::PREFIX.'_posts (date,post,author_id,categorie_id,image) VALUES ("'.date('Y-m-d H:i:s').'","'.$this->database->secure(strip_tags($post)).'","'.$id.'","'.$this->database->secure($cat).'","'.$database_image.'")');
	}

	public function addComment($id,$post,$post_id){
		$this->database->sqlquery('INSERT INTO '.CONFIG::PREFIX.'_comments (date,post,user_id,post_id) VALUES("'.date('Y-m-d H:i:s').'","'.$this->database->secure($post).'","'.$id.'","'.$this->database->secure($post_id).'")');
		$origin = current($this->database->sqlquery('SELECT '.CONFIG::PREFIX.'_users.id FROM '.CONFIG::PREFIX.'_users, '.CONFIG::PREFIX.'_posts WHERE '.CONFIG::PREFIX.'_users.id = '.CONFIG::PREFIX.'_posts.author_id AND '.CONFIG::PREFIX.'_posts.id = "'.$post_id.'"','query'));
		if($origin->id != $this->user->getActiveUser('id')){
			$this->database->sqlquery('INSERT INTO '.CONFIG::PREFIX.'_notification (dest_id,origin_id,post_id) VALUES("'.$origin->id.'","'.$id.'","'.$post_id.'")');
		}
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
		$this->database->sqlquery('UPDATE  '.CONFIG::PREFIX.'_posts  SET post="'.$this->database->secure(strip_tags($post)).'" ,  categorie_id="'.$this->database->secure($cat).'" ,  image="'.$database_image.'" WHERE id="'.$id.'"');
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

	public function paginate($next=true){
		$page = (int)$this->getPage();

		$page_prev = $page -1;
		$page_next = $page +1;

		$base_url = array_reverse(explode('/', Dispatcher::whaturl()));
		if(isset($base_url[1]) && $base_url[1] == 'page' && is_numeric($base_url[0])){
			array_shift($base_url);
			array_shift($base_url);
		}
		$final_url = implode('/', array_reverse($base_url));

		$url_prev = ($page_prev >= 0)? '<li><a href="'.Dispatcher::base().$final_url.'/page/'.$page_prev.'" title="">Previous page</a></li>':'';
		if($next == true){
			$url_next = ($page_next >= 0)? '<li><a href="'.Dispatcher::base().$final_url.'/page/'.$page_next.'" title="">Next page</a></li>':'';
		}else{
			$url_next = '';
		}

		return '<div id="pagination"><ul>'.$url_prev.$url_next."</ul></div>";
	}

	public function getPage(){
		$url = explode('/',Dispatcher::whaturl());
		$url_reverse = array_reverse($url);
		
		if(isset($url_reverse[1]) && $url_reverse[1] == 'page' && is_numeric($url_reverse[0])){
			$page = (int)$url_reverse[0];
		}else{
			$page = 0;
		}
		return $page;
	}

	public function getIndexPosts(){
		echo $this->getPost($this->getPage());
	}

	public function getCategoryPosts($cat_id){
		$this->getPost($this->getPage(),'cat',$cat_id);
	}
	public function getProfilePosts($user_id){
		$this->getPost($this->getPage(),'user',$user_id);
	}

	public function getComments($id,$type){
		if($this->auth->isLoged() || Controller::privacy() == '0'){
			if($type == 'list'){
				$data = $this->database->sqlquery('SELECT comments.date,comments.post,users.surname,users.name,users.avatar,users.id FROM '.CONFIG::PREFIX.'_comments AS comments,'.CONFIG::PREFIX.'_users AS users WHERE comments.post_id='.$id.' AND comments.user_id=users.id ORDER BY comments.id DESC','query');
				$html = '';
				foreach($data as $k => $v){
					$html .= '<li><div class="avatar"><a href="'.Dispatcher::base().'profile/'.$v->name.'" title="'.$v->surname.' profile"><img src="'.$this->user->getUserAvatar($v->id).'" alt="avatar" /></a></div><div class="commentright"><div class="commentinfos"><a href="'.Dispatcher::base().'profile/'.$v->name.'" title="'.$v->surname.' profile">'.$v->surname.'</a>  <span>'.$this->general->getFullDate($v->date).'</span></div><div class="commentcontent">'.nl2br($v->post).'</div></div></li>';
				}
				return $html;
			}elseif($type == 'info'){
				return current($this->database->sqlquery('SELECT * FROM '.CONFIG::PREFIX.'_categorie WHERE id="'.$this->database->secure($id).'"','query'));
			}
		}
	}

	public function getCategories($opt,$type,$active=null){
		if(is_numeric($opt)){
			$end = current($database->sqlquery('SELECT * FROM '.CONFIG::PREFIX.'_categorie WHERE url="'.$database->secure($opt).'"','query'));
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
		}
		return $end;
	}

	public function convertUrl($data){
		$word = explode(' ', $data);
		$pattern = '/(https?:\/\/)([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?/';
		foreach($word as $k => $v){
			preg_match($pattern, $v, $matches);
			if(isset($matches[0])){
				$data = str_replace($matches[0],'<a target="_blank" href="'.$matches[0].'">'.$matches[0].'</a>', $data);
			}
		}
		return $data;
	}

}