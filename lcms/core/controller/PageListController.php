<?php

//Default Controller
include("core/controller/controller.php");

class PageListController extends Controller{
	
	/**
	 * Starts the controller of the classes system
	 */
	public function PageListController($page){
		
		//Allow Plugins.
		Observable::Observable();
		
		//Setup basic variables
		$this->varSetup();
		
		//Sets the name of the other classes
		$this->setSystem("PageList");
		
		//Setup the page
		$this->setup("Pages");
		
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
							"list"
						);
		
		//Set all the request
		$this->setRequests($requests);
	}
	
	/**
	 * Show default classes
	 */
	protected function defaultRequest(){
		
		//If the user is logged in
		if($this->getModel()->checkLogin(true))
		{
			//Redirect to Dashboard
			$this->getPaging()->setRedirect('?system=PageList&page=list');	
		}
		else
		{
			//Redirect to Login
			$this->getPaging()->setRedirect('?system=Admin&page=login');	
		}
	}
	
	/**
	 * Show default index
	 */
	protected function indexRequest(){
		
		//If the user is logged in
		if($this->getModel()->checkLogin(false))
		{
			
		}
		else
		{
			//Redirect to Login
			$this->getPaging()->setRedirect('?system=Admin&page=login');	
		}
	}

	/**
	 * Show default classes
	 */
	protected function listRequest(){
		
		//If the user is logged in
		if($this->getModel()->checkLogin(true))
		{
			//Get the Page list data
			$data = $this->getModel()->getPages();
			
			//Print the Page List
			$this->getView()->showPageList($data);
		}
		
	}
}

?>