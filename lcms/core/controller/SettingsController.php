<?php
class SettingsController extends Controller{
	
	public function SettingsController($page){
		$this->setup("Settings","Settings");
	}
	
	protected function putRequests(){
		$requests = array("index");
		$this->setRequests($requests);
	}
	
	protected function indexRequest(){
		// Set the state and tell plugins.
		$this->setState('LOADING_SETTING_DASH');
		
		//Redirect to Dashboard
		$this->getView()->showSettingsDash();
	}
}

?>