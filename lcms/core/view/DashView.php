<?php

include("core/view/view.php");
include("core/lib/table.php");

class DashView extends View{
	
	
	/**
	 * Starts the controller of the classes system
	 */
	public function DashView(){
			
	}	
	
	/**
	 * Starts the controller of the classes system
	 */
	public function missingDetails(){
		
		// Set the state and tell plugins.
		$this->setState('MISSING_DETAILS');
		$this->notifyObservers();

		//Set Login Error		
		$_SESSION['ERROR_TYPE'] = "password";
		$_SESSION['ERROR_MESSAGE'] = $this->getController()->getModel()->openFile("core/fragments/missing_details.phtml");
		
		//Creates the login form system
		$l = new LoginForm();
		
		//create the login form
		$content = $l->createForm();
		
		//Set the title of the page to Login
		$this->setContentTitle("Login to Member's Area");
		
		//Set the login form as content
		$this->setContent($content);
		
		//Set the title of the website
		$this->setSiteTitle("LotusCMS Administration");
	}	
	
	/**
	 * Show wrong login details
	 */
	public function setWrongLogin(){
		
		// Set the state and tell plugins.
		$this->setState('CREATING_LOGIN_FORM');
		$this->notifyObservers();
		
		//Set Login Error		
		$_SESSION['ERROR_TYPE'] = "password";
		$_SESSION['ERROR_MESSAGE'] = $this->getController()->getModel()->openFile("core/fragments/wrong_details.phtml");
		
				//Creates the login form system
		$l = new LoginForm();
		
		//create the login form
		$content = $l->createForm();
		
		//Set the title of the page to Login
		$this->setContentTitle("Login to Member's Area");
		
		//Set the login form as content
		$this->setContent($content);
		
		//Set the title of the website
		$this->setSiteTitle("LotusCMS Administration");
	}
	
	/**
	 * Show the Dashboard
	 */
	public function showDash(){
		
		// Set the state and tell plugins.
		$this->setState('LOADING_DASH');
		$this->notifyObservers();
		
		//Get Dashboard screen
		$content = $this->openFile("core/fragments/admin_dashboard.phtml");
		
		//Print this dashboard
		$this->setContent($content);	
		
		// Set the state and tell plugins.
		$this->setState('FINISHED_DASH');
		$this->notifyObservers();
	}
}

?>