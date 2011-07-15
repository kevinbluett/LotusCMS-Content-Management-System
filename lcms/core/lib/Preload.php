<?php

include_once("core/lib/io.php");

/**
 * This class loads information about versions from LotusCMS.org after killing connection, so that it is ready while the user is typing login info.
 */
class Preload extends InputOutput{
	

	public function Preload($out){
		
		//first kill user connection so their browser shows login page.
		$this->closeConnection($out);
		
		//Setup PHP browser
		include("core/lib/RemoteFiles.php");
	    	
	    	$rf = new RemoteFiles();
		
	    	//Checks for update & sets applicable session data.
		$this->checkForUpdate($rf);
		$this->getNews($rf);
		$this->checkForModuleUpdate();
	}
	
	/**
	 * Closes the connection to browser without stopping PHP processing.
	 */
	protected function closeConnection($out){

		ob_end_clean();
		 header("Connection: close");
		 ignore_user_abort(); // optional
		 ob_start();
		 print $out;
		 $size = ob_get_length();
		 header("Content-Length: $size");
		 ob_end_flush(); // Strange behaviour, will not work
		 flush();            // Unless both are called !
		 // close current session
		if (session_id()) session_write_close(); 
		session_start();
	}
	
	public function getNews($rf){
	    	//Get version of CMS
	    	$v = file_get_contents("data/config/site_version.dat");
	    	
	    	$data = $rf->getURL("http://news.lotuscms.org/lcms-3-series/getDashboardNews.php?v=".$v);
	    	
	    	$this->saveFile("data/lcmscache/news.dat", $data);
	}
	
	/**
	 * Check for module updates...
	 */
	public function checkForModuleUpdate(){
		
		include_once("core/lib/ModuleUpdateCheck.php");
			
		$muc = new ModuleUpdateCheck();
			
		//Check Modules, but discard results.
		$muc->getVersionArray($this->listFiles("modules"), true);
	}
	
	/** 
	 * Checks for LCMS update.
	 */
	protected function checkForUpdate($rf){
	    	
	    	//Get version of CMS
	    	$v = file_get_contents("data/config/site_version.dat");
	    	
	    	$l = file_get_contents("data/config/locale.dat");
	    	
	    	$data = $rf->getURL("http://update.lotuscms.org/lcms-3-series/updateCheck.php?v=".$v."&lang=".$l);
	    	
	    	$data = explode("%%", $data);
	    	
	    	$this->saveFile("data/lcmscache/vmessage.dat", $data[1]);
	    	$this->saveFile("data/lcmscache/vnumber.dat", $data[0]);
    	}
    	
}

?>