<?php

//Default Controller
include("core/controller/controller.php");

class UsersController extends Controller{
	
	/**
	 * Starts the controller of the classes system
	 */
	public function UsersController($page){
		
		//Allow Plugins.
		Observable::Observable();
		
		//Setup basic variables
		$this->varSetup();
		
		//Sets the name of the other classes
		$this->setSystem("Users");
		
		//Setup the page
		$this->setup("Edit User");
		
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
							"create",
							"edit",
							"delete"
						);
		
		//Set all the request
		$this->setRequests($requests);
	}
	
	/**
	 * Show default classes
	 */
	protected function defaultRequest(){
		
		//Display 404
		$this->getPaging()->noPage();
	}
	
	/**
	 * Show create page
	 */
	protected function createRequest(){
		
		//If the user is logged in
		if($this->getModel()->checkLogin(true))
		{
			// Set the state and tell plugins.
			$this->setState('CREATING_REQUEST');
			$this->notifyObservers();
			
			//Check if isset
			$d = $this->getModel()->getInputString("email", null, "P");
			
			//If the form was submitted
			if(!empty($d))
			{
				//Save user
				$save = $this->getModel()->saveUser(true);
				
				//Show Message
				$this->getView()->showSubmitMessage($save);
			}
			//Form was not submitted
			else
			{
				//Require Logged in user to be admistrator.
				$this->getModel()->requireAdministrator();
				
				//Do Stuff
				$this->getView()->showCreateForm();
			}
		}
	}
	
	/**
	 * Show edit page
	 */
	protected function editRequest(){
		
		//If the user is logged in
		if($this->getModel()->checkLogin(false))
		{
			// Set the state and tell plugins.
			$this->setState('EDIT_REQUEST');
			$this->notifyObservers();
			
			//Check if isset
			$d = $this->getModel()->getInputString("access", null, "P");
			
			//If the Edit form was submitted
			if(!empty($d))
			{
				//Save user
				$save = $this->getModel()->saveUser(false);	
				
				//Show Message
				$this->getView()->showSubmitMessage($save);
			}
			//Otherwise show edit form
			else
			{
				//Gets the user details
				$data = $this->getModel()->getUserDetails();
				
				//Show Details in Form
				$this->getView()->showEditForm($data[0], $data[1], $data[2], $data[3], $data[4]);
			}
		}
	}
	
	/**
	 * Show delete page
	 */
	protected function deleteRequest(){
		
		//If the user is logged in
		if($this->getModel()->checkLogin(true))
		{
			// Set the state and tell plugins.
			$this->setState('DELETE_REQUEST');
			$this->notifyObservers();
			
			//Check if isset
			$d = $this->getModel()->getInputString("acc");
			
			//If not accepted delete
			if(empty($d))
			{
				//Show Are you sure
				$this->getView()->showDeleteCheck($this->getModel()->getActiveRequest());
			}
			//Actually Delete the User
			else
			{
				//Delete User
				$this->getModel()->delete();
				
				//Show Delete Success Message
				$this->getView()->showDeleteSuccess($this->getModel()->getActiveRequest());
			}
		}
	}
}

?>