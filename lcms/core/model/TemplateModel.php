<?php
class TemplateModel extends Model{
	
	public function TemplateModel(){
		Observable::Observable();
	}
	
	/**
	 * Destroys the files of a single template
	 */
	public function deleteTemplate(){
		$this->setState('DELETEING_TEMPLATE');
		
		$active = $this->getActiveRequest();
		
		//Step one - delete the main template file.
		unlink("style/$active.php");
		
		//Step two, if supporting folder exists, delete it.
		if(is_dir("style/comps/$active")){
			$this->destroyDir("style/comps/$active");
		}
	}
	
	/**
	 * Gets a template and installs it.
	 */
	public function getAndInstall(){
		$this->setState('DOWNLOADING_TEMPLATE');
		
		//Get plugin name
		$template = $this->getActiveRequest();
		
		//Should never occur.
		if(empty($template)){
			exit("Template Undefined.");	
		}
		
		//Get remote files collector
		include_once("core/lib/RemoteFiles.php");
		
		//Create new remote file connector.
		$rf = new RemoteFiles();
		
		
		//Save this zip
		$rf->downloadRemoteFile("http://styles.lotuscms.org/zips/".$template.".zip", "data", $template.".zip");
		
		include_once('modules/Backup/pclzip.lib.php');
			
		//Setup Archive
		$archive = new PclZip("data/".$template.".zip");
			
		//Extract Archive
		$list  =  $archive->extract(PCLZIP_OPT_PATH, "style");
			
		if ($archive->extract() == 0) {
			$this->setState('DOWNLOADING_TEMPLATE_FAILED');

			unlink("data/".$template.".zip");
			
			//Display Error Message
			exit("<p><strong>Error</strong> : ".$archive->errorInfo(true)."</p><p>It may help to chmod (change write permissions) the 'modules' cms directory to 777.</p>");
		}else{
			$this->setState('DOWNLOADING_PLUGIN_SUCCESS');
			
			//If the original plugin folder is also there remove it
			if(is_dir("comps")){
				
				//Destroys the secondary folder before copy.
				$this->destroyDir($template, true);	
				
				//Remove Additional Files
				$this->destroyDir("comps", true);	
				
				//Remove additional FIle
				unlink($template.".php");
				
				//Destroys the secondary folder before copy.
				$this->destroyDir("__MACOSX", true);
			}
			
			//Delete the temporary plugin zip file.
			unlink("data/".$template.".zip");
			
			return $template;
		}
	}
	
	/**
	 * Gets featured templates from lotuscms.org
	 */
	public function getFeaturedTemplates(){
		
		//Get ID
		$id = 1;
		
		//Get remote files collector
		include_once("core/lib/RemoteFiles.php");
		
		//Create new remote file connector.
		$rf = new RemoteFiles();
		
		$id = $this->getInputString("id", "1", "G");
		
		$version = $this->openFile("data/config/site_version.dat");
		
		$out1 = $rf->getURL("http://styles.lotuscms.org/lcms-3-styles/infoloader.php?id=".$id."&lang=".$this->getController()->getView()->getLocale()."&t=".$this->getAllTemplatesAsString()."&v=$version");
		
		if(empty($out1)){
			exit("<br /><br /><strong>Data retrieval failed</strong> - LotusCMS probably unavailable, please try again later.");	
		}
		
		return $out.$out1;
	}
	
	/**
	 * Returns the active Template
	 */
	public function getCurrentTemplate()
	{
		$this->setState('GETTING_ACTIVE_TEMPLATE');

		//Open the page file
		$data = $this->openFile("data/config/active_design.dat");

		//Return the collected data
		return $data;
	}
	
	/**
	 * Returns installed templates as string.
	 */
	public function getAllTemplatesAsString(){
		$temps = $this->getAllTemplates(); 
		
		$req = "";
			
		for($i = 0;$i < count($ap); $i++){
			
			if($i!=0)
				$req .= "|";
				
			$req . $ap[$i];
		}
		
		return $req;
	}
	
	/**
	 * Gets all available templates from the template folder.
	 */
	public function getAllTemplates(){
		$this->setState('GETTING_ALL_TEMPLATES');

		//The directory containing the pages.
		$dir = "style";
		
		//Lists the pages in a directory
		$temps = $this->listFiles($dir);
		
		//Create new array
		$templates = array();
		
		//Loops through all page listings to remove the extension of .dat
		for($i = 0; $i < count($temps); $i++)
		{
			//Removes the .dat from an item in the array
			$templates[$i]['unix'] = str_replace(".php", "", $temps[$i]);
			
			//Removes the .dat from an item in the array
			$templates[$i]['title'] = str_replace(".php", "", $temps[$i]);	
		}
		
		return $templates;		
	}
	
	/**
	 * Gets and saves the data from the SEO form
	 */
	public function saveTemplateData(){
		$this->setState('SAVING_TEMPLATE_DATA');
		
		//Get submitted data
		$data = $this->getTemplateDataForm();
		
		//Clear Cache
		$this->clearCache();
		
		//Save Keywords
		$this->saveFile("data/config/active_design.dat", $data);
	}
	
	/**
	 * Update Array
	 */
	public function getUpdateArray(){
		$a = $this->listFiles("style");
		
		include_once("core/lib/TemplateUpdateCheck.php");
		
		$t = new TemplateUpdateCheck();
		
		//Get version array
		return $t->getVersionArray($a);
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
		
		//Clear the contents of the cache - and show progress.
		$out = $fop->emptyDir("cache");
	}
	
	/**
	 * Gets the selected template
	 */
	protected function getTemplateDataForm(){
		$this->setState('GETTING_SUBMITTED_TEMPLATE');
		
		//Returns the required posted string
		return $this->getInputString("template", null, "P");
	}
}

?>