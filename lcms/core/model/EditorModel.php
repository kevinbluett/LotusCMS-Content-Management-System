<?php
class EditorModel extends Model{

	public function EditorModel(){
		Observable::Observable();
	}
	
	/**
	 * Creates a new page
	 */
	public function createNewPage($data)
	{
		$this->setState('STARTING_NEWPAGE_SAVING');
		
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
	 * Returns the contents of the page as an array
	 */
	public function getPageData($page){
		$this->setState('GETTING_PAGEDATA');
		
		//Open the page file
		$data = $this->openFile("data/pages/".$page.".dat");
		
		//Explode the available data
		$data = explode("|<*div*>|",$data);

		//Ensure that the paging system is directly compatible with old page types
		if(count($data)==3){
			//Copy over page in 3 content
			$data[3] = $data[2];
			
			//Set published status to true.
			$data[2] = "true";
		}
		
		//Return the collected data
		return $data;
	}
    
	/**
	* Retrieves the template options and converts them to a displayable format.
	*/ 
	public function getTemplateOptions($current = ""){
		
		// Set the state and tell plugins.
		$this->setState('GETTING_TEMPLATEOPTIONS');

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
		$this->setState('DELETING_PAGE');
		
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
	public function savePage($unix, $title, $template, $published, $content)
	{
		$this->setState('SAVING_PAGE');
			
		$content = str_replace('\"','"', $content);
		$content = str_replace("\'","'", $content);
		
		//Caches this version of the page to reduce load times.
		$this->recache($unix);
		
		//The file to save a page
		$data = $title."|<*div*>|".$template."|<*div*>|".$published."|<*div*>|".$content;
		
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
    	$sp = array(" ", "--", "!", "'", '"', "<", "\\", "/", "?", "*", "&", "%", ";", ":", "~");
    	
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
    
    public function getSubmitData(){
		$this->setState('GETTING_SUBMISSION_DATA');
    	
    	$data = array(
    					'unix' 		=>  	$this->getInputString("active", null),
    					'title' 	=>	  	$this->getInputString("title", null),
    					'template' 	=>		$this->getInputString("template", null),
    					'content' 	=>		$this->getInputString("pagedata", null),
    					'published' =>		$this->getInputString("published", "true")
    				);
    	
    	//Returns the data array
    	return $data;	
    }
    
    /**
     * Deletes a certain cached page is it is cached, may it be
     */
    protected function recache($page){
		$this->setState('RECACHING_PAGE');

    	if(file_exists("cache/".$page.".html"))
    	{
    		unlink("cache/".$page.".html") or die($this->openFile("core/fragments/errors/error29.phtml"));	
    	}

		include("core/lib/cacher.php");	
		$cacher = new Cacher($page);
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
		    $this->getController()->freeze_all();
	    }
	    //If the pagedata is empty
	    else if(empty($data[3]))
	    {
		    //Error 24 - missing page content
		    print $this->openFile("core/fragments/errors/error24.phtml");
		    $this->getController()->freeze_all();
	    }
	}
    
	/**
	 * Clears all the page files in the cache.
	 */
	public function clearCache(){
		$this->setState('EMPTYING_CACHE');
		
		//Include the file operations class
		include("core/lib/FileOperations.php");
		
		//Start up file operations class
		$fop = new FileOperations();
		
		//Let user know about the clearing cache title
		$this->getController()->getPaging()->setContentTitle($this->getController()->getView()->localize("Clearing Page Cache"));
		
		//Clear the contents of the cache - and show progress.
		$this->getController()->getPaging()->setContent($fop->emptyDir("cache"));
	}
}

?>