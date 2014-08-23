<?php Class Template extends File{

	function load($page,$params){
		if(method_exists('File',$page)){
			call_user_func_array(array('File',$page),array());
		}else{
			Template::theme('404');
		}
	}

	function theme($page){
		if(file_exists(ROOT.DS."template".DS.$page.".php")){
			if((Controller::privacy() >= '1' && !Controller::isloged()) && (Dispatcher::whaturl() != 'login' && Dispatcher::whaturl() != 'register')){
				$page = 'private';
			}
		}else{
			$page = '404';
		}
		require_once ROOT.DS."template".DS.$page.".php";
	}

	static function date($date){
		$d = explode('-', $date);

		$date = array();
		$date[0] = $d[2];
		$date[1] = $d[1];
		$date[2] = $d[0];

		if($date[1] == '01'){$date[1] = 'Janvier';}elseif($date[1] == '02'){$date[1] = 'Février';}elseif($date[1] == '03'){$date[1] = 'Mars';}elseif($date[1] == '04'){$date[1] = 'Avril';}elseif($date[1] == '05'){$date[1] = 'Mai';}elseif($date[1] == '06'){$date[1] = 'Juin';}elseif($date[1] == '07'){$date[1] = 'Juillet';}elseif($date[1] == '08'){$date[1] = 'Août';}elseif($date[1] == '09'){$date[1] = 'Septembre';}elseif($date[1] == '10'){$date[1] = 'Octobre';}elseif($date[1] == '11'){$date[1] = 'Novembre';}elseif($date[1] == '12'){$date[1] = 'Décembre';}
		return $date[0].' '.$date[1].' '.$date[2];
	}

	static function headernav(){
		$headernavonline = '<nav><ul><li><a href="'.Dispatcher::base().'profile/'.Template::me('name').'" title="Profil" class="avatar"><img src="'.Template::avatar(Template::me("name")).'" alt="Profil" /><span>Profil</span></a></li><li><a href="'.Dispatcher::base().'settings" title="Settings" class="settings"><img src="'.Dispatcher::base().'template/images/ico-settings.svg" alt="Settings" /><span>Settings</span></a></li></ul></nav>';
		$headernavsignin = '<nav><ul id="connections"><li><a href="'.Dispatcher::base().'" title="">Home</a></li><li><a href="'.Dispatcher::base().'register" title="">Register</a></li></ul></nav>';
		$headernavsignup = '<nav><ul id="connections"><li><a href="'.Dispatcher::base().'" title="">Home</a></li><li><a href="'.Dispatcher::base().'login" title="">Login</a></li></ul></nav>';
		$headernavoffline = '<nav><ul id="connections"><li><a href="'.Dispatcher::base().'register" title="">Register</a></li><li><a href="'.Dispatcher::base().'login" title="">Login</a></li></ul></nav>';
		$headernavsettings = '<nav><ul><li><a href="'.Dispatcher::base().'profile/'.Template::me('name').'" title="Profil" class="avatar"><img src="'.Template::avatar(Template::me("name")).'" alt="Profil"></a></li></ul><ul id="connections"><li><a href="'.Dispatcher::base().'logout" title="">Logout</a></li></ul></nav>';

		if(Dispatcher::whaturl() == "register"){
			echo $headernavsignup;
		}elseif(Dispatcher::whaturl() == "login"){
			echo $headernavsignin;
		}elseif(Dispatcher::whaturl() == "settings" && Controller::isloged() == true){
			echo $headernavsettings;
		}elseif(Controller::isloged() == true){
			echo $headernavonline;
		}else{
			echo $headernavoffline;
		}
	}

	static function tmpdir($dir){
		if(isset($dir) && !empty($dir)){
			$dir .= "/";
		} 
		return Dispatcher::base()."template/".$dir;
	}

	static function categorie($opt){
		$database = new Database;

		if(is_numeric($opt)){
			$end = current($database->sqlquery('SELECT * FROM '.CONFIG::PREFIX.'_categorie WHERE id="'.$database->secure($opt).'"','query'));
		}else{
		
			if($opt == "post"){
				$end = "<select name=\"categories\">\n";
			}elseif($opt == "list"){
				$end = "<ul>\n";
			}

			foreach ($database->sqlquery('SELECT * FROM '.CONFIG::PREFIX.'_categorie','query') as $k => $v) {
				if($opt == "post"){
					$end .= "<option value=\"$v->id\">$v->name</option>\n";
				}elseif($opt == "list"){
					$end .= "<li><a href=\"".Dispatcher::base()."cat/$v->url\">$v->name</a></li>\n";
				}
			}
			
			if($opt == "post"){
				$end .= "</select>";
			}elseif($opt == "list"){
				$end .= "<ul>";
			}

			if(Template::me('type') == 'root' && $opt == "list"){
				$end .= '<div class="addbutton"><a href="" title="">Add more</a></div>';
			}
		}
		return $end;
	}

	static function me($opt){
		$database = new Database;
		if(isset($_SESSION["user_id"]) && !empty($_SESSION["user_id"])){
			return current($database->sqlquery('SELECT * FROM '.CONFIG::PREFIX.'_users WHERE id='.$database->secure($_SESSION["user_id"]),'query'))->$opt;
		}
	}

	static function user($id){
		$database = new Database;
		if(is_numeric($id)){
			$res = current($database->sqlquery('SELECT * FROM '.CONFIG::PREFIX.'_users WHERE id="'.$database->secure($id).'"','query'));
		}else{
			$res = current($database->sqlquery('SELECT * FROM '.CONFIG::PREFIX.'_users WHERE name="'.$database->secure($id).'"','query'));
		}
		return $res;
	}

	static function avatar($user){
		$database = new Database;
		$avatar = current($database->sqlquery('SELECT avatar FROM '.CONFIG::PREFIX.'_users WHERE name="'.$database->secure($user).'"','query'));
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

	static function article($user=null,$cat=null){
		$database = new Database;
		if($user != null){
			$posts = $database->sqlquery('SELECT * FROM '.CONFIG::PREFIX.'_posts as posts, '.CONFIG::PREFIX.'_users as users WHERE users.id=posts.author_id AND users.name="'.$database->secure($user).'" ORDER BY posts.id DESC LIMIT 0,10','query');
		}else{
			$posts = $database->sqlquery('SELECT * FROM '.CONFIG::PREFIX.'_posts ORDER BY id DESC LIMIT 0,10','query');
		}
		$html = '';
		foreach($posts as $k => $v){
			$me = Template::user($v->author_id);
			$nb_comment = count($database->sqlquery('SELECT * FROM '.CONFIG::PREFIX.'_comments WHERE post_id="'.$v->id.'"','query'));
			$nb_like = count($database->sqlquery('SELECT * FROM '.CONFIG::PREFIX.'_likes WHERE post_id="'.$v->id.'"','query'));

 				$html .= '<article class="post" id="'.$v->id.'"><div class="posthead"><div class="avatar"><a href="'.Dispatcher::base().'profile/'.$me->name.'" title="Profil"><img src="'.Template::avatar($me->name).'" alt="avatar" /></a></div><div class="postinfos"><div class="name"><a href="'.Dispatcher::base().'profile/'.$me->name.'" title="" class="name">'.$me->surname.'</a></div><div class="datecat">'.Template::date($v->date).' in <a href="'.Dispatcher::base().'cat/'.Template::categorie($v->categorie_id)->url.'" title="">'.Template::categorie($v->categorie_id)->name.'</a></div></div></div><div class="posttext">'.nl2br($v->post).'</div>';
				if($v->image != null){
					$html .= '<div class="postimage"><img src="'.$v->image.'" /><div class="downarrow"></div><a href="" title="Extend"></a></div>';
				}
				$html .= '<div class="postfooter"><div class="permalink"><a href="'.Dispatcher::base().'post/'.$v->id.'" title="Permalink">Permalink</a></div><div class="postinteractions"><ul><li><a href="#" title="Delete this post">Delete</a></li><li><a id="'.$v->id.'" href="#" title="'.$nb_comment.' comment(s)" class="comments">'.$nb_comment.'</a></li><li><a href="'.Dispatcher::base().'likes/'.$v->id.'" title="Like it" class="likes">'.$nb_like.'</a></li></ul></div><div class="clearfloat"></div></div></article>';
			}
		return $html;
	}

	static function posts($id){
		$database = new Database;
		$post = current($database->sqlquery('SELECT * FROM '.CONFIG::PREFIX.'_posts as posts, '.CONFIG::PREFIX.'_users as users WHERE users.id=posts.author_id AND posts.id="'.$database->secure($id).'" LIMIT 1','query'));
			$me = Template::user($post->author_id);
			$nb_comment = count($database->sqlquery('SELECT * FROM '.CONFIG::PREFIX.'_comments WHERE post_id="'.$id.'"','query'));
			$nb_like = count($database->sqlquery('SELECT * FROM '.CONFIG::PREFIX.'_likes WHERE post_id="'.$id.'"','query'));

			$html = '<article class="post" id="'.$id.'"><div class="posthead"><div class="avatar"><a href="'.Dispatcher::base().'profile/'.$me->name.'" title="Profil"><img src="'.Template::avatar($me->name).'" alt="avatar" /></a></div><div class="postinfos"><div class="name"><a href="'.Dispatcher::base().'profile/'.$me->name.'" title="" class="name">'.$me->surname.'</a></div><div class="datecat">'.Template::date($post->date).' in <a href="'.Dispatcher::base().'cat/'.Template::categorie($post->categorie_id)->url.'" title="">'.Template::categorie($post->categorie_id)->name.'</a></div></div></div><div class="posttext">'.nl2br($post->post).'</div>';
			if($post->image != null){
				$html .= '<div class="postimage"><img src="'.$v->image.'" /><div class="downarrow"></div><a href="" title="Extend"></a></div>';
			}
			$html .= '<div class="postfooter"><div class="postinteractions"><ul><li><a id="'.$id.'" href="#" title="'.$nb_comment.' comment(s)" class="comments">'.$nb_comment.'</a></li><li><a href="'.Dispatcher::base().'likes/'.$id.'" title="Like it" class="likes">'.$nb_like.'</a></li></ul></div><div class="clearfloat"></div></div></article>';
		return $html;
	}

	static function comment(){
		if(Controller::isloged()){
			$html = '<li class="addcomment">
                                <form method="post" action="'.Dispatcher::base().'comments/post/">
                                    <div class="textarea">
                                        <textarea name="comment" required="required" placeholder="Add your comment…"></textarea>
                                    </div>
                                    <div class="buttons">
                                        <input value="Post comment" type="submit">
                                        <input value="Cancel" type="reset">
                                    </div>
                                    <div class="clearfloat"></div>
                                </form>
                            </li>';

		}
		return $html;
	}

}
?>
