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
include("core/controller/SettingsController.php");
	
class SettingsStarter{
	
	/**
	 * Fetches the page one way or another.
	 */
	public function SettingsStarter($fetch){
		
		//Setup the class page
		$ac = new SettingsController($fetch);
	}
}

?>