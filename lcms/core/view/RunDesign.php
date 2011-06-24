<?php
class RunDesign{

	var $page;
	var $design;

	/**
	 *	Runs the design
	 */
	public function runDesign($page, $design = null){
		
		//setup page
		$this->page = $page;
		
		//Override design
		if(!empty($design))
		{
			$this->design = $design;
		}
		//Else Get the design from file
		else
		{
			$this->design = $this->openFile('data/config/active_design.dat');
		}
	}
	
	/**
	 * Generates Temp
	 */
	public function generate(){
		//Get the content of the file
		return $this->obFile($this->page);
	}
	
	/** 
	 * Returns the current design.
	 */
	private function getCurrentDesign(){
		return $this->design;
	}
	
	/**
	 * Runs the data through the template
	 */
	private function obFile($p){
		
		//Get the currently in use design
		$current_temp = $this->getCurrentDesign();
		
		//Start Output Buffer
		ob_start();	
		
		include("style/".$current_temp.".php");
				
		//Get the contents of the buffer
		$content = ob_get_contents();
		
		//Empty the buffer
		ob_clean();
		
		return $content;
	}
	
    /**
     * Returns the contents of the requested page
     */
    protected function openFile($n){
    	$fd=fopen($n,"r") or die('Error 11: File Cannot be opened, '.$n);
		$fs=fread($fd,filesize($n));
		fclose($fd);
		return $fs;
    }
    
}
?>