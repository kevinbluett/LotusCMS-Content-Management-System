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
		$this->unix = "FileManager";
	}
	
	/**
	 * Install/Activation instructions 
	 */
	public function InstallStart(){
		
		//Makes the module active
		$this->createModuleActive();
	}
}

?>