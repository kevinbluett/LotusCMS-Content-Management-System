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
include("core/controller/ModulesInstallController.php");
	
class ModulesInstallStarter{
	
	/**
	 * Fetches the page one way or another.
	 */
	public function ModulesInstallStarter($fetch){
		
		//Setup the class page
		$ed = new ModulesInstallController($fetch);
	}
}

?>