<?php

include("core/view/view.php");
include("core/lib/table.php");

class ModuleUpdateView extends View{
	
	
	/**
	 * Starts the controller of the classes system
	 */
	public function ModuleUpdateView(){
			
	}	
	
	/**
	 * Redirects to the activation page after successful install.
	 */
	public function showInstallRedirect($plugin){
		
			//Show success message on redirected to page
			$_SESSION['ERROR_TYPE'] = "success";
			$_SESSION['ERROR_MESSAGE'] = "'".$plugin."' Module successfully updated.";
			
			//Go Redirect
			$this->setRedirect("index.php?system=Modules&page=load&active=".$plugin);	
	}
}

?>