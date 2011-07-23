<?php
class ModulesController extends Controller{

	public function ModulesController($page){
		$this->setup("Modules","Module Manager");
	}
	
	protected function putRequests(){
		
		//Create the array of request
		$requests = array(
					"index",
					"admin",
					"load",
					"list",
					"updateCheck",
					"checkAllModules",
					"uninstall",
					"deactivate"
				);
		
		//Set all the request
		$this->setRequests($requests);
	}
	
	/**
	 * Editor Request to get a page.
	 */
	protected function indexRequest(){
		//Get the installed module data
		$data = $this->getModel()->getInstalledModules();
		
		//Show this data visually
		$this->getView()->showInstalledModules($data);
	}
	
	/**
	 * Deactivate Request for the module
	 */
	protected function deactivateRequest(){
		//Get the installed module data
		$data = $this->getModel()->disableModule();
		
		//Show this data visually
		$this->getView()->showDisableMessage($data);
	}
	
	/**
	 * Lists all the installed plugins - activated or not.
	 */
	protected function listRequest(){
		//Plugin.
		$data = $this->getModel()->getPlugins();
		
		//Shows all the installed and activated plugins.
		$this->getView()->showPlugins($data);
	}
	
	/**
	 * Editor Request to get a page.
	 */
	protected function loadRequest(){
		//Get Module Information if available
		$load = $this->getModel()->getModuleInformation();
		
		//Show this information
		$this->getView()->showModuleInformation($load);
	}
	
	/**
	 * Check for update on installed plugin
	 */
	protected function updateCheckRequest(){
		//Plugin.
		$data = $this->getModel()->checkForUpdate();
		
		//Shows all the installed and activated plugins.
		$this->getView()->updateMessage($data);
	}
	
	/**
	 * Checks for updates for every plugin
	 */
	protected function checkAllModulesRequest(){
		//Plugin.
		$data = $this->getModel()->checkAllModules();
		
		//Shows all the installed and activated plugins.
		$this->getView()->updateMessage($data);
	}
	
	/**
	 * Deletes a plugin from the CMS
	 */
	protected function uninstallRequest(){
		//Output from uninstall message.
		$out = $this->getModel()->uninstall();
		
		//Shows uninstall message
		$this->getView()->showUninstallMessage($out);
	}
	
	/**
	 * Editor Request to get a page.
	 */
	protected function adminRequest(){
		//Get Module Information if available
		$load = $this->getModel()->getAdministration();
		
		//Show this information
		$this->getView()->showModuleAdministration($load);
	}
}

?>