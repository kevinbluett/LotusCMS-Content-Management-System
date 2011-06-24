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
include("core/controller/DashController.php");
	
class DashStarter{
	
	/**
	 * Fetches the page one way or another.
	 */
	public function DashStarter($fetch){
		
		//Setup the class page
		$ac = new DashController($fetch);
	}
}

?>