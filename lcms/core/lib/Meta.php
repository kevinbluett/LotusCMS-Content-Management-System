<?php
/**
 * Gets Meta-Keywords and Meta-Description for SEO use
 */
class Meta{
	
	protected $ex;
	
	public function Meta(){
		$this->ex = "";
	}	
	
	/**
	 * Returns the required SEOed Description
	 */
	public function getDescription(){
		return $this->openFile("data/config/site_description.dat");
	}
	
	/**
	 * Returns the required SEOed Keywords
	 */
	public function getKeywords(){
		return $this->openFile("data/config/site_keywords.dat");
	}
	
	/**
	 * Adds Extra Meta Data (Depriciated but may be in use)
	 */
	public function addExtra($ex){
		$this->ex = $ex;	
	}
	
	/**
	 * Adds Extra Meta Data (Formal Name)
	 */
	public function appendExtra($ex){
		$this->ex = $ex;	
	}
	
	/**
	 * Gets Extra Meta Data
	 */
	public function getExtra(){
		return $this->ex;	
	}
	
    /**
     * Returns the contents of the requested page
     */
    protected function openFile($n){
    	$fd=fopen($n,"r") or die('<h1>Uncritical Error 23. Failed opening file: '.$n.', in Meta systems.</h2>');
		$fs=fread($fd,filesize($n));
		fclose($fd);
		return $fs;
    }
}

?>