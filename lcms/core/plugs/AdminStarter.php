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
include("core/controller/AdminController.php");
	
class AdminStarter{
	
	/**
	 * Fetches the page one way or another.
	 */
	public function AdminStarter($fetch){
		
		//Setup the class page
		$ac = new AdminController($fetch);
	}
}

?>