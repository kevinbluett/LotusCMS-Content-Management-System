<?php

//Default Controller
include("core/controller/controller.php");

class ModuleUpdateController extends Controller{
	
	/**
	 * Starts the controller of the classes system
	 */
	public function ModuleUpdateController($page){
		
		//Allow Plugins.
		Observable::Observable();
		
		//Setup basic variables
		$this->varSetup();
		
		//Sets the name of the other classes
		$this->setSystem("ModuleUpdate");
		
		//Setup the page
		$this->setup("Module Update");
		
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
					"update"
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
	 * This system downloads and installs a selected plugin
	 */
	protected function updateRequest(){
		
		$data = $this->getModel()->downloadInstall();
		
		$this->getView()->showInstallRedirect($data);
	}
}

?>