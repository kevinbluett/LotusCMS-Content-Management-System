<?php

/**
 * The Administration loader for the module
 */
class ModuleAdmin extends Admin{

	/**
	 * The Default setup function
	 */
	public function ModuleAdmin(){
		//Sets the unix name of the plugin
		$this->setUnix("Dashboard");	
	}

	/**
	 * Default Requestion
	 */
	public function defaultRequest(){
		
		$data = "This Plugin's Administration has yet to be built by the LotusCMS team.";
		
		$this->setContent($data);
	}
	
	/**
	 * Prints the news for the main dashboard.
	 */
	public function newsRequest(){
		
		//Loads news for the ajax box
		$this->getNews();
		
		//Stops all processing.
		$this->freeze_all();
	}
	
	/**
	 * Prints the news for the main dashboard.
	 */
	public function checkModulesRequest(){
		
		//Loads news for the ajax box
		$this->checkModules();
		
		//Stops all processing.
		$this->freeze_all();
	}
	
	/**
	 * Check Modules
	 */
	private function checkModules(){
		
		$plugins = $this->getPlugins();
		$out = "";
		$id = $this->getInputString("id");
	
		include_once("modules/Dashboard/Abstraction.php");
				
		$abs = new Abstraction();
		
		print $abs->checkForModuleUpdate($id);
	}
	
	/**
	 * Gets plugins activated and installed.
	 */
	public function getPlugins(){
		
		include_once("core/lib/io.php");
		$io = new InputOutput();
		
		//Gets all directory info inside modules i.e. activated and unactivated plugins
		$allPlugins = $io->listFiles("modules");
		
		//Returns all plugins with activity status.
		return $allPlugins;
	}
	
    /**
     * Gets html formatted news from LotusCMS.org
     */
    private function getNews(){
    	$data = "";
    	if(!isset($_SESSION['newsCheck'])){
	    	include("core/lib/RemoteFiles.php");
	    	
	    	$rf = new RemoteFiles();
	    	
	    	//Get version of CMS
	    	$v = $this->con->getVersion();
	    	
	    	$data = $rf->getURL("http://news.lotuscms.org/lcms-3-series/getDashboardNews.php?v=".$v);
	    	
	    	$_SESSION['newsCheck'] = $data;
    	}else{
    		$data = $_SESSION['newsCheck'];
    	}
    	print $data;
    }
	
	/**
	 * Quits any running software.
	 */
	protected function freeze_all(){
		exit;	
	}
}

?>