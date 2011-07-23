<?php

//Default Controller
include("core/controller/controller.php");

class GeneralSettingsController extends Controller{
	
	/**
	 * Starts the controller of the classes system
	 */
	public function GeneralSettingsController($page){
		
		//Allow Plugins.
		Observable::Observable();
		
		//Setup basic variables
		$this->varSetup();
		
		//Sets the name of the other classes
		$this->setSystem("GeneralSettings");
		
		//Setup the page
		$this->setup("LotusCMS Settings");
		
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
					"edit"
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
	protected function editRequest(){
		
		//If the user is logged in
		if($this->getModel()->checkLogin(true))
		{
			//If no data has been submitted
			if($this->getModel()->getInputString("title", null, "P")==null)
			{
				// Set the state and tell plugins.
				$this->setState('CREATING_EDITOR');
				$this->notifyObservers();
		
				//Get the page content request
				$data = $this->getModel()->getWebsiteData();
				
				//Create an editor for data
				$this->getView()->createEditor($data);
			}
			//Data has been submitted to be processed
			else
			{
				// Set the state and tell plugins.
				$this->setState('SAVING_DETAILS');
				$this->notifyObservers();
				
				//Save data
				$this->getModel()->saveWebsiteData();
				
				//Redirect
				$this->getView()->setWebsiteRedirect();
			}
		}
	}
}

?>