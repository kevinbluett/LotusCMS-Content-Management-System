<?php
class ModulesModel extends Model{
	
	public function ModulesModel(){
		Observable::Observable();
	}
	

	/**
	 * Get the module information for one module.
	 */ 
	public function getModuleInformation($name = null){
		$this->setState('GETTING_MODULE_INFO');
		
		//Get the module request
		$module = $this->getActiveRequest();
		
		//If name given, use it instead of request
		if(!empty($name)){
			$module = $name;
		}
		
		//Include the Module Library.
		include_once("core/lib/ModuleInfo.php");
		
		//Include Module Information
		include_once("modules/".$module."/".$module."ModuleInfo.php");
		
		$mod = $module."ModuleInfo";
		
		//Create Module Information
		$m = new $mod();
		
		//Loading Module information
		$m->ModuleInfo();
		
		//Return Module Info
		return $m;
	}
	
	/**
	 * Gets plugins activated and installed.
	 */
	public function getPlugins(){
		$this->setState('LISTING_ALL_PLUGINS_START');
		
		//Gets all directory info inside modules i.e. activated and unactivated plugins
		$allPlugins = $this->listFiles("modules");
		
		//Gets all the activated plugins.
		$active = $this->listFiles("data/modules");
		
		//Output Array
		$data = array();
		
		//Loops through all available plugins.
		for($i = 0; $i < count($allPlugins); $i++){
			
			$activated = false;
			
			//Loops through all the active plugins.
			for($y = 0; $y < count($active); $y++){
			
				//If the plugin is activated
				if($active[$y]==$allPlugins[$i]){
					
					//Let it know that it is activated.
					$activated = true;	
				}	
			}
			
			//Check System Activation Allowance
			$noDisable = file_exists("modules/".$allPlugins[$i]."/noDisableStatus.dat");
			
			//Adds info of each plugin into output data.
			$data[$i] = array(
								$allPlugins[$i],
								$activated,
								$noDisable
							 );		
		}//End of data creation loop
		
		$this->setState('LISTING_ALL_PLUGINS_IN');
		
		//Returns all plugins with activity status.
		return $data;
	}
	
	/**
	 * Gets the administraion of the request
	 */ 
	public function getAdministration(){
		$this->setState('LOADING_MODULE_ADMIN');
		
		//Get in admin request
		$request = $this->getInputString("req", "", "G");
		
		//Get Module Request
		$module = $this->getActiveRequest();
		
		//Make sure the administraion Exists
		if(file_exists("modules/".$module."/".$module."ModuleAdmin.php"))
		{
			//Include Module Information
			include_once("core/lib/ModuleAdmin.php");
			include_once("modules/".$module."/".$module."ModuleAdmin.php");
			
			//Creates new module admin
			$m = new ModuleAdmin($this->getController());
			
			//Give the module administration it's request
			$m->setRequest($request);
			
			//Sets the controller properly.
			$m->setController($this->getController());
			
			//Process the given request
			$m->process();
			
			//Get the content created after processing
			return array($module, $m->getContent());
		}
		//Module Misreported Existance of Administration
		else
		{
			$this->setState('NO_MODULE_MODULE_ADMIN');
			//Report Error
			die("Error: No Module Administration Exists. Please report to developer and stop using this module until it is fixed.");
		}
	}
	
	/**
	 * Returns the contents of the page as an array
	 */
	public function getInstalledModules()
	{
		$this->setState('GET_ACTIVE_PLUGINS');	
		
		//Setup Array
		$data = array();
		
		//Get Modules
		$modules = $this->listFiles("data/modules");
    	
		//Go through each module
		for($i = 0; $i < count($modules); $i++)
		{
			//Copy into array
			$data[$i]['title'] = $modules[$i];
			
			//Copy no image into array
			$data[$i]['img'] = "style/comps/admin/img/module_noimg.png";
			
			//Check if the module has a logo
			if(file_exists("modules/".$modules[$i]."/logo.png"))
			{
				//Setup the image.
				$data[$i]['img'] = "modules/".$modules[$i]."/logo.png";
			}
		}
		
		$this->setState('GET_ACTIVE_PLUGINS_FINISHED');	
		
		//Return the collected data
		return $data;
	}
	
	/**
	 * Disables the module
	 */
	public function disableModule(){
		$this->setState('DISABLING_PLUGIN');
		
		$plugin = $this->getInputString("req", "", "G");
		
		//Failsafe protection, this SHOULD never happen.
		if(empty($plugin)){
			exit("Plugin Undefined.");	
		}
		
		//Backup all variable data before deleting.
		$this->backup();
		
		//Try 1 is data module directly, may not exist therefore true
		$try1 = true;
		
		//Check and destroy data directory of module.
		if(is_dir("data/modules/".$plugin)){
			//Data dir exists;
			$try1 = false;
			
			//Try to destroy module data directory.
			$try1 = $this->destroyDir("data/modules/".$plugin);
		}
		
		//If all of the previous has succeeded.
		if($try1){
			
			//Removes all possible remaining parts of this plugin
			$this->removeAllPlugs($plugin);

			$this->setState('SUCCESS_DISABLED');

			return true;
		}else{
			$this->setState('FAILED_DISABLED');
			
			return false;
		}

	}
	
