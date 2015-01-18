<?php Class GeneralController extends Controller{
	
	private $lang,$user,$auth;

	function __construct(){
		$this->lang = Controller::loading_controller('LangController');
		$this->user = Controller::loading_controller('UserController');
		$this->auth = Controller::loading_controller('AuthController');
	}

	// Transform DATTIME in good format
	public function getFullDate($d){
		$list = array('',$this->lang->i18n('site_month_01'),$this->lang->i18n('site_month_02'),$this->lang->i18n('site_month_03'),$this->lang->i18n('site_month_04'),$this->lang->i18n('site_month_05'),$this->lang->i18n('site_month_06'),$this->lang->i18n('site_month_07'),$this->lang->i18n('site_month_08'),$this->lang->i18n('site_month_09'),$this->lang->i18n('site_month_10'),$this->lang->i18n('site_month_11'),$this->lang->i18n('site_month_12'));

		$date = explode(' ',$d);
		$day = explode('-', $date[0]);
		$hour = explode(':', $date[1]);

		$month = ($day[1] < 10)? trim($day[1],0) : $day[1];
		return $day[2].' '.$list[$month].' '.$day[0].' '.$hour[0].'h '.$hour[1]; 
	}

	// generate content of aside
	public function getSideNavBar(){
		$in_home = (Dispatcher::whaturl() == 'index')? 'class="actived"' : '';
		$in_login = (Dispatcher::whaturl() == 'login')? 'class="actived"' : '';
		$in_register = (Dispatcher::whaturl() == 'register')? 'class="actived"' : '';
		$in_settings = (Dispatcher::whaturl() == 'settings')? 'class="actived"' : '';
		$in_notif = (Dispatcher::whaturl() == 'notification')? 'class="actived"' : '';
		$in_post = (Dispatcher::whaturl() == 'profile/'.$this->user->getActiveUser('name'))? 'class="actived"' : '';

		
		if(strpos(Dispatcher::whaturl(), 'admin' ) !== false && strpos(Dispatcher::whaturl(), 'category') === false){
			$html = '
			<li><a href="'.Dispatcher::base().'" title="'.$this->lang->i18n('site_back_member_area').'"><i class="fa fa-arrow-left"></i><span>'.$this->lang->i18n('site_back_member_area').'</span></a></li>
			<li><a href="'.Dispatcher::base().'admin/categories" title="'.$this->lang->i18n('site_categories').'"><i class="fa fa-tags"></i><span>'.$this->lang->i18n('site_categories').'</span></a></li>
			<li><a href="'.Dispatcher::base().'admin/users" title="'.$this->lang->i18n('site_members').'"><i class="fa fa-users"></i><span>'.$this->lang->i18n('site_members').'</span></a></li>
			<li><a href="'.Dispatcher::base().'admin/invite" title="'.$this->lang->i18n('site_invite_member').'"><i class="fa fa-child"></i><span>'.$this->lang->i18n('site_invite_member').'</span></a></li>
			<li><a href="'.Dispatcher::base().'admin/settings" title="'.$this->lang->i18n('site_settings').'"><i class="fa fa-cog"></i><span>'.$this->lang->i18n('site_settings').'</span></a></li>
			';
		}else{
			$html = '<li><a href="'.Dispatcher::base().'" title="'.$this->lang->i18n('site_home').'" '.$in_home.'><i class="fa fa-home"></i><span>'.$this->lang->i18n('site_home').'</span></a></li>';
			
			if(!$this->auth->isLoged()){
				$html .= '
				<li><a href="'.Dispatcher::base().'register" title="'.$this->lang->i18n('site_register').'" '.$in_register.'><i class="fa fa-pencil"></i><span>'.$this->lang->i18n('site_register').'</span></a></li>
				<li><a href="'.Dispatcher::base().'login" title="'.$this->lang->i18n('site_login').'" '.$in_login.'><i class="fa fa-sign-in"></i><span>'.$this->lang->i18n('site_login').'</span></a></li>
				';
			}else{
				$html .= '
				<li><a href="'.Dispatcher::base().'profile/'.$this->user->getActiveUser('name').'" title="Profil" '.$in_post.'><i class="fa fa-user"></i><span>'.$this->lang->i18n('site_myposts').'</span></a></li>
				<li><a href="'.Dispatcher::base().'notification" title="'.$this->lang->i18n('site_notification').'" '.$in_notif.'><i class="fa fa-tags"></i><span>'.$this->lang->i18n('site_notification').'</span></a></li>
				<li><a href="'.Dispatcher::base().'settings" title="'.$this->lang->i18n('site_settings').'" '.$in_settings.'><i class="fa fa-cog"></i><span>'.$this->lang->i18n('site_settings').'</span></a></li>
				<li><a href="'.Dispatcher::base().'logout" title="'.$this->lang->i18n('site_logout').'"><i class="fa fa-sign-out"></i><span>'.$this->lang->i18n('site_logout').'</span></a></li>
				';
				if($this->user->getActiveUser('type') == 'root'){
					$html .= '<li><a href="'.Dispatcher::base().'admin" title="'.$this->lang->i18n('site_dashboard').'"><i class="fa fa-dashboard"></i><span>'.$this->lang->i18n('site_dashboard').'</span></a></li>';
				}
			}
		}

		return $html;
	}
}