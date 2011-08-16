<?php
include_once("core/lib/ModuleInfo.php");

/**
 * Menu Module Information
 */
class DashboardModuleInfo extends Info{
	
	/**
	 * Setup system
	 */
	public function ModuleInfo(){
		
		//Setup Unix
		$this->unix = "Dashboard";
		
		//Title setup
		$this->title = "LCMS jQuery Dashboard";
		
		//Setup Author
		$this->author = "LotusCMS Development Team";
		
		//Support URL
		$this->support = "http://forum.lotuscms.org";
		
		//Version
		$this->version = "1.1";
		
		//Administration Area Exists
		$this->admin = true;
		
		//Overriding Anything
		$this->overrider = false;
		
		//Organisation Committing Development
		$this->organisation = "LotusCMS Core Development Team";
	}
	
	/**
	 * Install/Activation instructions 
	 */
	public function InstallInfo(){
		//None
	}
}

?>