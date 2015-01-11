<?php Class NotificationController extends Controller{

	private $lang;

	function __construct(){
		$this->lang = Controller::loading_controller('LangController');
	}

	public function setNotification($msg,$type){
		$_SESSION['notification_popup'] = '<div class="m-'.$type.'"><span>'.$msg.'</span><div class=".clearfloat"></div></div>';
	}

	public function getNotification(){
		if(isset($_SESSION['notification_popup']) && !empty($_SESSION['notification_popup'])){
			$notif = $_SESSION['notification_popup'];
			unset($_SESSION['notification_popup']);
		}else{
			$notif = '';
		}
		return $notif;
	}

	public function getPersonalNotification(){
		$database = Controller::loading_controller('Database');
		$user = Controller::loading_controller('UserController');
		$notifications = $database->sqlquery('SELECT * FROM '.CONFIG::PREFIX.'_notification WHERE dest_id="'.$user->getUserById('id').'"','query');
		$html = '';

		foreach($notifications as $k => $v){
			$html .= '<article><div class="post"><div class="posthead"><div class="avatar"><img src="'.$user->getUserAvatar($v->origin_id).'" alt="Avatar"></div><div class="infos"><div class="name"><a href="/settee/profile/'.$user->getUserById($v->origin_id)->name.'" title="Posted by  '.$user->getUserById($v->origin_id)->surname.'" class="name">'.$user->getUserById($v->origin_id)->surname.'</a></div></div><div class="clearfloat"></div></div><div class="posttext">'.$this->lang->i18n('site_new_comment').' <a href="'.Dispatcher::base().'share/'.$v->post_id.'">'.$v->post_id.'</a></div><div class="postfooter"><ul><li><a href="'.Dispatcher::base().'deletenotification/'.$v->id.'" title="'.$this->lang->i18n('site_del_notification').'"><i class="fa fa-trash"></i><span>'.$this->lang->i18n('site_del_notification').'</span></a></li></ul><div class="clearfloat"></div></div></div></article>';
		}

		return $html;
	}
}