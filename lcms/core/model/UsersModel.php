<?php
include_once("core/lib/User.php");	
class UsersModel extends Model{
	
	public function UsersModel(){
		Observable::Observable();
	}
	
	/**
	 * Delete a user
	 */
	public function delete(){
		$this->setState('DELETING_USER');
		
		//Get Username
		$username = $this->getActiveRequest();
		
		//Include the User Library
		include_once("core/lib/User.php");
		
		//Setup user class
		$u = new User();	
		
		//Delete u
		$u->delete($username);
	}
	
	protected function checkUserLevel($lvl){
		$u = new User();
		return $u->checkUserLevel($lvl);
	}
	
	/**
	 * Saves the user from form whatever type
	 */
	public function saveUser($new){
		$this->setState('SAVE_USER');

		//Create Strings
		$username 	= "";
		$fullname 	= $this->getInputString("name", null, "P");
		$email 		= $this->getInputString("email", null, "P");
		$password1 	= $this->getInputString("password1", null, "P");
		$password2 	= $this->getInputString("password2", null, "P");
		$accesslvl 	= $this->getInputString("access", null, "P");
		
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
	 * Collects User details through available library
	 */
	public function getUserDetails(){
		$this->setState('GETTING_USER_DETAIL');
		
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
		$this->setState('REQUIRE_ADMINISTRATOR');
		
		//Required Level
		$lvl = "administrator";
		
		$u = new User();
		
		//If level is not ok force quick stop of processing
		if(!$u->checkUserLevel($lvl))
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
}

?>