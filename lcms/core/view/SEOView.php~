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
		$tabs = $this->openFile("core/fragments/settings/SettingsDash.phtml");
		
		//Sets this tab active.
		$tabs = $this->setTabActive(2, $tabs);	
		
		//Localise
		$tabs = str_replace("%GENERAL_SETTINGS_LOCALE%", $this->localize("General Settings"), $tabs);
		$tabs = str_replace("%SEO_SETTINGS_LOCALE%", $this->localize("SEO Settings"), $tabs);
		$tabs = str_replace("%TEMPLATE_SETTINGS_LOCALE%", $this->localize("Template Settings"), $tabs);
		$tabs = str_replace("%CLEAR_CACHE_LOCALE%", $this->localize("Clear Cache"), $tabs);
		
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
		$_SESSION['ERROR_MESSAGE'] = $this->localize("SEO Data Saved Successfully");
		
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
		
		//Localize
		$out = str_replace("%WEBSITE_KEYWORDS_LOCALE%", $this->localize("Website Keywords"), $out);
		$out = str_replace("%KEYWORDS_HELP_LOCALE%", $this->localize("Approximately 30 keywords separated by commas"), $out);
		$out = str_replace("%SEO_DESCRIPTION_LOCALE%", $this->localize("SEO Description"), $out);
		$out = str_replace("%SEO_DESCRIPTION_HELP_LOCALE%", $this->localize("Short description of your website of around 25 words."), $out);
		$out = str_replace("%SAVE_LOCALE%", $this->localize("Save"), $out);
		
		//Return the out data
		return $out;
	}
}

?>