<?php

include("core/view/view.php");
include("core/lib/table.php");

class AdminView extends View{
	
	
	/**
	 * Starts the controller of the classes system
	 */
	public function AdminView(){
			
	}	
	
	/**
	 * Starts the controller of the classes system
	 */
	public function missingDetails(){
		
		//Change ID after incorrect logn
		session_regenerate_id();

		//Login
		$out = $this->openFile("style/comps/admin/login.phtml");
		
			
		$out = str_replace("%MESSAGE%", "<p class='msg error'>".$this->openFile("core/fragments/missing_details.phtml")."</p>", $out);

		//This quits the normal login form,
		$this->overridePaging($out);
		/******* freeze_all() called ********/
	}	
	
	/**
	 * Show wrong login details
	 */
	public function setWrongLogin(){
		
		//Change ID after incorrect logn
		session_regenerate_id();
		
		//Login
		$out = $this->openFile("style/comps/admin/login.phtml");
		
		$out = str_replace("%MESSAGE%", "<p class='msg error'>".$this->getController()->getModel()->openFile("core/fragments/wrong_details.phtml")."</p>", $out);

		//This quits the normal login form,
		$this->overridePaging($out);
		/******* freeze_all() called ********/
	}

}

?>