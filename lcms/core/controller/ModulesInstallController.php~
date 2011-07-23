<?php

//Default Controller
include("core/controller/controller.php");

class ModulesInstallController extends Controller{
	
	/**
	 * Starts the controller of the classes system
	 */
	public function ModulesInstallController($page){
		
		//Allow Plugins.
		Observable::Observable();
		
		//Setup basic variables
		$this->varSetup();
		
		//Sets the name of the other classes
		$this->setSystem("ModulesInstall");
		
		//Setup the page
		$this->setup("Module Find &amp; Install");
		
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
					"find",
					"install",
					"activate",
					"installDownload"
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
	protected function findRequest(){
		
		//If the user is logged in
		if($this->getModel()->checkLogin(true))
		{
			//Get the installed module data
			$data = $this->getModel()->getFindInfo();
			
			//Show this data visually
			$this->getView()->showFindInfo($data);
		}
	}
	
	/**
	 * This system downloads and installs a selected plugin
	 */
	protected function installDownloadRequest(){
		
		$data = $this->getModel()->downloadInstall();
		
		$this->getView()->showInstallRedirect($data);
	}
	
	/**
	 * Plugin Activation Request.
	 */
	protected function activateRequest(){
		//If the user is logged in
		if($this->getModel()->checkLogin(true))
		{
			$data = $this->getModel()->activatePlugin();
			
			$this->getView()->showPluginActivation($data);	
		}
	}
}

?>