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
include("core/controller/ModulesController.php");
	
class ModulesStarter{
	
	/**
	 * Fetches the page one way or another.
	 */
	public function ModulesStarter($fetch){
		
		//Setup the class page
		$ed = new ModulesController($fetch);
	}
}

?>