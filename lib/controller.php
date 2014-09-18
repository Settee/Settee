<?php Class Controller{
	
	static function privacy(){
		$return = '-1';
		if(Config::PRIVACY == 'public'){
			$return = '0';
		}
		if(Config::PRIVACY == 'publicregistration'){
			$return = '1';
		}
		if(Config::PRIVACY == 'privateadminvalitation'){
			$return = '2';
		}
		if(Config::PRIVACY == 'privatememberinvite'){
			$return = '3';
		}
		if(Config::PRIVACY == 'privateadmininvite'){
			$return = '4';
		}

		return $return;
	}
}