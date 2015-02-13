<?php Class AdminController extends Controller{

	private $database,$update_name,$update_privacy;

	public function __construct(){
		$this->database = Controller::loading_controller('Database');
	}

	public function update_name($name){
		if(isset($name) && !empty($name) && is_string($name)){
			$this->update_name = $name;
		}else{
			$this->update_name = CONFIG::WEBSITE;
		}
	}

	public function update_privacy($privacy){
		$choise = array('public','publicregistration','privateadminvalitation','privatememberinvite','privateadmininvite');
		if(isset($privacy) && !empty($privacy) && in_array($privacy, $choise)){
			$this->update_privacy = $privacy;
		}else{
			$this->update_privacy = CONFIG::PRIVACY;
		}
	}

	public function update_save(){
		$conf = '<?php Class Config extends Dispatcher{/* If you don\'t know PHP and this CMS DON\'T TOUCH THIS FILE PLEASE */ const WEBSITE = "'.$this->update_name.'";const HOST = "'.CONFIG::HOST.'";const USER = "'.CONFIG::USER.'";const PASSWD = "'.CONFIG::PASSWD.'";const DB = "'.CONFIG::DB.'";const PREFIX = "'.CONFIG::PREFIX.'";const KEY = "'.CONFIG::KEY.'";const PRIVACY = "'.$this->update_privacy.'";}?>';
		$file = fopen(ROOT.DS.'config.php', 'w+');
		$ligne = fputs($file,$conf);
		fclose($file);
		chmod(ROOT.DS."config.php",0700);
	}

	public function add_category($name){
		$alphabet = array(
			'Å&nbsp;'=>'S', 'Å¡'=>'s', 'Ã'=>'Dj','Å½'=>'Z', 'Å¾'=>'z', 'Ã€'=>'A', 'Ã'=>'A', 'Ã‚'=>'A', 'Ãƒ'=>'A', 'Ã„'=>'A',
			'Ã…'=>'A', 'Ã†'=>'A', 'Ã‡'=>'C', 'Ãˆ'=>'E', 'Ã‰'=>'E', 'ÃŠ'=>'E', 'Ã‹'=>'E', 'ÃŒ'=>'I', 'Ã'=>'I', 'ÃŽ'=>'I',
			'Ã'=>'I', 'Ã‘'=>'N', 'Ã’'=>'O', 'Ã“'=>'O', 'Ã”'=>'O', 'Ã•'=>'O', 'Ã–'=>'O', 'Ã˜'=>'O', 'Ã™'=>'U', 'Ãš'=>'U',
			'Ã›'=>'U', 'Ãœ'=>'U', 'Ã'=>'Y', 'Ãž'=>'B', 'ÃŸ'=>'Ss','Ã&nbsp;'=>'a', 'Ã¡'=>'a', 'Ã¢'=>'a', 'Ã£'=>'a', 'Ã¤'=>'a',
			'Ã¥'=>'a', 'Ã¦'=>'a', 'Ã§'=>'c', 'Ã¨'=>'e', 'Ã©'=>'e', 'Ãª'=>'e', 'Ã«'=>'e', 'Ã¬'=>'i', 'Ã­'=>'i', 'Ã®'=>'i',
			'Ã¯'=>'i', 'Ã°'=>'o', 'Ã±'=>'n', 'Ã²'=>'o', 'Ã³'=>'o', 'Ã´'=>'o', 'Ãµ'=>'o', 'Ã¶'=>'o', 'Ã¸'=>'o', 'Ã¹'=>'u',
			'Ãº'=>'u', 'Ã»'=>'u', 'Ã½'=>'y', 'Ã½'=>'y', 'Ã¾'=>'b', 'Ã¿'=>'y', 'Æ’'=>'f',
		);
		$url = strtolower(strtr($name, $alphabet));
		$url = str_replace(' ', '-', $url);
		$this->database->sqlquery('INSERT INTO '.CONFIG::PREFIX.'_categorie (name,url) VALUES ("'.$this->database->secure($name).'","'.$this->database->secure($url).'")');
	}

}