<?php

//Default Controller
include("core/controller/controller.php");

class SettingsController extends Controller{
	
	/**
	 * Starts the controller of the classes system
	 */
	public function SettingsController($page){
		
		//Allow Plugins.
		Observable::Observable();
		
		//Setup basic variables
		$this->varSetup();
		
		//Sets the name of the other classes
		$this->setSystem("Settings");
		
		//Setup the page
		$this->setup("General Settings");
		
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
							"index"
						);
		
		//Set all the request
		$this->setRequests($requests);
	}
	
	/**
	 * Show default classes
	 */
	protected function defaultRequest(){
		
		//Redirect to Dashboard
		$this->getPaging()->noPage();
	}
	
	/**
	 * Show default index
	 */
	protected function indexRequest(){
		
		//If the user is logged in
		if($this->getModel()->checkLogin(true))
		{
			// Set the state and tell plugins.
			$this->setState('LOADING_SETTING_DASH');
			$this->notifyObservers();
			
			//Redirect to Dashboard
			$this->getView()->showSettingsDash();
		}
	}
}

?>