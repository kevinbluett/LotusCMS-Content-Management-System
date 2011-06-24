<?php

include("core/view/view.php");
include("core/lib/table.php");

class GeneralSettingsView extends View{
	
	
	/**
	 * Starts the controller of the classes system
	 */
	public function GeneralSettingsView(){
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
		$this->setState('LOADING_EDITOR');
		$this->notifyObservers();
		
		//Change the content into a form
		$content = $this->singleForm($data[0], $data[1]);
		
		//Tabs above settings
		$tabs = $this->openFile("core/fragments/settings/SettingsDash.phtml");	
		
		//Print this dashboard
		$this->setContent($tabs.$content);	
	}
	
	/**
	 * Redirects after successfully saving the data
	 */
	public function setWebsiteRedirect(){
		
		// Set the state and tell plugins.
		$this->setState('REDIRECTING');
		$this->notifyObservers();
		
		//Show success message on redirected to page
		$_SESSION['ERROR_TYPE'] = "success";
		$_SESSION['ERROR_MESSAGE'] = "Website Data Saved Successfully";
		
		//Go Redirect
		$this->setRedirect("index.php?system=GeneralSettings&page=edit");	
	}
	
	/**
	 * Create a form for the page.
	 */
	protected function singleForm($version, $title){
		
		// Set the state and tell plugins.
		$this->setState('CREATING_SINGLE_FORM');
		$this->notifyObservers();
		
		//Get the form
		$out = $this->openFile("core/fragments/settings/GeneralSettingsForm.phtml");
		
		//Replace Title in File
		$out = str_replace("%TITLE%", $title, $out);
		
		//Replace current version in File
		$out = str_replace("%CURRENTVERSION%", $version, $out);
		
		//Include and load the latest version
    	include("core/lib/RemoteFiles.php");
		$rf = new RemoteFiles();
    	$data = $rf->getURL("http://update.lotuscms.org/lcms-3-series/latestVersion.dat");
    	
    	//Reduce load by dumping the retrieval system.
    	unset($rf);
		
		//Replace Unix in File
		$out = str_replace("%LATESTVERSION%", $data, $out);
		
		
		//Return the out data
		return $out;
	}
}

?>