	/**
	 * This function checks for an update for a particular module at the lotuscms website.
	 */
	public function checkForUpdate(){

		$this->setState('STARTING_UPDATE_CHECK');

		//Gets the module in question
		$req = $this->getInputString("req", "", "G");
		
		include("core/lib/RemoteFiles.php");
		include_once("core/lib/ModuleInfo.php");
		include("modules/".$req."/".$req."ModuleInfo.php");
		
		$classname = $req."ModuleInfo";
		
		$mod = new $classname();
	    	
		$rf = new RemoteFiles();
	    	
		$version = file_get_contents("data/config/site_version.dat");
		
		//Get version of Module
		$v = $mod->getVersion();
	    
	    	
		$data = $rf->getURL("http://cdn.modules.lotuscms.org/lcms-3-series/versioncontrol/versioncheck.php?module=$req&v=$version");
	    
		if(!empty($data)){
			if($data!=$v){
				return true;	
			}	
		}else{
			return false;	
		}
	}
	
	/**
	 * Calls a backup of the CMS before deleting all plugin data etc.
	 * This function requires existance of backup plugin
	 */
	public function backup(){
		$this->setState('BACKING_UP_CMS');
		
		if(is_dir("modules/Backup")){
			date_default_timezone_set("GMT");
			$name = 'modules/Backup/zips/archive'.date("c").'.zip';
			
			include_once('modules/Backup/pclzip.lib.php');
			  
			$archive = new PclZip($name);
			$v_list = $archive->add('data/');
		}
	}
	
	/**
	 * Completely removes a plugin from the CMS.
	 */
	public function uninstall(){
		$this->setState('UNINSTALLING_PLUGIN');
		
		$plugin = $this->getInputString("req", "", "G");
		
		//Failsafe protection, this SHOULD never happen.
		if(empty($plugin)){
			exit("Plugin Undefined.");	
		}
		
		//Backup all variable data before deleting.
		$this->backup();
		
		//Try 1 is data module directly, may not exist therefore true
		$try1 = true;
		
		//Try 2 is module main directory, it must exist therefore it is initially false.
		$try2 = false;
		
		//Check and destroy data directory of module.
		if(is_dir("data/modules/".$plugin)){
			//Data dir exists;
			$try1 = false;
			
			//Try to destroy module data directory.
			$try1 = $this->destroyDir("data/modules/".$plugin);
		}
		
		//Try to destroy the modules plugin directory.
		$try2 = $this->destroyDir("modules/".$plugin);
		
		//If all of the previous has succeeded.
		if($try1&&$try2){
			
			//Removes all possible remaining parts of this plugin
			$this->removeAllPlugs($plugin);

			$this->setState('SUCCESS_UNINSTALLED');
			
			//Success
			return true;
		}else{
			$this->setState('FAILED_UNINSTALL');
			
			//Failed
			return false;
		}
	}
	
	/**
	 * Checks an individual update exists
	 */
	public function checkModForUpdates($mod){
	 	
		include_once("core/lib/ModuleUpdateCheck.php");
		
		$muc = new ModuleUpdateCheck();
		
		//Get array of version information
		$vA = $muc->getVersionArray($this->listFiles("modules"));
		
		if(!empty($vA[$mod])){
			return $vA[$mod];
		}else{
			return false;	
		}
	}
	
	/**
	 * Removes the plugins from a defined plugin.
	 */
	protected function removeAllPlugs($plugin){
		$this->setState('REMOVE_ALL_PLUGS');

		//The Observing plugins.
		$plugged = $this->listFiles("data/config/Modules");
		
		//loop through each available plug
		for($i = 0; $i < count($plugged); $i++){
			
			//Checks if this plugin is activate in this state
			if($this->checkPlug(str_replace(".dat", "", $plugged[$i]), $plugin)){
				
				//Deletes the plugin from the observing state.	
				$this->removePlug(str_replace(".dat", "", $plugged[$i]), $plugin);
			}
		}
	}
	
	/**
	 * Checks for observable option in system
	 */
	public function checkPlug($system, $unix){
		
		if(file_exists("data/config/modules/".$system.".dat")){
			$data = $this->openFile("data/config/modules/".$system.".dat");
			
			$data = explode("|", $data);
			
			for($i = 0;$i < count($data); $i++){					
				
				if($data[$i]==$unix){
					return true;
				}	
			}
		}
		return false;
	}
	
	/**
	 * Checks for observable option in system
	 */
	public function addPlug($system, $unix){
		
		$on = $this->checkPlug($system, $unix);
		
		//If the check plug isn't on
		if(!$on){
			if(file_exists("data/config/modules/".$system.".dat")){
				$data = $this->openFile("data/config/modules/".$system.".dat");
				
				//Add name to total list
				$data = $data."|".$unix;
				
				//Save file
				$this->saveFile("data/config/modules/".$system.".dat", $data);
			}else{
				//Directly create file
				$this->saveFile("data/config/modules/".$system.".dat", $unix);
			}
		}
	}
	
	/**
	 * Removes Observing plug from system
	 */
	public function removePlug($system, $unix){
		
		$on = $this->checkPlug($system, $unix);
		
		//If the check plug is on.
		if($on){
			//Existing Plugs
			$data = $this->openFile("data/config/modules/".$system.".dat");
			
			//New String
			$out = "";
				
			for($i = 0; $i < count($data); $i++){
				
				//Avoids empty strings.
				if($i==0){
					if($data[$i]!=$unix){
						$out .= $data[$i];
					}
				}else{
					if($data[$i]!=$unix){
						$out .= "|".$data[$i];
					}
				}
			}
				
			//Save file
			$this->saveFile("data/config/modules/".$system.".dat", $out);
		}
	}
}

?>