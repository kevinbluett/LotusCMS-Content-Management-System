<?php

include("core/model/model.php");

class PageListModel extends Model{
	
	/**
	 * Starts the controller of the classes system
	 */
	public function PageListModel(){
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
		$this->setState('CHECK_USER_DETAILS');
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
	public function getPages(){
		
		// Set the state and tell plugins.
		$this->setState('GETTING_PAGES');
		$this->notifyObservers();
		
		//The directory containing the pages.
		$dir = $this->getController()->getPageDirectory();
		
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