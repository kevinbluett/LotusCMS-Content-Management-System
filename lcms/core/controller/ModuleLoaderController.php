<?php

//Default Controller
include("core/controller/controller.php");

class ModuleLoaderController extends Controller{
	
	//Module In which requests are processed.
	protected $module;
	protected $page;
	
	/**
	 * Starts the controller of the classes system
	 */
	public function ModuleLoaderController($page, $module){
		
		//Sets up the module to process requests.
		$this->setModule($module);
		$this->page = $page;
		
		//Setup basic variables
		$this->varSetup();
		
		//Sets the name of the other classes
		$this->setSystem("ModuleLoader");
		
		//Setup the page
		$this->setup("External Module");
		
		//Give module access to view model and controller
		$this->getModule()->setView($this->getView());
		$this->getModule()->setModel($this->getModel());
		$this->getModule()->setController($this);
				
		//Set the requests accepted
		$this->getModule()->putRequests();
		
		//Process Request
		$this->processModuleRequest();
		
		$this->displayPage();
	}
	
	/**
	 * Process the page request
	 */
	public function processModuleRequest(){

		$found = false;
		
		$req = $this->getModule()->getRequests();
		$reqy = $this->page;
		
		//If in wildcard mode process as wildcard override.
		if($this->wildCard){
			
			//Run Wildcard
			$this->getModule()->wildCardRequest();
			
			//Break from this function
			return false;
		}
		
		//Requests the default system
		if(empty($reqy))
		{
			//Run the default request
			$this->getModule()->defaultRequest();
			
			//This breaks processing of all the following requests
			return false;
		}
		
		//Work through all the set request
		for($i = 0;$i < count($req);$i++)
		{	
			//Process the request
			if($req[$i]==$reqy)
			{
				//Create Request
				$process = "\$this->getModule()->".($req[$i])."Request();";
				
				//Process the request
				eval($process);
				
				//Set processed
				$found = true;
				
				//Stop the loop
				break;
			}	
		}
		
		//Page not found
		if(!$found)
		{
			//Get the 404 page
			$this->getView()->noPage();
		}
	}
	
	/**
	 * Sets the module
	 */
	protected function setModule($mod){
		$this->module = $mod;	
	}
	
	/**
	 * Gets the local module variable
	 */
	protected function getModule(){
		return $this->module;	
	}
}

?>