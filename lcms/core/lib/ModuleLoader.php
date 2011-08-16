<?php
/**
 * Interfaces page processing with individual plugins.
 */
class ModuleLoader{
	
	
	/**
	 * Starts the Module Loading System
	 */
	public function ModuleLoader($module, $page){		
		
		//A Full Loader
		if(file_exists("modules/".$module."/".$module."Module.php"))
		{
			$module = htmlentities ( trim ($module));
			$page = htmlentities ( trim ($page));
			//Include Basic Module Functions
			include_once("core/lib/Module.php");
			
			//Include the Module Processing Functions
			include_once("modules/".$module."/".$module."Module.php");
			
			//Module
			$m;
			
			//Create the module
			eval("\$m = new ".$module."Module(\$page);");
			
			//Incorporates the page request into the module.
			$m->setPage($page);
			
			//Sets unix name of module
			$m->setUnix($module);
			
			//Include the controller and all interfacing to paging etc.
			include_once("core/controller/ModuleLoaderController.php");
				
			//Setup Module Controller	
			$con = new ModuleLoaderController($page, $m);
		}
	}
}
?>