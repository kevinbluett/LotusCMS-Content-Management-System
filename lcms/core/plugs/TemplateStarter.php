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
include("core/controller/TemplateController.php");
	
class TemplateStarter{
	
	/**
	 * Fetches the page one way or another.
	 */
	public function TemplateStarter($fetch){
		
		//Setup the class page
		$ed = new TemplateController($fetch);
	}
}

?>