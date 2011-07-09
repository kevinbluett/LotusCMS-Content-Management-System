<?php

//Locale loader for plugins.
include_once("core/lib/io.php");

class Locale extends InputOutput{
	
	public $custom;
	
	/**
	 * Starts the Locale System
	 */
	public function Locale(){
		
	}
	
	/**
	 * Gets the locale from the session information.
	 */
	public function getLocale(){
		
		$l = $this->getInputString("locale","","S");
		
		if(empty($l)){
			$l = $this->openFile("data/config/locale.dat");
			$_SESSION['locale'] = $l;
		}
		
		return $l;
	}
	
	/**
	 *
	 */
	public function setCustomLocaleFile($custom){
		$this->custom = $custom;
	}
	
	/**
	 *
	 */
	public function getCustomLocaleFile(){
		return $this->custom;
	}
	
	/**
	* Load the proper language file and return the translated phrase
	*
	* The language file is JSON encoded and returns an associative array
	* Language filename is determined by BCP 47 + RFC 4646
	* http://www.rfc-editor.org/rfc/bcp/bcp47.txt
	*
	* @param string $phrase The phrase that needs to be translated
	* @return string
	*/
	public function localize($phrase) {
	    /* Static keyword is used to ensure the file is loaded only once */
	    static $translations = NULL;
	    
	    /* If no instance of $translations has occured load the language file */
	    if (is_null($translations)) {
	    	$lang_file = "";
	    	$custom = $this->getCustomLocaleFile();
		    if(empty($custom)){
		        $lang_file =  'core/lang/' . $this->getLocale() . '.txt';
		        if (!file_exists($lang_file)) {
		            $lang_file = 'core/lang/' . 'en.txt';
		        }
		    }else{
		        $lang_file =  $this->getCustomLocaleFile() . $this->getLocale() . '.txt';
		        if (!file_exists($lang_file)) {
		            $lang_file = $this->getCustomLocaleFile() . 'en.txt';
		        }
		    }
		    
	        $lang_file_content = file_get_contents($lang_file);
	        
	        /* Load the language file as a JSON object and transform it into an associative array */
	        include_once("core/lib/JSON.php");
	        $js = new Services_JSON(SERVICES_JSON_LOOSE_TYPE);
	        $translations = $js->decode($lang_file_content );
	    }

	    if(!empty($translations[$phrase])){
	    	return $translations[$phrase];
	    }else{
	    	return $phrase;	
	    }
	}
}

?>