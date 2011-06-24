<?php
/**
 *
 * GPL v4 
 * LotusCMS 2010.
 *
 * Written by Kevin Bluett
 *
 */
 
//Controller of the paging collector.
include("core/controller/UsersListController.php");
	
class UsersListStarter{
	
	/**
	 * Fetches the page one way or another.
	 */
	public function UsersListStarter($fetch){
		
		//Setup the class page
		$ac = new UsersListController($fetch);
	}
}

?>