<?php
 
/**
 * Dashboard Abs
 */
class Abstraction{
	
	/**
	 * This function checks for an update for a particular module at the lotuscms website.
	 */
	public function checkForUpdate($req){
		include_once("core/lib/RemoteFiles.php");
		include_once("core/lib/ModuleInfo.php");
		include_once("modules/".$req."/".$req."ModuleInfo.php");
	    	
	    $rf = new RemoteFiles();
	    $mi = new ModuleInfo();
	    	
	    //Get version of Module
	    $v = $mi->getVersion();
	    	
	    $data = $rf->getURL("http://cdn.modules.lotuscms.org/lcms-3-series/versioncontrol/versioncheck.php?module=".$req);
	    
	    $mod = None;
	    
	    if(!empty($data)){
	    	if($data!=$v){
	    		return $v;	
	    	}
	    }else{
	    	return false;	
	    }
	}
}
 
?>
