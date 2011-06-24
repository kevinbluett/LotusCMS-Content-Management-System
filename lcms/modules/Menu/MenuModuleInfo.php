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
		$this->unix = "Menu";
		
		//Title setup
		$this->title = "LCMS Menu Editor";
		
		//Setup Author
		$this->author = "LotusCMS Development Team";
		
		//Support URL
		$this->support = "http://forum.lotuscms.org";
		
		//Version
		$this->version = "1.4";
		
		//Administration Area
		$this->admin = true;
		
		//Overriding Anything
		$this->overrider = false;
		
		//Organisation Committing Development
		$this->organisation = "LotusCMS Core Development Team";
	}
}

?>