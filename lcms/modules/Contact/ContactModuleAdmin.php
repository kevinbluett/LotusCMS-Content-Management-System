<?php

/**
 * The Administration loader for the module
 */
class ModuleAdmin extends Admin{

	/**
	 * The Default setup function
	 */
	public function ModuleAdmin(){
		//Sets the unix name of the plugin
		$this->setUnix("Contact");	
	}

	/**
	 * Default Requestion
	 */
	public function defaultRequest(){
		
		$test = $this->getInputString("title", "", "P");
		
		if(empty($test)){
		
			$data = "<p class='msg success'>This LCMS module has been built by Kevin Bluett for <a href='redlemonade.ie'>Redlemonade.ie</a>.</p>";
			
			$page = $this->openFile("modules/Contact/fragments/email-settings.phtml");
			
			//Get Settings
			$settings = $this->getSettings();
			
			$page = str_replace("%EMAIL%", $settings[0], $page);
			$page = str_replace("%SUBJECT%", $settings[1], $page);
			$page = str_replace("%TITLE%", $settings[2], $page);
			
			$this->setContent($data.$page);
		}else{
			
			//Get save settings
			$title = $this->getInputString("title", "", "P");
			$subject = $this->getInputString("subject", "", "P");	
			$email = $this->getInputString("email", "", "P");
			
			//Save these settings to file
			$save = array(
							str_replace("|","", $email),
							str_replace("|","", $subject),
							str_replace("|","", $title)
						 );
			$this->saveSettings($save);
			
			$this->setContent("<p class='msg success'>Success saving contact details.</p>");
		}
	}
		
	/**
	 * Gets saved shop settings
	 */
	public function getSettings(){
		
		//Opens the shop data file
		$data = $this->openFile("data/modules/Contact/settings.dat");
		
		//Converts into array usable by this system.
		$data = explode("||", $data);
		
		return $data;
	}
	
	/**
	 * Saves Settings
	 */
	public function saveSettings( $data){
		
		//Implodes into one string
		$toSave = implode("||",  $data);
		
		//Saves this into file
		$this->saveFile("data/modules/Contact/settings.dat", $toSave);
	}
	
}

?>