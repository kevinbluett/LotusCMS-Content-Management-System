<?php
class AdminController extends Controller{
	
	public function AdminController($page){
		//Setup the page + [disabling authencation for this page]
		$this->setup("Admin", "LotusCMS Administration", "admin", "");
	}

	protected function putRequests(){
		$requests = array("login","loginSubmit","logout");
		$this->setRequests($requests);
	}
	
	/**
	 * Show default classes
	 */
	protected function defaultRequest(){
		
		//If the user is logged in
		if($this->getModel()->checkLogin(true, true))
		{
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
	 * Show default classes
	 */
	protected function loginRequest(){
		
		//If the user is logged in
		if($this->getModel()->checkLogin(true, true))
		{
			//Redirect to Dashboard
			$this->getPaging()->setRedirect('?system=Dash&page=index');	
		}
		
	}
	
	/**
	 * Login Submit System
	 */
	protected function loginSubmitRequest(){
		
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
				//Set the login token
				$this->getModel()->setLoginToken();
				
				//Set the users access level
				$this->getModel()->setAccessLvl($username);
				
				//Redirect to the dashboard
				$this->getPaging()->setRedirect("index.php?system=Dash&page=index");
			}
			//Show failed login if the details were incorrect
			else
			{
				$this->getView()->setWrongLogin();
			}
		}
	}
	
	/**
	 * Show default classes
	 */
	protected function logoutRequest(){
		if($this->getModel()->checkLogin(true))
		{
			$this->getModel()->unsetLoginToken();
			$this->getView()->setRedirect("index.php");
		}
	}
}

?>