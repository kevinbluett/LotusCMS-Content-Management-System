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
include("core/controller/PageListController.php");
	
class PageListStarter{
	
	/**
	 * Fetches the page one way or another.
	 */
	public function PageListStarter($fetch){
		
		//Setup the class page
		$ac = new PageListController($fetch);
	}
}

?>