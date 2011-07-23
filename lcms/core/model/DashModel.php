<?php
class DashModel extends Model{
	
	/**
	 * Starts the controller of the classes system
	 */
	public function DashModel(){
		Observable::Observable();
	}
	
	/**
	 * Collects details of a user from the database system
	 * Returns a Null array if user doesn't exist;
	 */
	public function checkUserDetails($username, $password){
		
		// Set the state and tell plugins.
		$this->setState('CHECKING_USER_DETAILS');
		$this->notifyObservers();
		
		//Include the User Library
		include("lib/User.php");
		
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
	
}

?>