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
		$this->unix = "Nicedit";
	}
	
	/**
	 * Install/Activation instructions 
	 */
	public function InstallStart(){
		
		//Makes the module active
		$this->createModuleActive();
		
		//Create the running Plug
		$this->addPlug("Editor", $this->getUnix());
	}
}

?>