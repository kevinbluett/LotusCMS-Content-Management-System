<?php

include("core/model/model.php");

class SEOModel extends Model{
	
	public $t;
	
	/**
	 * Starts the controller of the classes system
	 */
	public function SEOModel(){
		
		//Allow Plugins.
		Observable::Observable();
	}
	
	/**
	 * Returns the page requested by the system
	 */
	public function getActiveRequest(){
		return $this->getInputString("active", null, "G");	
	}
	
	/**
	 * Returns the contents of the page as an array
	 */
	public function getSEOData()
	{
		// Set the state and tell plugins.
		$this->setState('GETTING_SEO_DATA');
		$this->notifyObservers();
		
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
		
		// Set the state and tell plugins.
		$this->setState('SAVING_SEO_DATA');
		$this->notifyObservers();
		
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
		
		// Set the state and tell plugins.
		$this->setState('GETTING_SUBMITTED_DATA');
		$this->notifyObservers();
		
		//Create Array for the data
		$data = array();
		
		//Get keywords
		$data[] = $this->getInputString("seokeywords", null, "P");
		
		//Get Description
		$data[] = $this->getInputString("seodescription", null, "P");
		
		//Return this data
		return $data;
	}
	
	/**
	 * Save the set file, with the requested content.
	 * $m = file
	 * $n = file contents
	 * $o = Error message.
	 */
	protected function saveFile($m, $n, $o = 0){
    	
		//Save to disk if the space is available
		if($this->disk_space())
		{
			$n=trim($n);
			if($n==''){$n=' ';}$n=str_replace("\n\n","\n",$n);$p=0;
			do{$fd=fopen($m,"w+") or die($this->openFile("core/fragments/errors/error21.phtml")." - Via SEOModel.php");$fout=fwrite($fd,$n);
			fclose($fd);$p++;}while(filesize($m)<5&&$p<5);
		}
		else
		{
			//Print Out of Space Error Message
			die($this->openFile("core/fragments/errors/error22.phtml"));	
		}
	}
    
	/**
	 * Checks that there is enough space left to save the file on the harddisk.
	 */
	protected function disk_space(){
		$s = true;
		
		if(function_exists('disk_free_space'))
		{
			$a = disk_free_space("/");
			if(is_int($a)&&$a<204800)
			{
				$s = false;
			}
		}
		return $s;
	}
}

?>