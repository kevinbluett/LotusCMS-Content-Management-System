<?php

include("core/view/view.php");
include("core/lib/table.php");

class SEOView extends View{
	
	
	/**
	 * Starts the controller of the classes system
	 */
	public function SEOView(){
		//Meta is usually not setup yet, so we manually do this before loading css file
		$this->meta = new Meta();
		
		//This class requires an extra css file
		$this->getMeta()->addExtra('<link href="core/fragments/css/admin.css" rel="stylesheet" type="text/css" />');
	}	
	
	/**
	 * Show the Dashboard
	 */
	public function createEditor($data){
		
		// Set the state and tell plugins.
		$this->setState('CREATING_EDITOR');
		$this->notifyObservers();
		
		//Change the content into a form
		$content = $this->singleForm($data[0], $data[1]);
		
		//Tabs above settings
		$tabs = $this->openFile("core/fragments/settings/SEOtabs.phtml");	
		
		//Print this dashboard
		$this->setContent($tabs.$content);	
	}
	
	/**
	 * Redirects after successfully saving the data
	 */
	public function setSEORedirect(){
		
		// Set the state and tell plugins.
		$this->setState('SEO_REDIRECT');
		$this->notifyObservers();
		
		//Show success message on redirected to page
		$_SESSION['ERROR_TYPE'] = "success";
		$_SESSION['ERROR_MESSAGE'] = "SEO Data Saved Successfully";
		
		//Go Redirect
		$this->setRedirect("index.php?system=GeneralSettings&page=edit");	
	}
	
	/**
	 * Create a form for the page.
	 */
	protected function singleForm($keywords, $description){
		
		// Set the state and tell plugins.
		$this->setState('SINGLE_FORM_CREATING');
		$this->notifyObservers();
		
		//Get the form
		$out = $this->openFile("core/fragments/settings/SEOSettings.phtml");
		
		//Replace Title in File
		$out = str_replace("%SEOKEYWORDS%", $keywords, $out);
		
		//Replace Unix in File
		$out = str_replace("%SEODESCRIPTION%", $description, $out);
		
		//Return the out data
		return $out;
	}
}

?>