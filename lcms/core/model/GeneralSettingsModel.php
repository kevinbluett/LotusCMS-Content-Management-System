<?php

include("core/model/model.php");

class GeneralSettingsModel extends Model{
	
	public $t;
	
	/**
	 * Starts the controller of the classes system
	 */
	public function GeneralSettingsModel(){
		//Allow Plugins.
		Observable::Observable();
	}
	
	/**
	 * Returns the page requested by the system
	 */
	public function getActiveRequest(){
		return $this->getInputString("active", null, "G");	
	}
	
	/**
	 * Returns the contents of the page as an array
	 */
	public function getWebsiteData()
	{
		// Set the state and tell plugins.
		$this->setState('LOADING_SITE_DATA');
		$this->notifyObservers();
		
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
		
		// Set the state and tell plugins.
		$this->setState('SAVING_SITE_DATA');
		$this->notifyObservers();
		
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
		
		// Set the state and tell plugins.
		$this->setState('GETTING_FORM_DATA');
		$this->notifyObservers();
		
		//Create Array for the data
		$data = array();
		
		//Get website title
		$data[] = $this->getInputString("title", null, "P");
		
		//Get Locale text
		$loc = explode("[", $this->getInputString("locale", null, "P"));
		
		//G
		$data[] = str_replace("]", "", $loc[1]);
		
		//Return this data
		return $data;
	}
	
	/**
	 * Returns the active locale
	 * Kevin Bluett July 2011
	 */
	public function getListOfLocale(){
		include_once("core/lib/io.php");
		
		//Setup the Input Output system
		$io = new InputOutput();
		
		$locales = $io->listFiles("core/lang/");
		
		$fullText = $locales;
		
		for($i = 0; $i < count($locales); $i++){
		    /* Static keyword is used to ensure the file is loaded only once */
		    $translations = NULL;
		    
		    $lang_file =  'core/lang/' . $locales[$i];
		    $lang_file_content = file_get_contents($lang_file);
		        
		    /* Load the language file as a JSON object and transform it into an associative array */
		    include_once("core/lib/JSON.php");
		    
		    $js = new Services_JSON(SERVICES_JSON_LOOSE_TYPE);
		    $translations = $js->decode($lang_file_content );
		    $fullText[$i] = $translations['full_lang'];
		}
		
		return array($locales, $fullText);
	}
	
	/**
	 * Returns the active locale
	 * Kevin Bluett July 2011
	 */
	public function getActiveLocale(){
		return $this->getController()->getView()->getLocale();	
	}
	
	/**
	 * Save the set file, with the requested content.
	 * $m = file
	 * $n = file contents
	 * $o = Error message.
	 */
	protected function saveFile($m, $n, $o = 0){
    	
		//Save to disk if the space is available
		if($this->disk_space())
		{
			$n=trim($n);
			if($n==''){$n=' ';}$n=str_replace("\n\n","\n",$n);$p=0;
			do{$fd=fopen($m,"w+") or die($this->openFile("core/fragments/errors/error21.phtml")." - Via SEOModel.php");$fout=fwrite($fd,$n);
			fclose($fd);$p++;}while(filesize($m)<5&&$p<5);
		}
		else
		{
			//Print Out of Space Error Message
			die($this->openFile("core/fragments/errors/error22.phtml"));	
		}
	}
    
	/**
	 * Checks that there is enough space left to save the file on the harddisk.
	 */
	protected function disk_space(){
		$s = true;
		
		if(function_exists('disk_free_space'))
		{
			$a = disk_free_space("/");
			if(is_int($a)&&$a<204800)
			{
				$s = false;
			}
		}
		return $s;
	}
}

?>