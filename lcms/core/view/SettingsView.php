<?php

include("core/view/view.php");
include("core/lib/table.php");

class SettingsView extends View{
	
	
	/**
	 * Starts the controller of the classes system
	 */
	public function SettingsView(){
		//Meta is usually not setup yet, so we manually do this before loading css file
		$this->meta = new Meta();
		
		//This class requires an extra css file
		$this->getMeta()->addExtra('<link href="core/fragments/css/admin.css" rel="stylesheet" type="text/css" />');	
	}	
	
	public function showSettingsDash(){
	
		// Set the state and tell plugins.
		$this->setState('SHOW_SETTINGS_DASH');
		$this->notifyObservers();
	
		//Redirect to General Settings
		$this->setRedirect("index.php?system=GeneralSettings&page=edit");
	}
}

?>