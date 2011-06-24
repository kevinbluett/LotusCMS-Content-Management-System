<?php
include("core/model/model.php");

class EditorModel extends Model{
	
	public $t;
	
	/**
	 * Starts the controller of the classes system
	 */
	public function EditorModel(){
		
		//Allow Plugins to join in.
		Observable::Observable();
	}
	
	/**
	 * Creates a new page
	 */
	public function createNewPage($data)
	{
		// Set the state and tell plugins.
		$this->setState('STARTING_NEWPAGE_SAVING');
		$this->notifyObservers();
		
		//Set unix pagename as full pagename.
		$data[0] = $data[1];
		
		//Remove double spacing
		$data[0] = str_replace("  ", " ", $data[0]);
		
		//Remove Triple spacing
		$data[0] = str_replace("   ", " ", $data[0]);
		
		//Change remaining spaces to dashes
		$data[0] = str_replace(" ", "-", $data[0]);
		
		//If the unix string was inputted (Hidden by javascript in form)
		if($this->getInputString("unix", null, "P")!=null)
		{
			//Get input string
			$data[0] = $this->getInputString("unix", null, "P");
		}
		//Otherwise convert the existing string
		else
		{
			//Convert into a usable page name
			$data[0] = $this->fixPageName($data[0]);	
		}
		
		//Save the page.
		$this->savePage($data[0], $data[1], $data[2], $data[3]);
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
	public function getPageData($page)
	{
		// Set the state and tell plugins.
		$this->setState('GETTING_PAGEDATA');
		$this->notifyObservers();
		
		//Open the page file
		$data = $this->openFile("data/pages/".$page.".dat");
		
		//Explode the available data
		$data = explode("|<*div*>|",$data);
		
		//Return the collected data
		return $data;
	}
    
	/**
	* Retrieves the template options and converts them to a displayable format.
	*/ 
	public function getTemplateOptions($current = ""){
		
		// Set the state and tell plugins.
		$this->setState('GETTING_TEMPLATEOPTIONS');
		$this->notifyObservers();
		
		//Avialable templates
		$data = $this->getAllTemplates();
		
		$out = "";
		
		//If the current template isn't Default
		if($current!="Default"){
			//Output string
			$out = "<option>Default</option>";
		}
		
		//Create Selectable options out of available tempate options.
		for($i = 0; $i < count($data); $i++)
		{
			if($current!=$data[$i])
			{
				$out .= "<option>".$data[$i]."</option>";
			}
		}
		return $out;
	}

	/**
	 * Gets all available templates from the template folder.
	 */
	public function getAllTemplates(){
	
		//The directory containing the pages.
		$dir = "style";
		
		//Lists the pages in a directory
		$temps = $this->listFiles($dir);
		
		
		//Loops through all page listings to remove the extension of .dat
		for($i = 0; $i < count($temps); $i++)
		{
			//Removes the .dat from an item in the array
			$temps[$i] = str_replace(".php", "", $temps[$i]);
		}
		
		return $temps;		
	}
    
    /**
     * Deletes a page
     */
    public function deletePage(){
    	
	// Set the state and tell plugins.
	$this->setState('DELETING_PAGE');
	$this->notifyObservers();
	
    	//Active Request
    	$active = $this->getActiveRequest();
    	
    	//Delete the page
    	if(file_exists("data/pages/".$active.".dat"))
    	{
    		//Actually delete the file or die due to permissions
    		unlink("data/pages/".$active.".dat") or die($this->openFile("core/fragments/errors/error32.phtml"));
    	}
    	
    	//Delete cached page if cached
    	if(file_exists("cache/".$active.".html"))
    	{
    		//Actually delete the file or die due to permissions
    		unlink("cache/".$active.".html") or die($this->openFile("core/fragments/errors/error33.phtml"));
    	}
    }
    
    /**
     * Saves the contents of a page
     */
    public function savePage($unix, $title, $template, $content)
    {
		// Set the state and tell plugins.
		$this->setState('SAVING_PAGE');
		$this->notifyObservers();
		
		$content = str_replace('\"','"', $content);
		$content = str_replace("\'","'", $content);
	
    	//Deletes the cached version of the page.
    	$this->deleteCached($unix);
    	
    	//The file to save a page
    	$data = $title."|<*div*>|".$template."|<*div*>|".$content;
    	
    	//Save the edited page.
    	$this->saveFile(
    					$this->getController()->getPageDirectory()."/".$unix.".dat", 
    					$this->utf8_urldecode($data), 
    					$this->openFile("core/fragments/errors/error31.phtml")
    				   );
    }
    
    /**
     * Fixes escaped content
     */ 
    public function utf8_urldecode($str) {
	    $str = preg_replace("/%u([0-9a-f]{3,4})/i","&#x\\1;",urldecode($str));
	    return html_entity_decode($str,null,'UTF-8');;
    }
    
    /**
     * Fixes any textareas in the page content
     */
    public function fixTags($data){
    	
    	//Replace the < tag for text-areas to stop incorrect rendering of editor
    	$data[1] = str_replace("</textarea>","&lt;/textarea>", $data[1]);
    	
    	//Returns the fixed data
    	return $data;
    }
    
    /**
     * Removes Unallowed page characters - to allowed unix type filename
     */
    public function fixPageName($text){
    	
    	//Special Characters to be removed
    	$sp = array(
    						" ",
    						"--",
    						"!",
    						"'",
    						'"',
    						"<",
    						"\\",
    						"/",
    						"?",
    						"*",
    						"&",
    						"%",
    						";",
    						":",
    						"~"
    					);
    	
    	//loop through specified characters
    	for($i = 0; $i < count($sp); $i++)
    	{
    		//Replace Disallowed character with empty string
    		$text = str_replace($sp[$i], "", $text);	
    	}
    	
    	//Change the text to lowercase
    	$text = strtolower($text);
    	
    	//Return string
    	return $text;
    }
    
    /**
     *
     */
    public function getSubmitData(){
	
	// Set the state and tell plugins.
	$this->setState('GETTING_SUBMISSION_DATA');
	$this->notifyObservers();
    	
    	$data = array();
    	
    	//Gets the unix name of page (GET)
    	$data[0] = $this->getInputString("active", null);
    	
    	//Gets the submited title (POST)
    	$data[1] = $this->getInputString("title");

	//Gets the Template (POST)
    	$data[2] = $this->getInputString("template");
	
    	//Gets the page data (POST)
    	$data[3] = $this->getInputString("pagedata");
    	
    	//Returns the data array
    	return $data;	
    }
    
    /**
     * Deletes a certain cached page is it is cached, may it be
     */
    protected function deleteCached($page)
    {
	
	// Set the state and tell plugins.
	$this->setState('DELETING_CACHED_PAGE');
	$this->notifyObservers();
	
    	//Check if cached version exists
    	if(file_exists("cache/".$page.".html"))
    	{
    		//Delete cached page or show uncaching error.
    		unlink("cache/".$page.".html") or die($this->openFile("core/fragments/errors/error29.phtml"));	
    	}
    }
    
	/**
	 * Checks page input values to make sure that they are not empty.
	 * NOTE: JAVASCRIPT CHECKS VALUES BEFORE SENDING, this is only a low level failsafe for browsers with no javascript
	 * 		 and hacking attempts.
	 */
	public function checkPageValues($data){
	    
	    //If the page title is empty
	    if(empty($data[1]))
	    {
		    //Error 23 - missing page title
		    print $this->openFile("core/fragments/errors/error23.phtml");
		    
		    //Stop Everything
		    $this->getController()->freeze_all();
	    }
	    //If the pagedata is empty
	    else if(empty($data[3]))
	    {
		    //Error 24 - missing page content
		    print $this->openFile("core/fragments/errors/error24.phtml");
		    
		    //Stop Everything
		    $this->getController()->freeze_all();
	    }
	}
    
	/**
	 * Clears all the page files in the cache.
	 */
	public function clearCache(){
		
		// Set the state and tell plugins.
		$this->setState('EMPTYING_CACHE');
		$this->notifyObservers();
		
		//Include the file operations class
		include("core/lib/FileOperations.php");
		
		//Start up file operations class
		$fop = new FileOperations();
		
		//Let user know about the clearing cache title
		$this->getController()->getPaging()->setContentTitle("Clearing Page Cache");
		
		//Clear the contents of the cache - and show progress.
		$this->getController()->getPaging()->setContent($fop->emptyDir("cache"));
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
			do{$fd=fopen($m,"w+") or die("Save file failed - ".$m." - ".$o);$fout=fwrite($fd,$n);
			fclose($fd);$p++;}while(filesize($m)<5&&$p<5);
		}
		else
		{
			//Print Out of Space Error Message
			die($this->openFile("core/fragments/errors/error22.phtml"));	
		}
    }
    
	/**
	 * Returns a list of all the files in a specified directory (Not Recursive) - excluding confirguration files and 'index.php'.
	 */
	protected function listFiles($start_dir)
	{
		
		/*
		returns an array of files in $start_dir (not recursive)
		*/
			
		$files = array();
		$dir = opendir($start_dir);
		while(($myfile = readdir($dir)) !== false)
			{
			if($myfile != '.' && $myfile != '..' && !is_file($myfile) && $myfile != 'resource.frk' && $myfile != 'comps' && $myfile != 'index.php' && $myfile != 'admin.php' && !eregi('^Icon',$myfile) )
				{
				$files[] = $myfile;
				}
			}
		closedir($dir);
		return $files;
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