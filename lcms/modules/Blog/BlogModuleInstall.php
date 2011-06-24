<?php
/**
 * Menu Module Information
 */
class ModuleInstall extends Install{
	
	/**
	 * Setup system
	 */
	public function ModuleInstall(){
		
		//Setup Unix
		$this->unix = "Blog";
	}
	
	/**
	 * Install/Activation instructions 
	 */
	public function InstallStart(){
		
		//Makes the module active
		$this->createModuleActive();
		
		//Include the unzipping systems.
		$this->extractFile();
		
		//Creates starter - i.e. content access for users outside of administration.
		$this->createStarter();
	}
	
	/**
	 * Tries to extract the default blog information into the data directory.
	 */
	public function extractFile(){
		
		include_once('modules/Backup/pclzip.lib.php');
			
		//Setup Archive
		$archive = new PclZip("modules/Blog/default_data_dir.zip");
			
		//Extract Archive
		$list  =  $archive->extract(PCLZIP_OPT_PATH, "data/modules");
			
		if ($archive->extract() == 0) {
			//Display Error Message
			exit("<p><strong>Error</strong> : ".$archive->errorInfo(true)."</p><p>It may help to chmod (change write permissions) the 'data' cms directory to 777.</p>");
		}else{
			
			//If the original plugin folder is also there remove it
			if(is_dir("Blog")){
				
				//Destroys the secondary folder before copy.
				$this->destroyDir("Blog", true);	
				
				//Destroys the secondary folder before copy.
				$this->destroyDir("__MACOSX", true);
				
				//Destroys the secondary folder before copy.
				$this->destroyDir("data/modules/__MACOSX", true);
			}
			
			return true;
		}
	}
}

?>