<?php

//Default Controller
include("core/controller/controller.php");

class ModulesController extends Controller{
	
	/**
	 * Starts the controller of the classes system
	 */
	public function ModulesController($page){
		
		//Allow Plugins.
		Observable::Observable();
		
		//Setup basic variables
		$this->varSetup();
		
		//Sets the name of the other classes
		$this->setSystem("Modules");
		
		//Setup the page
		$this->setup("Module Manager");
		
		//Set to Administration template
		$this->getView()->setTemplate("admin");
		
		//Check for Plugins
		$this->loadPlugins();
		
		//Set the requests accepted
		$this->putRequests();
		
		//Process Request
		$this->processRequest();
		
		$this->displayPage();
	}
	
	/**
	 * Sets the requests of the system
	 */
	protected function putRequests(){
		
		//Create the array of request
		$requests = array(
					"index",
					"admin",
					"load",
					"list",
					"updateCheck",
					"uninstall",
					"deactivate"
				);
		
		//Set all the request
		$this->setRequests($requests);
	}
	
	/**
	 * Show default classes
	 */
	protected function defaultRequest(){
		
		//Show 404
		$this->getPaging()->noPage();
	}
	
	/**
	 * Editor Request to get a page.
	 */
	protected function indexRequest(){
		
		//If the user is logged in
		if($this->getModel()->checkLogin(true))
		{
			//Get the installed module data
			$data = $this->getModel()->getInstalledModules();
			
			//Show this data visually
			$this->getView()->showInstalledModules($data);
		}
	}
	
	/**
	 * Deactivate Request for the module
	 */
	protected function deactivateRequest(){
		
		//If the user is logged in
		if($this->getModel()->checkLogin(true))
		{
			//Get the installed module data
			$data = $this->getModel()->disableModule();
			
			//Show this data visually
			$this->getView()->showDisableMessage($data);
		}
	}
	
	/**
	 * Lists all the installed plugins - activated or not.
	 */
	protected function listRequest(){
		//If the user is logged in
		if($this->getModel()->checkLogin(true))
		{
			//Plugin.
			$data = $this->getModel()->getPlugins();
			
			//Shows all the installed and activated plugins.
			$this->getView()->showPlugins($data);
		}
	}
	
	/**
	 * Editor Request to get a page.
	 */
	protected function loadRequest(){
		
		//If the user is logged in
		if($this->getModel()->checkLogin(true))
		{
			//Get Module Information if available
			$load = $this->getModel()->getModuleInformation();
			
			//Show this information
			$this->getView()->showModuleInformation($load);
		}
	}
	
	/**
	 * Check for update on installed plugin
	 */
	protected function updateCheckRequest(){
		//If the user is logged in
		if($this->getModel()->checkLogin(true))
		{
			//Plugin.
			$data = $this->getModel()->checkForUpdate();
			
			//Shows all the installed and activated plugins.
			$this->getView()->updateMessage($data);
		}
	}
	
	/**
	 * Deletes a plugin from the CMS
	 */
	protected function uninstallRequest(){
		
		//If the user is logged in of course
		if($this->getModel()->checkLogin(true))
		{
			//Output from uninstall message.
			$out = $this->getModel()->uninstall();
			
			//Shows uninstall message
			$this->getView()->showUninstallMessage($out);
		}
	}
	
	/**
	 * Editor Request to get a page.
	 */
	protected function adminRequest(){
		//If the user is logged in
		if($this->getModel()->checkLogin(true))
		{
			//Get Module Information if available
			$load = $this->getModel()->getAdministration();
			
			//Show this information
			$this->getView()->showModuleAdministration($load);
		}
	}
}

?>