<?php
class ModuleUpdateModel extends Model{
	
	public function ModuleUpdateModel(){
		Observable::Observable();
	}
	
	/**
	 * Returns the page requested by the system
	 */
	public function getActiveRequest(){
		return $this->getInputString("req", null, "G");	
	}
	
	/**
	 * Allows download and install
	 */
	public function downloadInstall(){
		$this->setState('DOWNLOADING_PLUGIN');
		
		//Get plugin name
		$plugin = $this->getActiveRequest();
		
		//Should never occur.
		if(empty($plugin)){
			exit("Plugin Undefined.");	
		}
		
		//Get remote files collector
		include_once("core/lib/RemoteFiles.php");
		
		//Create new remote file connector.
		$rf = new RemoteFiles();
		
		//Save this zip
		$rf->downloadRemoteFile("http://cdn.modules.lotuscms.org/lcms-3-series/zips/".$plugin.".zip", "data", $plugin.".zip");
		
		include_once('core/lib/pclzip.lib.php');
			
		//Setup Archive
		$archive = new PclZip("data/".$plugin.".zip");
		
		//Destroy existing plugin files if they exist.
		$this->destroyDir("modules/".$plugin, true);
			
		//Extract Archive
		$list  =  $archive->extract(PCLZIP_OPT_PATH, "modules");
			
		if ($archive->extract() == 0) {
			$this->setState('DOWNLOADING_PLUGIN_FAILED');
			
			unlink("data/".$plugin.".zip");
			
			//Display Error Message
			exit("<p><strong>Error</strong> : ".$archive->errorInfo(true)."</p><p>It may help to chmod (change write permissions) the 'modules' cms directory to 777.</p>");
		}else{
			$this->setState('DOWNLOADING_PLUGIN_SUCCESS');
			
			//If the original plugin folder is also there remove it
			if(is_dir($plugin)){
				
				//Destroys the secondary folder before copy.
				$this->destroyDir($plugin, true);	
				
				//Destroys the secondary folder before copy.
				$this->destroyDir("__MACOSX", true);
			}
			
			//Delete the temporary plugin zip file.
			unlink("data/".$plugin.".zip");
			
			return $plugin;
		}
	}
}

?>