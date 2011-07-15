<?php
/**
 * This class tests if any updates exist for the requested module.
 */
class TemplateUpdateCheck{
	
	public function getVersionArray($ap){
		$req = "";
			
		for($i = 0;$i < count($ap); $i++){
			
			if($i!=0)
				$req .= "|";
				
			$req .= str_replace(".php", "", $ap[$i]);
		}
		
		//Return the module update
		return $this->checkForModuleUpdate($req);
	}
	
	/**
	 * This function checks for an update for a particular module at the lotuscms website.
	 */
	protected function checkForModuleUpdate($req){
		if(isset($_SESSION['TEM_LCMS_ORG_RESPONSE'])){
			return $_SESSION['TEM_LCMS_ORG_RESPONSE'];
		}else{
		    include_once("core/lib/RemoteFiles.php");
		    include_once("core/lib/ModuleInfo.php");
		    
		    //Get information on update status of each plugin.
		    
		    //Setup remote file requestor
		    $rf = new RemoteFiles();
		    
		    //Get site version
		    $version = file_get_contents("data/config/site_version.dat");
		    
		    $data = "";
		    
		    if(isset($_SESSION['TEM_LCMS_ORG_RESPONSE_URL'])){	  
		    	    if(!empty($_SESSION['TEM_LCMS_ORG_RESPONSE_URL'])){
		    	    	    $data = $_SESSION['TEM_LCMS_ORG_RESPONSE_URL'];
		    	    }
		    }
		    
		    if(empty($data))
		    {
		    	    //Collect information on module updates.
		    	    $data = $rf->getURL("http://styles.lotuscms.org/lcms-3-styles/checkTempString.php?t=$req&v=$version");
		    }

		    $_SESSION['TEM_LCMS_ORG_RESPONSE_URL'] = $data;
		    	    
		    //Output
		    $out = "";
		    
		    $modArray = array();
		    
		    //Get list of plugins
		    $m = explode("|", $data);
		    
		    //Loop through each update response.
		    for($i = 0;$i < count($m); $i++)
		    {
		    	//Split name & version number
		    	$getFull = explode(":", $m[$i]);
		    		
		    	//Get full number of plugin
		    	$nv = (float)$getFull[1];
		    	
		    	//Get current version number
		    	$cv = $this->getTemplateVersionNumber($getFull[0]);
		    	
		    	//If new version is actually newer than installed.
		    	if($cv<$nv){
		    		$modArray[$getFull[0]] = true; 
		    	}else{
		    		$modArray[$getFull[0]] = false; 
		    	}
		    }
		    
		    //Response
		    $_SESSION['TEM_LCMS_ORG_RESPONSE'] = $modArray;
		    
		    return $modArray;
		}
	}
	
	/**
	 * Collects the current version of a template from the temp directory. Takes 0.1 as version if none available.
	 */
	public function getTemplateVersionNumber($tempName){
		
		if(file_exists("style/comps/$tempName/version.dat")){
			return (float) (file_get_contents("style/comps/$tempName/version.dat"));
		}else{
			return (float)0.1;	
		}
		
	}
}
 
?>