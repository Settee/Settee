<?php Class NotificationController extends Controller{

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
			$html .= '<article><div class="post"><div class="posthead"><div class="avatar"><img src="'.$user->getUserAvatar($v->origin_id).'" alt="Avatar"></div><div class="infos"><div class="name"><a href="/settee/profile/'.$user->getUserById($v->origin_id)->name.'" title="Posted by  '.$user->getUserById($v->origin_id)->surname.'" class="name">'.$user->getUserById($v->origin_id)->surname.'</a></div></div><div class="clearfloat"></div></div><div class="posttext">You have a new activity in post: <a href="'.Dispatcher::base().'share/'.$v->post_id.'">'.Dispatcher::base().'share/'.$v->post_id.'</a></div><div class="postfooter"><ul><li><a href="/settee/deletenotification/'.$v->id.'" title="Delete this post"><i class="fa fa-trash"></i><span>Delete</span></a></li></ul><div class="clearfloat"></div></div></div></article>';
		}

		return $html;
	}
}