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
include("core/controller/GeneralSettingsController.php");
	
class GeneralSettingsStarter{
	
	/**
	 * Fetches the page one way or another.
	 */
	public function GeneralSettingsStarter($fetch){
		
		//Setup the class page
		$ed = new GeneralSettingsController($fetch);
	}
}

?>