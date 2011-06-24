<?php

include("core/model/model.php");

class UsersListModel extends Model{
	
	/**
	 * Starts the controller of the classes system
	 */
	public function UsersListModel(){
		
		//Allow Plugins.
		Observable::Observable();
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
		
		// Set the state and tell plugins.
		$this->setState('CHECKING_USER_DETAILS');
		$this->notifyObservers();
		
		//Include the User Library
		include("core/lib/User.php");
		
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
	 * List all pages in a directory.
	 */
	public function getUsers(){
		
		// Set the state and tell plugins.
		$this->setState('GETTING_USERS');
		$this->notifyObservers();
		
		//The directory containing the pages.
		$dir = "data/users";
		
		//Lists the pages in a directory
		$pages = $this->listFiles($dir);
		
		//Loops through all page listings to remove the extension of .dat
		for($i = 0; $i < count($pages); $i++)
		{
			//Removes the .dat from an item in the array
			$pages[$i] = str_replace(".dat", "", $pages[$i]);	
		}
		
		return $pages;	
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
	
	/**
	 * Returns a list of all the files in a specified directory (Not Recursive) - excluding confirguration files and 'index.php'.
	 */
	protected function listFiles($start_dir)
    {
        
        /*
        returns an array of files in $start_dir (not recursive)
        */
                
        $files = array();
        $dir = opendir($start_dir);
        while(($myfile = readdir($dir)) !== false)
                {
                if($myfile != '.' && $myfile != '..' && !is_file($myfile) && $myfile != 'resource.frk' && $myfile != 'index.php' && !eregi('^Icon',$myfile) )
                        {
                        $files[] = $myfile;
                        }
                }
        closedir($dir);
        return $files;
   }
	
}

?>