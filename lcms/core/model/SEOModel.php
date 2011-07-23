<?php
class SEOModel extends Model{
	
	public function SEOModel(){
		Observable::Observable();
	}
	
	/**
	 * Returns the contents of the page as an array
	 */
	public function getSEOData(){
		$this->setState('GETTING_SEO_DATA');
		
		//Setup Array
		$data = array();
    	
		//Open the page file
		$data[0] = $this->openFile("data/config/site_keywords.dat");
		
		//Open the page file
		$data[1] = $this->openFile("data/config/site_description.dat");
		
		//Return the collected data
		return $data;
	}
	
	/**
	 * Gets and saves the data from the SEO form
	 */
	public function saveSEOData(){
		$this->setState('SAVING_SEO_DATA');

		//Get submitted data
		$data = $this->getSEODataForm();
		
		//Save Keywords
		$this->saveFile("data/config/site_keywords.dat", $data[0]);
		
		//Save Site Description
		$this->saveFile("data/config/site_description.dat", $data[1]);
	}	
	
	/**
	 * Gets the data submitted through SEO form in the administration panel.
	 */
	public function getSEODataForm(){
		$this->setState('GETTING_SUBMITTED_DATA');
		
		//Create Array for the data
		$data = array();
		
		//Get keywords
		$data[] = $this->getInputString("seokeywords", null, "P");
		
		//Get Description
		$data[] = $this->getInputString("seodescription", null, "P");
		
		//Return this data
		return $data;
	}
}

?>