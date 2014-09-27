<?php Class Template extends File{

	function load($page){
		if(method_exists('File',$page)){
			call_user_func_array(array('File',$page),array());
		}else{
			Template::theme('404');
		}
	}

	function theme($page){
		if(file_exists(ROOT.DS."view/site".DS.$page.".php")){
			if((Controller::privacy() >= '1' && !$this->auth->isLoged()) && (Dispatcher::whaturl() != 'login' && Dispatcher::whaturl() != 'register')){
				$page = 'private';
			}
		}else{
			$page = '404';
		}
		require_once ROOT.DS."view/site".DS.$page.".php";
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

			if($this->pages->getInfo('type') == 'root' && $opt == "list"){
				$end .= '<div class="addbutton"><a href="" title="">Add more</a></div>';
			}
		}
		return $end;
	}

	function comment(){
		print_r($this);
		if($this->auth->isLoged()){
			$html = '<li class="addcomment">
                                <form method="post" action="'.Dispatcher::base().'addcomment/">
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
