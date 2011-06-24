<?php

//Default Controller
include("core/controller/controller.php");

class DashController extends Controller{
	
	/**
	 * Starts the controller of the classes system
	 */
	public function DashController($page){
		
		//Allow Plugins.
		Observable::Observable();
		
		//Setup basic variables
		$this->varSetup();
		
		//Sets the name of the other classes
		$this->setSystem("Dash");
		
		//Setup the page
		$this->setup("Dashboard");
		
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
							"login",
							"loginSubmit",
							"logout"
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
			// Set the state and tell plugins.
			$this->setState('DEFAULT_REQUEST');
			$this->notifyObservers();
			
			//Redirect to Dashboard
			$this->getPaging()->setRedirect('?system=Dash&page=index');	
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
			// Set the state and tell plugins.
			$this->setState('LOADING_DASH');
			$this->notifyObservers();
		
			//Print the Dash
			$this->getView()->showDash();
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
	protected function loginRequest(){
		
		//If the user is logged in
		if($this->getModel()->checkLogin(true))
		{
			//Redirect to Dashboard
			$this->getPaging()->setRedirect('?system=Dash&page=index');	
		}
		
	}
	
	/**
	 * Login Submit System
	 */
	protected function loginSubmitRequest(){
		
		// Set the state and tell plugins.
		$this->setState('LOGIN_SUBMIT');
		$this->notifyObservers();
		
		$username = $this->getModel()->getInputString("username");
		$password = $this->getModel()->getInputString("password");
		
		if(empty($username)||empty($password))
		{
			//Show that the details are missing
			$this->getView()->missingDetails();
		}
		//If entered details
		else
		{
			//Check the user details
			$check = $this->getModel()->checkUserDetails($username, $password);
			
			//Direct to Dash if details are correct
			if($check)
			{
				$this->getPaging()->setRedirect("index.php?system=Dash&page=index");
			}
			//Show failed login if the details were incorrect
			else
			{
				$this->getView()->setWrongLogin();
			}
		}
	}
}

?>