<?php
include("core/model/model.php");

class UsersModel extends Model{
	
	/**
	 * Starts the controller of the classes system
	 */
	public function UsersModel(){
		
		//Allow Plugins.
		Observable::Observable();
	}
	
	/**
	 * Delete a user
	 */
	public function delete(){
		
		// Set the state and tell plugins.
		$this->setState('DELETING_USER');
		$this->notifyObservers();
		
		//Get Username
		$username = $this->getActiveRequest();
		
		//Include the User Library
		include_once("core/lib/User.php");
		
		//Setup user class
		$u = new User();	
		
		//Delete u
		$u->delete($username);
	}
	
	/**
	 * Saves the user from form whatever type
	 */
	public function saveUser($new){
		
		// Set the state and tell plugins.
		$this->setState('SAVE_USER');
		$this->notifyObservers();
		
		//Create Strings
		$username = "";
		$fullname = $this->getInputString("name", null, "P");
		$email = $this->getInputString("email", null, "P");
		$password1 = $this->getInputString("password1", null, "P");
		$password2 = $this->getInputString("password2", null, "P");
		$accesslvl = $this->getInputString("access", null, "P");
		
		//If loading from post data
		if($new)
		{
			//Get from post
			$username = $this->getInputString("username", null, "P");
		}
		//User in Get data
		else
		{
			//Get from URL
			$username = $this->getActiveRequest();
		}
		
		//If username empty return false
		if(empty($username))
		{
			//User not filled in correctly
			return false;	
		}
		
		//If no new passwords
		if(empty($password1))
		{
			$password1 = "";
			$password2 = "";
		}
		//If a new password is submitted.
		else
		{
			//If passwords were filled in
			if($password1!=$password2)
			{
				//Password 1 not equal to password 2
				return false;	
			}	
		}
		
		//Include the User Library
		include_once("core/lib/User.php");
		
		//Setup user class
		$u = new User();
		
		//USER LIB API
		$u->saveUser($username, $fullname, $email, $password1, $accesslvl);
		
		//Was Success
		return true;
	}
	
	/**
	 * Returns the page requested by the system
	 */
	public function getPageRequest(){
		return $this->getInputString("page");	
	}
	
	/**
	 * Returns the page requested by the system
	 */
	public function getActiveRequest(){
		return $this->getInputString("active", null, "G");	
	}
	
	/**
	 * Collects User details through available library
	 */
	public function getUserDetails(){
		
		// Set the state and tell plugins.
		$this->setState('GETTING_USER_DETAIL');
		$this->notifyObservers();
		
		//Get the username request
		$username = $this->getActiveRequest();
		
		//Include the User Library
		include_once("core/lib/User.php");
		
		//Setup user class
		$u = new User();
		
		//Get the user data
		$data = $u->getDetails($username);

		//Convert Data into View required strings [showEditForm($user, $fullname, $email, $level1, $level2)]
		$form = array();
		
		//Set Name
		$form[0] = $username;
		
		//Set Full Name
		$form[1] = $data[0];
		
		//Set Email
		$form[2] = $data[3];
		
		//Set Option 1 (Allows for case of external change eg. more than just administrator and editor - possibly a hacked 'contributor' mode.)
		$form[3] = $data[2];
		
		//If Administrator
		if($data[2]=="administrator")
		{
			//Show Administrator as first option
			$form[4] = "editor";
		}
		//If Editor
		else
		{
			//Show Administrator as first option
			$form[4] = "administrator";		
		}
		
		//Return form data
		return $form;
	}
	
	/**
	 * Requires logged in user to be administrator
	 */
	public function requireAdministrator(){
		
		// Set the state and tell plugins.
		$this->setState('REQUIRE_ADMINISTRATOR');
		$this->notifyObservers();
		
		//Required Level
		$lvl = "administrator";
		
		//If level is not ok force quick stop of processing
		if(!$this->checkUserLevel($lvl))
		{
			//Get Access Denied Page for his level
			$cont = $this->openFile("core/fragments/users/access_denied.phtml");
		
			//Set the content
			$this->getController()->getView()->setContentTitle("Access Denied");
			
			//Set the content
			$this->getController()->getView()->setContent($cont);
			
			//Display page
			$this->getController()->displayPage();
			
			//Stop All Processing
			$this->getController()->freeze_all();
		}	
	}
	
	/**
	 * Checks the user access level
	 */
	protected function checkUserLevel($lvl){
		
		// Set the state and tell plugins.
		$this->setState('CHECKING_USER_LVL');
		$this->notifyObservers();
		
		//Get Access level (Force userage of Session variable - otherwise attack is possible)
		$access = $this->getInputString("access_lvl", "", "S");
			
		//Check Access Level
		if($lvl!=$access)
		{
			//The User does not have the rights to access this area
			return false;	
		}
		//Access Level OK
		else
		{
			//Access OK
			return true;	
		}
	}
	
}

?>