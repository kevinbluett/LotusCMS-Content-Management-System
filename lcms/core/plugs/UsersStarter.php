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
include("core/controller/UsersController.php");
	
class UsersStarter{
	
	/**
	 * Fetches the page one way or another.
	 */
	public function UsersStarter($fetch){
		
		//Setup the class page
		$ac = new UsersController($fetch);
	}
}

?>