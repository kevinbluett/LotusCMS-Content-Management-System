<?php

//Default Controller
include("core/controller/controller.php");

class UsersListController extends Controller{
	
	/**
	 * Starts the controller of the classes system
	 */
	public function UsersListController($page){
		
		//Allow Plugins.
		Observable::Observable();
		
		//Setup basic variables
		$this->varSetup();
		
		//Sets the name of the other classes
		$this->setSystem("UsersList");
		
		//Setup the page
		$this->setup("Users");
		
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
			$this->getPaging()->setRedirect('?system=UsersList&page=list');	
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
			// Set the state and tell plugins.
			$this->setState('USERLIST_REQUEST');
			$this->notifyObservers();
			
			//Require Administrator Privs to see
			$this->getModel()->requireAdministrator();
			
			//Get the Page list data
			$data = $this->getModel()->getUsers();
			
			//Print the Page List
			$this->getView()->showUsersList($data);
		}
		
	}
}

?>