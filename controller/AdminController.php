<?php Class AdminController extends Controller{

	private $update_name,$update_privacy;

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

}