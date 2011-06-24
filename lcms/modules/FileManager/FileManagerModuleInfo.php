<?php
/**
 * Menu Module Information
 */
class ModuleInfo extends Info{
	
	/**
	 * Setup system
	 */
	public function ModuleInfo(){
		
		//Setup Unix
		$this->unix = "FileManager";
		
		//Title setup
		$this->title = "LCMS FileManager";
		
		//Setup Author
		$this->author = "LotusCMS Integration Team";
		
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
}

?>