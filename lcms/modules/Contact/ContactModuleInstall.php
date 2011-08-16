<?php
/**
 * Menu Module Information
 */
class ModuleInstall extends Install{
	
	/**
	 * Setup system
	 */
	public function ModuleInstall(){
		
		//Setup Unix
		$this->unix = "Contact";
	}
	
	/**
	 * Install/Activation instructions 
	 */
	public function InstallStart(){
		
		//Makes the module active
		$this->createModuleActive();
	
		//Creates starter - i.e. content access for users outside of administration.
		$this->createStarter();
		
		//Settings file
		if(!file_exists("data/modules/Contact/settings.dat")){
			//Save the menu_items file
			$this->saveFile("data/modules/Contact/settings.dat", "default@example.com||Subject||Page Title");
		}
	}
}

?>