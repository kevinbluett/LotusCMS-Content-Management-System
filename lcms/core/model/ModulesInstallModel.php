<?php
class ModulesInstallModel extends Model{

	public function ModulesInstallModel(){
		Observable::Observable();
	}
	
	/**
	 * Gets the plugin choosing page.
	 */
	public function getFindInfo(){
		
		//Get the form
		$out = $this->openFile("core/fragments/listTop.phtml");
		
		$out = $this->getController()->getView()->setTabActive(3, $out);
		
		//Get remote files collector
		include_once("core/lib/RemoteFiles.php");
		
		//Create new remote file connector.
		$rf = new RemoteFiles();
		
		$id = $this->getInputString("id", "1", "G");
		
		//Get LCMS Version
		$version = $this->openFile("data/config/site_version.dat");
		
		$out1 = $rf->getURL("http://cdn.modules.lotuscms.org/lcms-3-series/infoloader/modules.php?id=".$id."&lang=".$this->getController()->getView()->getLocale()."&v=".$version."&m=".$this->getModuleArray());
		
		if(empty($out1)){
			exit("<br /><br /><strong>Data retrieval failed</strong> - LotusCMS probably unavailable, please try again later.");	
		}
		
		return $out.$out1;
	}
	
	/**
	 * Generates Mod array
	 */
	public function getModuleArray(){
		$ap = $this->listFiles("modules");
		
		$req = "";
			
		for($i = 0;$i < count($ap); $i++){
			
			if($i!=0)
				$req .= "|";
				
			$req .= $ap[$i];
		}
		
		//Return the module update
		return $req;
	}
	
	/**
	 * Activates a plugin
	 */
	public function activatePlugin(){
		$this->setState('ACTIVATING_PLUGIN');
			
		//Gets plugin under activation request
		$plugin = $this->getActiveRequest();
		
		if(!empty($plugin)){
			
			//Include installation packages for activation
			include("core/lib/ModuleInstall.php");
			
			if(file_exists("modules/".$plugin."/".$plugin."ModuleInstall.php")){
				include("modules/".$plugin."/".$plugin."ModuleInstall.php");
			}else{
				exit("Missing ModuleInstaller class in the module. Please notify module developer.");	
			}
				
			//Start the module installer class
			$ins = new ModuleInstall();
			
			//Starts the activation of the plugin
			$ins->InstallStart();
			
			$this->setState('ACTIVATING_PLUGIN_COMPLETE');
			
			//Allows to return to the plugin information of the activated. plugin.
			return $plugin;
		}else{
			$this->setState('PLUGIN_UNDEFINED');
		
			exit("Plugin Undefined.");	
		}
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
		
		//Get LCMS Version to select repository
		$version = $this->openFile("data/config/site_version.dat");
		
		//Explode the version number
		$v = explode(".", $version);
		
		//Create subversion number
		$version = $v[0].".".$v[1];
		
		
		//Save this zip
		$rf->downloadRemoteFile("http://cdn.modules.lotuscms.org/lcms-3-series/zips/$version/$plugin.zip", "data", "$plugin.zip");
		
		include_once('modules/Backup/pclzip.lib.php');
			
		//Setup Archive
		$archive = new PclZip("data/".$plugin.".zip");
			
		//Extract Archive
		$list  =  $archive->extract(PCLZIP_OPT_PATH, "modules");
			
		if ($archive->extract() == 0) {
			$this->setState('DOWNLOADING_PLUGIN_FAILED');
			
			unlink("data/".$plugin.".zip");
			
			//Display Error Message
			exit("<p><strong>Error</strong> : ".$archive->errorInfo(true)."</p><p>It may help to chmod (change write permissions) the 'modules' cms directory to 777.</p>");
		}else{
			$this->setState('DOWNLOADING_PLUGIN_SUCCESS');
			
			//Delete session varaibles detailing the info
			unset($_SESSION['MOD_LCMS_ORG_RESPONSE']);
			unset($_SESSION['MOD_LCMS_ORG_RESPONSE_URL']);
			
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