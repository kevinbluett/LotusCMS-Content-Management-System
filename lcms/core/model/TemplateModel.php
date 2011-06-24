<?php

include("core/model/model.php");

class TemplateModel extends Model{
	
	public $t;
	
	/**
	 * Starts the controller of the classes system
	 */
	public function TemplateModel(){
		
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
	 * Gets a template and installs it.
	 */
	public function getAndInstall(){
		
		// Set the state and tell plugins.
		$this->setState('DOWNLOADING_TEMPLATE');
		$this->notifyObservers();
		
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
			
			// Set the state and tell plugins.
			$this->setState('DOWNLOADING_TEMPLATE_FAILED');
			$this->notifyObservers();
			
			unlink("data/".$template.".zip");
			
			//Display Error Message
			exit("<p><strong>Error</strong> : ".$archive->errorInfo(true)."</p><p>It may help to chmod (change write permissions) the 'modules' cms directory to 777.</p>");
		}else{
			// Set the state and tell plugins.
			$this->setState('DOWNLOADING_PLUGIN_SUCCESS');
			$this->notifyObservers();
			
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
		
		$out1 = $rf->getURL("http://styles.lotuscms.org/lcms-3-styles/infoloader.php?id=".$id);
		
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
		// Set the state and tell plugins.
		$this->setState('GETTING_ACTIVE_TEMPLATE');
		$this->notifyObservers();
		
		//Open the page file
		$data = $this->openFile("data/config/active_design.dat");

		//Return the collected data
		return $data;
	}
	
	/**
	 * Gets all available templates from the template folder.
	 */
	public function getAllTemplates(){
		
		// Set the state and tell plugins.
		$this->setState('GETTING_ALL_TEMPLATES');
		$this->notifyObservers();
	
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
		
		// Set the state and tell plugins.
		$this->setState('SAVING_TEMPLATE_DATA');
		$this->notifyObservers();
		
		//Get submitted data
		$data = $this->getTemplateDataForm();
		
		//Clear Cache
		$this->clearCache();
		
		//Save Keywords
		$this->saveFile("data/config/active_design.dat", $data);
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
		
		//Clear the contents of the cache - and show progress.
		$out = $fop->emptyDir("cache");
	}
	
	/**
	 * Gets the selected template
	 */
	protected function getTemplateDataForm(){
		// Set the state and tell plugins.
		$this->setState('GETTING_SUBMITTED_TEMPLATE');
		$this->notifyObservers();
		
		//Returns the required posted string
		return $this->getInputString("template", null, "P");
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
     * Destorys directory
     */
   	public function destroyDir($dir, $virtual = false)
	{
		$ds = DIRECTORY_SEPARATOR;
		$dir = $virtual ? realpath($dir) : $dir;
		$dir = substr($dir, -1) == $ds ? substr($dir, 0, -1) : $dir;
		if (is_dir($dir) && $handle = opendir($dir))
		{
			while ($file = readdir($handle))
			{
				if ($file == '.' || $file == '..')
				{
					continue;
				}
				elseif (is_dir($dir.$ds.$file))
				{
					$this->destroyDir($dir.$ds.$file);
				}
				else
				{
					unlink($dir.$ds.$file);
				}
			}
			closedir($handle);
			rmdir($dir);
			return true;
		}
		else
		{
			return false;
		}
	}
}

?>