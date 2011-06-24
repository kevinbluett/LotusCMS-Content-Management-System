<?php

include("core/model/model.php");

class AdminModel extends Model{
	
	public $t;
	
	/**
	 * Starts the controller of the classes system
	 */
	public function AdminModel(){
		
	}
	
	/**
	 * Returns the page requested by the system
	 */
	public function getPageRequest(){
		return $this->getInputString("page");	
	}
	
	/**
	 * Collects details of a user from the database system
	 * Returns a Null array if user doesn't exist;
	 */
	public function checkUserDetails($username, $password){
		
		//Include the User Library
		include_once("core/lib/User.php");
		
		//Setup user class
		$user = new User();
		
		//Encrypt the password
		$password = $user->encryptPass($password);
		
		$data = $user->getDetails($username);
		
		//If the user doesn't exist return false, incorrect details
		if(empty($data))
		{
			//Return false
			return false;	
		}
		//If the password is correct the user details are correct
		else if($password==$data[1])
		{
			//The details are correct
			return true;
		}
	}
	
	/**
	 * Sets the session access level.
	 */
	public function setAccessLvl($username){
		
		include_once("core/lib/User.php");
		
		//Setup user class
		$user = new User();
		
		//Collect the access level from the system
		$lvl = $user->accessLvl($username);
		
		//Sets the access level
		$_SESSION['access_lvl'] = $lvl;
		
		//Sets the username for reference
		$_SESSION['username'] = $username;
	}
	
	/**
	 * Set the login token
	 */
	public function setLoginToken(){
		
		//Change ID after logging in.
		session_regenerate_id();
		
		//Login token
		$_SESSION['login'] = true;	
	}
	
	/**
	 * unset the login token
	 */
	public function unsetLoginToken(){
		
		//unset Login token
		unset($_SESSION['login']);	
		
		//Destroy All Other Session Data
		session_destroy();
		
		//Logout - change ID
		session_regenerate_id();
	}
	
}

?>