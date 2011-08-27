<?php
include_once("core/controller/controller.php");
/* LCMS by Kevin Bluett 2011 */
class Router{
	/* This routes any request from get variables into the LotusCMS system. */
	public function Router(){
		$page = $this->getInputString("page", "index");
		$plugin = $this->getInputString("system", "Page");
		if(file_exists("core/controller/".$plugin."Controller.php")){
			include("core/controller/".$plugin."Controller.php");
			$name = $plugin."Controller";
			new $name($page);
		}else if(file_exists("data/modules/".$plugin."/starter.dat")){
			include("core/lib/ModuleLoader.php");
			new ModuleLoader($plugin, $this->getInputString("page", null));
		}
	}
	protected function getInputString($name, $default_value = "", $format = "GPCS"){
		$format_defines = array ('G'=>'_GET','P'=>'_POST','C'=>'_COOKIE','S'=>'_SESSION','R'=>'_REQUEST','F'=>'_FILES',);
		preg_match_all("/[G|P|C|S|R|F]/", $format, $matches);
		foreach ($matches[0] as $k=>$glb){
		    if ( isset ($GLOBALS[$format_defines[$glb]][$name])){   
			return htmlentities ( trim ( $GLOBALS[$format_defines[$glb]][$name] ) , ENT_QUOTES ) ;
		    }
		}
		return $default_value;
	} 
} ?>