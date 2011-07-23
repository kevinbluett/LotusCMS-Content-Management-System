<?php
include_once("core/lib/Locale.php");
/**
 * Dashboard Abs
 */
class Abstraction{
	
	/**
	 * This function checks for an update for a particular module at the lotuscms website.
	 */
	public function checkForModuleUpdate($req){
		if(isset($_SESSION['MODUPDATE'])){
			print $_SESSION['MODUPDATE'];
			exit;
		}else{
		    include_once("core/lib/RemoteFiles.php");
		    include_once("core/lib/ModuleInfo.php");
		    
		    //Get information on update status of each plugin.
		    
		    //Setup remote file requestor
		    $rf = new RemoteFiles();
		    
		    //Get site version
		    $version = file_get_contents("data/config/site_version.dat");
		    
		    $data = "";
		    
		    if(isset($_SESSION['MOD_LCMS_ORG_RESPONSE_URL'])){
		    	$data = $_SESSION['MOD_LCMS_ORG_RESPONSE_URL'];		    
		    }
		    
		    if(file_exists("data/lcmscache/mod_update.dat")&&empty($data)){
		    	    include_once("core/lib/io.php");
		    	    $io = new InputOutput();
		    	    	    
		    	    $data = $io->openFile("data/lcmscache/mod_update.dat");
		    	    $_SESSION['MOD_LCMS_ORG_RESPONSE_URL'] = $data;
		    	    
		    	    //Finally delete the cache
		    	    unlink("data/lcmscache/mod_update.dat");
		    }else{
		    
		    	    //Collect information on module updates.
		    	$data = $rf->getURL("http://cdn.modules.lotuscms.org/lcms-3-series/versioncontrol/allversioncheck.php?m=$req&v=$version");
		    }
		    
		    //Save response in session in case it is required later
		    $_SESSION['MOD_LCMS_ORG_RESPONSE_URL'] = $data;
		    
		    //Output
		    $out = "";
		    
		    //Get list of plugins
		    $m = explode("|", $data);
		    
		    $l = new Locale();
		    
		    
		    //Loop through each update response.
		    for($i = 0;$i < count($m); $i++)
		    {
		    	//Split name & version number
		    	$getFull = explode(":", $m[$i]);
		    		
		    	//Get full number of plugin
		    	$nv = (float)$getFull[1];
		    	
		    	//Get current version
		    	include_once("modules/".$getFull[0]."/".$getFull[0]."ModuleInfo.php");
		    	
		    	$name = $getFull[0]."ModuleInfo";
		    	
		    	//Load info class
		    	$info = new $name();
		    	
		    	//Load info
		    	$info->ModuleInfo();
		    	
		    	//Get current version number
		    	$cv = (float)$info->getVersion();
		    	
		    	//If new version is actually newer than installed.
		    	if($cv<$nv){
		    		$out .= '<p class="msg error">'.$l->localize("Module").': '.$getFull[0].' '.$l->localize("is out of date").' ['.$l->localize("version").': '.$getFull[1].']. <a href="index.php?system=Modules&page=updateCheck&req='.$getFull[0].'">'.$l->localize("Update").'</a></p>';
		    	}
		    }
			
			print $out;
			$_SESSION['MODUPDATE'] = $out;
			exit;
		}
	}
}
 
?>
