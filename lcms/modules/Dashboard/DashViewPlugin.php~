<?php
 
/**
 * Dashboard
 */
class DashboardView extends Observer{
	
	protected $view;
	
	public function DashboardView(){

	}
	
	/**
	 * Sets up the listening system
	 */
	public function setupObserver(& $view){
		Observer::Observer($view);
		
		$this->view = $view;
	}

    /**
    * Implement the parent update() method checking the state
    * of the observable subject
    * @return void
    */
    function update () {
        if ( $this->subject->getState() == 'FINISHED_DASH' ) {
        	$this->loadDash();
        }
    }
    
    /**
     * Loads a jQuery based dashboard.
     */
    private function loadDash(){
    	
    	//Get the Locale
    	include_once("core/lib/Locale.php");
    	
    	$l = new Locale();
    	
    	//Checks for update
    	$data = $this->checkForUpdate();
    	
    	//If no update available.
    	if(empty($data)){
    		$this->view->setErrorData("success", $l->localize("Welcome to the new LotusCMS 3 Series, visit the <a href='http://forum.lotuscms.org'>LotusCMS Forum</a> for help / questions"));
    	}else{
    		$this->view->setErrorData("error", $data);	
    	}
    	
    	//Buttons.
    	$out = "<fieldset><table width='10%'><tr>";
    	
    	//Count items
    	$i = 0;
    	
    	if($this->hasBlog()){
    		$i++;
    		//Creates an icon for the blog
    		$out .= $this->createIcon(
    									"index.php?system=Modules&page=admin&active=Blog", 
    									"modules/Blog/logo.png",
    									$l->localize("Blog")
    								 );
    	}
    	
    	if($this->hasMenu()){
    		$i++;
    		//Creates icon for the menu
    		$out .= $this->createIcon(
    									"index.php?system=Modules&page=admin&active=Menu", 
    									"modules/Menu/logo.png",
    									$l->localize("Menu")
    								 );
    	}
    	
    	if($this->hasBackup()){
    		$i++;
    		//Creates icon for the menu
    		$out .= $this->createIcon(
    									"index.php?system=Modules&page=admin&active=Backup", 
    									"modules/Backup/logo.png",
    									$l->localize("Backup")
    								 );
    	}
    	
    	//Adds an icon for the template
    	if($i<6){
    		$i++;
      		$out .= $this->createIcon(
    									"index.php?system=Template&page=change", 
    									"style/comps/admin/img/templates.png",
    									$l->localize("Styles")
    								 );
    	}
    	
    	$out .= "</tr></table></fieldset>";
    	
    	//Working module update check. Takes Aggeeess to load - due to up to 8 HTTP requests sent to LotusCMS.org.
    	 $extra = '<script type="text/javascript">
    		<!--
    		$(document).ready(function() {
    			$("#loadNews").load("index.php?system=Modules&page=admin&active=Dashboard&req=news");
    			$(window).load(
    function() {';
    			
    	$plugs = $this->getPlugins();
    	
    	if(!file_exists("data/modules/Dashboard/stopmodulecheck.dat")){
    		
    		$requests = "";
    		
	    	for($k = 0;$k < count($plugs);$k++){
		    	
		    	if($k!=0){
		    		$requests .= "|";	
		    	}
		    	
		    	$requests .= $plugs[$k];
	    	}
	    	
	    	$extra .= '$("#checkModules").load("index.php?system=Modules&page=admin&active=Dashboard&req=checkModules&id='.$requests.'");';
	    	$out .= "<div id='checkModules'><p style='border: 1px solid #e3e3e3; font-size:11px;'><img style='float:left;padding-top:3px;padding-left:4px;' src='modules/Dashboard/loading.gif' height='15px' width='15px'/>&nbsp;<span style='display:block;float:left;width: 400px;height:20px;margin-left:20px;'>".$l->localize("Checking for module updates")."…</span><span style='display:block;width:60px;height:20px;margin-right:6px;text-align:right;float:right'><a style='color:#b2b2b2;text-decoration:none;' href='index.php?system=Modules&page=admin&active=Dashboard&req=disableModCheck'><!--Disable--></a></span></p></div>";
    	}
    			
    	$this->getView()->getMeta()->appendExtra($extra.'    } );});
    	--></script>');
    	
    	$out .= "<fieldset>";
    	$out .= "<p id='loadNews'><strong>".$l->localize("Loading News...")."</strong></p>";
    	
    	$out .= "</fieldset>";
    	
    	//Sets the content of the dashboard.
    	$this->getView()->setContent($out);
    }
    
    private function hasBlog(){
    	return file_exists("data/modules/Blog/starter.dat");	
    }
    
    private function hasMenu(){
    	return is_dir("data/modules/Menu");	
    }
    
    private function hasBackup(){
    	return is_dir("data/modules/Backup");	
    }
    
    /**
     *
     */
    private function checkForUpdate(){
    	$data = "";
    	if(!isset($_SESSION['versionCheck'])){
	    	include("core/lib/RemoteFiles.php");
	    	
	    	$rf = new RemoteFiles();
	    	
	    	//Get version of CMS
	    	$v = $this->getView()->getController()->getVersion();
	    	
	    	$data = $rf->getURL("http://update.lotuscms.org/lcms-3-series/updateCheck.php?v=".$v."&lang=".$this->getView()->getLocale());
	    	
	    	$data = explode("%%", $data);
	    	
	    	$_SESSION['versionCheck'] = $data[1];
	    	$_SESSION['vnumber'] = $data[0];
	    	
	    	
    	}else{
    		$data = array("",$_SESSION['versionCheck']);
    	}
    	return $data[1];
    }
    
    /**
     * Gets the view
     */
    private function getView(){
    	return $this->view;	
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
     * Creates a block with image and title
     */
    private function createIcon($url, $imgUrl, $title){
    	return "<td><div style='background-color: #ffffff;width: 97px;border: 1px solid #e3e3e3;margin-bottom: 5px;'><a href='".$url."'><img style='padding-left: 4px;border-style: none;' src='".$imgUrl."' alt='Module Image' /></a><br /><p style='text-align:center; font-size: 13.5px;margin-top: 0px;'><a href='".$url."'>".$title."</a></p></div></td>";
    }
}
 
?>
