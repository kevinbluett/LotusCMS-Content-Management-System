<?php

include_once("core/lib/io.php");

class User extends InputOutput{
	
	
	/**
	 * Starts the User System
	 */
	public function User(){
		
	}
	
	/**
	 * Checks if a user exists
	 */
	public function exists($username){
		
		//Check if the username file exists
		if(file_exists("data/users/".$username.".dat"))
		{
			return true;	
		}
		//If it doesn't return false
		else
		{
			return false;
		}
	}
	
	/**
	 * Returns a user access level
	 */
	public function accessLvl($user){
		
		//Make sure user exists
		if($this->exists($user))
		{
			//Get user details
			$data = $this->parseFile($user);
			
			//Return just the access level
			return $data[2];		
		}	
		else
		{
			//Return false if user doesn't exists;
			return false;	
		}
	}
	
	/**
	 * Returns a user's full name
	 */
	public function fullname($user){
		
		//Make sure user exists
		if($this->exists($user))
		{
			//Get user details
			$data = $this->parseFile($user);
			
			//Return just the access level
			return $data[0];		
		}	
		else
		{
			//Return false if user doesn't exists;
			return false;	
		}
	}
	
	/**
	 * Returns a user's password.
	 */
	public function hashedPassword($user){
		
		//Make sure user exists
		if($this->exists($user))
		{
			//Get user details
			$data = $this->parseFile($user);
			
			//Return just the access level
			return $data[1];		
		}	
		else
		{
			//Return false if user doesn't exists;
			return false;	
		}
	}
	
	/**
	 * Collects details of a user from the database system
	 * Returns a Null array if user doesn't exist;
	 */
	public function getDetails($user){
		
		$user = $this->filterUsername($user);
		
		//If the User exists;
		if($this->exists($user))
		{
			//Return the parsed user details
			return $this->parseFile($user);
		}
		//Otherwise return empty array
		else
		{
			//Empty Array
			return array();
		}
	}
	
	/**
	 * Checks if the user detailsare correct
	 */
	public function checkUserDetails($username, $password){
		
		//Encrypt the password
		$password = $this->encryptPass($password);
		
		$data = $this->getDetails($username);
		
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
	 * Checks the user access level
	 */
	public function checkUserLevel($lvl){
		
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
	
	
	/**
	 * Filter the username, dying if hacking attemp suspected
	 */
	protected function filterUsername($user){
		
		//If trying to get into the system using the 
		if($user=="index.php"){
			die($this->openFile("core/fragments/hacking_attempt.phtml"));	
		}
		else if($user==".htaccess"){
			die($this->openFile("core/fragments/hacking_attempt.phtml"));	
		}
		
		return $user;
	}
	
	/**
	 * Removes pipes and quotes from a string
	 */
	protected function filterString($text){
		
    	//Special Characters to be removed
    	$sp = array(
    						"|",
    						"'",
    						'"'
    					);
    	
    	//loop through specified characters
    	for($i = 0; $i < count($sp); $i++)
    	{
    		//Replace Disallowed character with empty string
    		$text = str_replace($sp[$i], "", $text);	
    	}
    	
    	//Return string
    	return $text;
	}
	
	
	/**
	 * Encrypts password using hash and sha1.
	 */
	public function encryptPass($pass){
		
		//Get the user hash
		$hash = $this->openFile("data/config/salt.dat");
		
		//Add hash to password
		$pass .= $hash;
		
		//Encrypt Password
		$pass = sha1($pass);
		
		//Returns encrypted password
		return $pass;
	}
	
	/**
	 * Parses a User file into a detail array
	 */
	protected function parseFile($user){
		
		//Opens the user file
		$file = $this->openFile("data/users/".$user.".dat");
		
		//Explode the contents of this file
		$data = explode("|", $file);
		
		//Return the Data
		return $data;
	}
	
	/**
	 * Change Full Name of user
	 */
	public function setFullName($username, $fullname){
		
		//Get Current Data
		$data = $this->getDetails($username);
		
		//Change the Fullname data
		$data[0] = $fullname;
		
		//Save the User Data
		$this->saveUser($username, $data[0], $data[3], $data[1], $data[2]);
	}
	
	/**
	 * Delete a users file
	 */
	public function delete($username){
		
		//Goes over minimum php memory if using InputOutput API.
		//If the users file exists try to delete it
		if(file_exists("data/users/".$username.".dat"))
		{
			unlink("data/users/".$username.".dat") or die($this->openFile("core/fragments/errors/error34.phtml"));
		}
	}
	
	/**
	 * Save the user details
	 */
	public function saveUser($username, $fullname, $email, $password, $accesslvl){
		
		//Filter username
		$username = $this->filterString($username);
		
		//Filter Full Name
		$fullname = $this->filterString($fullname);
		
		//Filter E-mail
		$email = $this->filterString($email);

		//If Password is not to be reset
		if(empty($password))
		{
			//Get the user details
			$details = $this->getDetails($username);
			
			//Set the password as detail to be saved
			$password = $details[1];
		}	
		//Encrypt The password is it was submitted
		else
		{
			//Encrypt Password
			$password = $this->encryptPass($password);	
		}
		
		//Filter Access
		$accesslvl = $this->filterString($accesslvl);
				
		//Create the String
		$userFile = $fullname."|".$password."|".$accesslvl."|".$email;
		
		//Save this userfile
		$this->saveFile("data/users/".$username.".dat", $userFile);	
	}
	
	/**
	 * Should log errors and hacking attempts in future.
	 */
	protected function hookLogger($msg){
		//Unsupported	
	}
}

?>