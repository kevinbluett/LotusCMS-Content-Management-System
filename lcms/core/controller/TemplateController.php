<?php

//Default Controller
include("core/controller/controller.php");

class TemplateController extends Controller{
	
	/**
	 * Starts the controller of the classes system
	 */
	public function TemplateController($page){
		
		//Allow Plugins.
		Observable::Observable();
		
		//Setup basic variables
		$this->varSetup();
		
		//Sets the name of the other classes
		$this->setSystem("Template");
		
		//Setup the page
		$this->setup("Change Your Template");
		
		//Set to Administration template
		$this->getView()->setTemplate("admin");
		
		//Check for Plugins
		$this->loadPlugins();
		
		//Set the requests accepted
		$this->putRequests();
		
		//Process Request
		$this->processRequest();
		
		//Render the page
		$this->displayPage();
	}
	
	/**
	 * Sets the requests of the system
	 */
	protected function putRequests(){
		
		//Create the array of request
		$requests = array(
					"change",
					"install",
					"getTemplates"
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
	protected function getTemplatesRequest(){
		
		//If the user is logged in
		if($this->getModel()->checkLogin(true))
		{
			// Set the state and tell plugins.
			$this->setState('GETTING_TEMPLATES_LIST');
			$this->notifyObservers();
			
			//Gets template data
			$data = $this->getModel()->getFeaturedTemplates();
			
			//Show data
			$this->getView()->showInstallableTemplates($data);
		}
	}
	
	/** 
	 * Downloads and installs a template.
	 */
	protected function installRequest(){
		//If the user is logged in
		if($this->getModel()->checkLogin(true))
		{
			// Set the state and tell plugins.
			$this->setState('INSTALLING_TEMPLATE');
			$this->notifyObservers();
			
			//Gets template data
			$data = $this->getModel()->getAndInstall();
			
			//Show data
			$this->getView()->showMessage($data);
		}
	}
	
	/**
	 * Editor Request to get a page.
	 */
	protected function changeRequest(){
		
		//If the user is logged in
		if($this->getModel()->checkLogin(true))
		{
			// Set the state and tell plugins.
			$this->setState('CHANGE_REQUEST');
			$this->notifyObservers();
			
			//If no data has been submitted
			if($this->getModel()->getInputString("template", null, "P")==null)
			{
				//Get the page content request
				$active = $this->getModel()->getCurrentTemplate();
				
				//Get all available templates
				$all = $this->getModel()->getAllTemplates();
				
				//Create an editor for data
				$this->getView()->createTable($active, $all);
			}
			//Data has been submitted to be processed
			else
			{
				//Save data
				$this->getModel()->saveTemplateData();
				
				//Redirect
				$this->getView()->setTemplateRedirect();
			}
		}
	}
}

?>