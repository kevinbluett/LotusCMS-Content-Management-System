<?php
include_once("core/controller/controller.php");
/**
 * GPL v4 
 * LotusCMS 2011.
 * Written by Kevin Bluett
 * This Class routes any request from an external source into the LotusCMS systems.
 */
class Router{
	
	/**
	 * This routes any request from get variables into the LotusCMS system.
	 */
	public function Router(){
		//Get page request (if any)
		$page = $this->getInputString("page", "index");
		
		//Get plugin request (if any)
		$plugin = $this->getInputString("system", "Page");
		
		//If there is a request for a plugin
		if(file_exists("core/controller/".$plugin."Controller.php")){
			//Include Page fetcher
			include("core/controller/".$plugin."Controller.php");

			$name = $plugin."Controller";

			//Fetch the page and get over loading cache etc...
			new $name($page);
			
		}else if(file_exists("data/modules/".$plugin."/starter.dat")){
			//Include Module Fetching System
			include("core/lib/ModuleLoader.php");
			
			//Load Module
			new ModuleLoader($plugin, $this->getInputString("page", null));
		}else{ //Otherwise load a page from the standard system.
			//Include Page fetcher
			include("core/plugs/PageStarter.php");
			
			//Fetch the page and get over loading cache etc...
			new PageStarter($page);
		}
	}
	
	/**
	 * Returns a global variable
	 */
	protected function getInputString($name, $default_value = "", $format = "GPCS")
	{
		//order of retrieve default GPCS (get, post, cookie, session);
		$format_defines = array (
		'G'=>'_GET',
		'P'=>'_POST',
		'C'=>'_COOKIE',
		'S'=>'_SESSION',
		'R'=>'_REQUEST',
		'F'=>'_FILES',
		);
		preg_match_all("/[G|P|C|S|R|F]/", $format, $matches); //splitting to globals order
		foreach ($matches[0] as $k=>$glb)
		{
		    if ( isset ($GLOBALS[$format_defines[$glb]][$name]))
		    {   
			return htmlentities ( trim ( $GLOBALS[$format_defines[$glb]][$name] ) , ENT_QUOTES ) ;
		    }
		}
		return $default_value;
	} 

}

?>