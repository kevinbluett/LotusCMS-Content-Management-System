<?php
include_once("core/lib/User.php");
class AdminModel extends Model{
	
	public $user;
	
	/**
	 * Starts the controller of the classes system
	 */
	public function AdminModel(){
		$this->user = new User();
	}
	
	/**
	 * Sets the session access level.
	 */
	public function setAccessLvl($username){
		
		//Collect the access level from the system
		$lvl = $this->user->accessLvl($username);
		
		//Sets the access level
		$_SESSION['access_lvl'] = $lvl;
		
		//Sets the username for reference
		$_SESSION['username'] = $username;
	}
	
	public function checkUserDetails($username, $password){
		return $this->user->checkUserDetails($username, $password);	
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