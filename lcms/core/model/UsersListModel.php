<?php
class UsersListModel extends Model{
	
	public function UsersListModel(){
		Observable::Observable();
	}
	
	/**
	 * List all pages in a directory.
	 */
	public function getUsers(){
		$this->setState('GETTING_USERS');
		
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
}

?>