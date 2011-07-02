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
		$this->unix = "Contact";
		
		//Title setup
		$this->title = "LCMS Contact Form";
		
		//Setup Author
		$this->author = "Kevin Bluett - LotusCMS Core Team";
		
		//Support URL
		$this->support = "http://forum.lotuscms.org";
		
		//Version
		$this->version = "1.0";
		
		//Administration Area Exists
		$this->admin = true;
		
		//Overriding Anything
		$this->overrider = false;
		
		//Organisation Committing Development
		$this->organisation = "LotusCMS Core Development";
	}
}

?>