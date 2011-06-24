<?php

include("core/lib/page.php");

class View extends Page{

	//Controller Variable Local
	protected $con;
	
	/**
	 * Set the local Controller
	 */
	public function setController($con){
		$this->con = $con;
	}
	
	/**
	 * Get the local Controller
	 */
	public function getController(){
		return $this->con;
	}

	/**
	 * Process Blank Request
	 */
	public function noPage(){
		
		//Get the not exist page
		$not_exist = file_get_contents("core/fragments/404.phtml");
		
		//Set the Title
		$this->setContentTitle("404 - Page does not Exist");
				
		//Set the 404 page
		$this->setContent($not_exist);
	}
	
}

?>