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
include("core/controller/EditorController.php");
	
class EditorStarter{
	
	/**
	 * Fetches the page one way or another.
	 */
	public function EditorStarter($fetch){
		
		//Setup the class page
		$ed = new EditorController($fetch);
	}
}

?>