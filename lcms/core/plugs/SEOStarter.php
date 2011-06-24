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
include("core/controller/SEOController.php");
	
class SEOStarter{
	
	/**
	 * Fetches the page one way or another.
	 */
	public function SEOStarter($fetch){
		
		//Setup the class page
		$ed = new SEOController($fetch);
	}
}

?>