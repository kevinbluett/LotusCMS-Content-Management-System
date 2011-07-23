<?php
class GeneralSettingsModel extends Model{
	
	/**
	 * Starts the controller of the classes system
	 */
	public function GeneralSettingsModel(){
		//Allow Plugins.
		Observable::Observable();
	}
	
	/**
	 * Returns the contents of the page as an array
	 */
	public function getWebsiteData()
	{
		$this->setState('LOADING_SITE_DATA');
		
		//Setup Array
		$data = array();
    	
		//Open the page file
		$data[0] = $this->openFile("data/config/site_version.dat");
		
		//Open the page file
		$data[1] = $this->openFile("data/config/site_title.dat");
		
		//Return the collected data
		return $data;
	}
	
	/**
	 * Gets and saves the data from the SEO form
	 */
	public function saveWebsitedata(){
		$this->setState('SAVING_SITE_DATA');
		
		//Get submitted data
		$data = $this->getWebsiteDataForm();
		
		//Save Site Description
		$this->saveFile("data/config/site_title.dat", $data[0]);
		
		//Save the locale details
		$this->saveFile("data/config/locale.dat", $data[1]);
	}	
	
	/**
	 * Gets the data submitted through SEO form in the administration panel.
	 */
	public function getWebsiteDataForm(){
		$this->setState('GETTING_FORM_DATA');
		
		$data = array();
		
		//Get website title
		$data[] = $this->getInputString("title", null, "P");
		
		//Get Locale text
		$loc = explode("[", $this->getInputString("locale", null, "P"));
		
		$data[] = str_replace("]", "", $loc[1]);
		
		//Return this data
		return $data;
	}
	
	/**
	 * Returns the active locale
	 * Kevin Bluett July 2011
	 */
	public function getListOfLocale(){
		
		$locales = $this->io->listFiles("core/lang/");
		
		$fullText = $locales;
		
		for($i = 0; $i < count($locales); $i++){
		    /* Static keyword is used to ensure the file is loaded only once */
		    $translations = NULL;
		    
		    $lang_file =  'core/lang/' . $locales[$i];
		    $lang_file_content = $this->openFile($lang_file);
		        
		    /* Load the language file as a JSON object and transform it into an associative array */
		    include_once("core/lib/JSON.php");
		    
		    $js = new Services_JSON(SERVICES_JSON_LOOSE_TYPE);
		    $translations = $js->decode($lang_file_content );
		    $fullText[$i] = $translations['full_lang'];
		}
		
		return array($locales, $fullText);
	}
}

?>