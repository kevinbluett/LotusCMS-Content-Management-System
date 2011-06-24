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
include("core/controller/ModuleUpdateController.php");
	
class ModuleUpdateStarter{
	
	/**
	 * Fetches the page one way or another.
	 */
	public function ModuleUpdateStarter($fetch){
		
		//Setup the class page
		$ed = new ModuleUpdateController($fetch);
	}
}

?>