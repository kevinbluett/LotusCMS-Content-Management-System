<?php

include("core/view/view.php");
include("core/lib/table.php");

class ModulesInstallView extends View{
	
	
	/**
	 * Starts the controller of the classes system
	 */
	public function ModulesInstallView(){
		//Meta is usually not setup yet, so we manually do this before loading css file
		$this->meta = new Meta();
		
		//This class requires an extra css file
		$this->getMeta()->addExtra('<link href="core/fragments/css/admin.css" rel="stylesheet" type="text/css" />');
	}	
	
	/**
	 * Shows the find information downloaded from LotusCMS.org
	 */
	public function showFindInfo($data){
		$this->setContentTitle($this->localize("Find More Plugins"));
		
		$this->setContent($data);	
	}
	
	/**
	 * Shows the plugin activation redirect.
	 */	
	public function showPluginActivation($plugin){
		
			//Show success message on redirected to page
			$_SESSION['ERROR_TYPE'] = "success";
			$_SESSION['ERROR_MESSAGE'] = "'".$plugin."' Module successfully activated.";
			
			//Go Redirect
			$this->setRedirect("index.php?system=Modules&page=load&active=".$plugin);	
	}
	
	/**
	 * Redirects to the activation page after successful install.
	 */
	public function showInstallRedirect($plugin){
		
			//Go Redirect
			$this->setRedirect("index.php?system=ModulesInstall&page=activate&active=".$plugin);	
	}
	
	/**
	 * A function for the settings file that sets which tab is active + Localization of Tabs
	 */ 
	public function setTabActive($active = 1, $tabs){
		
		//Localization
		$tabs = str_replace("%CURRENTLY_ACTIVE_MODULES_LOCALE%", $this->localize("Currently Active Modules"), $tabs);
		$tabs = str_replace("%ALL_INSTALLED_MODULES_LOCALE%", $this->localize("All Installed Modules"), $tabs);
		$tabs = str_replace("%FIND_MORE_PLUGINS_LOCALE%", $this->localize("Find More Plugins"), $tabs);
		
		if($active==1){
			$tabs = str_replace("%ONE%", "active", $tabs);
		}else{
			$tabs = str_replace("%ONE%", "inactive", $tabs);
		}
		if($active==2){
			$tabs = str_replace("%TWO%", "active", $tabs);
		}else{
			$tabs = str_replace("%TWO%", "inactive", $tabs);
		}
		if($active==3){
			$tabs = str_replace("%THREE%", "active", $tabs);
		}else{
			$tabs = str_replace("%THREE%", "inactive", $tabs);
		}
		return $tabs;
	}
}

?>