<?php
/**
 * GPL v4 
 * LotusCMS 2010.
 * Written by Kevin Bluett
 * This Class generates a page for the cache if unavailable at the current moment and is static.
 */
class Cacher{
	/**
	 * This cachers the requested page.
	 */
	public function Cacher($page){
		
		//Include the pager
		include_once("core/view/Page.php");
		
		//The Title of the website
		$siteTitle = $this->openFile("data/config/site_title.dat");
		
		//Create the pager
		$pager = new Pager($siteTitle);
		
		//Get the page content
		$data = $this->getPageData($page);
		
		//Set the page title
		$pager->setContentTitle($data[0]);
		
		//If Not on default template set the alternate one
		if($data[1]!="Default"){
			$pager->setTemplate($data[1]);
		}
		
		//Set the page content
		$pager->setContent($data[2]);
		
		//Generate the page
		$toCache = $pager->softDisplayPage();
		
		//Save to the cache
		$this->saveFile("cache/".$page.".html", $toCache);
	}
	
	/**
	 * Returns the contents of the page as an array
	 */
	protected function getPageData($page)
	{
		//Open the page file
		$data = $this->openFile("data/pages/".$page.".dat");
		
		//Explode the available data
		$data = explode("|<*div*>|",$data);
		
		//Return the collected data
		return $data;
	} 
    
    /**
     * Returns the contents of the requested page
     */
    protected function openFile($n){
    	$fd=fopen($n,"r") or die('...');
		$fs=fread($fd,filesize($n));
		fclose($fd);
		return $fs;
    }
    
    /**
     * Save the set file, with the requested content.
     */
    protected function saveFile($m, $n, $o = 0){
    	
    	//Save to disk if the space is available
    	if($this->disk_space())
    	{
			$n=trim($n);
			if($n==''){$n=' ';}$n=str_replace("\n\n","\n",$n);$p=0;
			do{$fd=fopen($m,"w+") or die($this->openFile("core/fragments/errors/error21.phtml"));$fout=fwrite($fd,$n);
			fclose($fd);$p++;}while(filesize($m)<5&&$p<5);
		}
		else
		{
			//Print  Error Message
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
			$a=disk_free_space("/");
			if(is_int($a)&&$a<204800)
			{
				$s=false;
			}
		}
		return $s;
	}

}

?>