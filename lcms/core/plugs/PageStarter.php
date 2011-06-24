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
include("core/controller/PageController.php");
	
class PageStarter{
	
	/**
	 * Fetches the page one way or another.
	 */
	public function PageStarter($fetch){
		
		//Setup the class page
		$pc = new PageController($fetch);
	}
}

?>