<?php
include_once("core/lib/Observable.php");
include_once("core/view/view.php");
include_once("core/model/model.php");

class Controller extends Observable{
	
	//Class View
	protected $cv;
	
	//Class Model
	protected $cm;
	
	//The user variable
	protected $user;
	
	//The page setting system
	protected $p;
	
	//Request
	protected $request;
	
	//Array of the reqs
	protected $req;
	
	//Controller ID
	protected $id;
	
	//System Name
	protected $system;
	
	//Wildcard
	protected $wildCard;
	
	/**
	 * Get the paging system
	 */
	public function getPaging(){
		return $this->p;	
	}
	
	/**
	 * Get the paging system
	 */
	public function setPaging($page){
		$this->p = $page;	
	}
	
	/**
	 * Set the system
	 */
	public function setSystem($sys){
		$sys = str_replace(";","",$sys);
		$this->system = htmlentities ( trim ($sys));	
	}
	
	/**
	 * set the system
	 */
	public function getSystem(){
		return $this->system;	
	}
	
	/**
	 * Setup the required page
	 * Kevin Bluett July 2011
	 * @param $system 		=> Name of the Controller eg. DashController must be inputted as "Dash" 
	 * @param $title 		=> Title of the page to be setup
	 * @param $template 		=> The template to be loaded by the paging systems. Defaults to "admin"
	 * @param $auth			=> The user level required to access page. Leave blank for no login, '*' for any user a/c, 'user_group' for a specific user group
	 */
	public function setup($system, $title, $template = "admin", $auth = "*"){
		
		//Sets the name of the other classes
		$this->setSystem($system);

		//Setup Classes
		$this->setupClasses();
		
		//Setup basic variables
		$this->varSetup();
		
		//Setup paging system
		$this->setupPaging();
		
		//Setup as array
		$this->req = array();
		
		//Set title of the Dashbaord.
		$this->p->setContentTitle($title);
		
		//Set to two columns
		$this->p->setTwoColumn();
		
		//Set to Administration template
		$this->getView()->setTemplate($template);
		
		//Check for Plugins
		$this->loadPlugins();
		
		//Set the requests accepted
		$this->putRequests();
		
		if(!empty($auth)){
			//Load the requirement for all pages to be password protected by user management
			$this->requireAuth();
		}
		
		//Process Request
		$this->processRequest();
		
		$this->displayPage();
	}
	
	/**
	 *	Setup the required classes
	 */
	protected function setupClasses(){
		
		//Get class names
		$className = $this->getSystem();
		
		//View
		include_once("core/view/".$className."View.php");
		
		//Model
		include_once("core/model/".$className."Model.php");
		
		$load = $className."View";

		//Setup the classview
		$this->cv = new $load();
		
		//Set this as controller
		$this->cv->setController($this);
		
		//Set View
		$this->cv->setView($this->cv);
		
		$load = $className."Model";
		
		//Setup the classmodel
		$this->cm = new $load();
		
		//Set this as controller
		$this->cm->setController($this);
		
	}
	
	/**
	 * Setup the paging system if required
	 */
	protected function setupPaging(){
		//Setup the required page
		$this->cv->setupPage();	
		
		$this->setPaging($this->cv);
	}
	
	
	/**
	 * Set the requests
	 */
	public function setRequests($requests){
		
		$this->wildCard = false;
		
		//Set the requests
		$this->req = $requests;
	}
	
	/**
	 * Allow Wildcard Request
	 */
	public function setWildCardMode($req){
		
		//Set the wildcard request setting.
		$this->wildCard = $req;
		
	}
	
	/**
	 * Add Request
	 */
	public function addRequest($request){
		
		//Add Array Push
		$this->req = array_push($this->req, $request);
		
	}
	
	/**
	 * Empty All request
	 */
	public function emptyRequests(){
		//Empty Array
		$this->req = array();
	}
		
	/**
	 * Display the required page that was setup
	 */	
	public function displayPage(){
		if(isset($this->p))
		{
			$this->p->displayPage();	
		}	
	}
		
	/**
	 * Process the page request
	 */
	public function processRequest(){
		$found = false;
		
		$localRequest = (String)$this->request;
		
		//Set the Locale
		$this->getModel()->setLocale();
		
		//If in wildcard mode process as wildcard override.
		if($this->wildCard){
			
			//Run Wildcard
			$this->wildCardRequest();
			
			//Break from this function
			return false;
		}
		
		//Requests the default system
		if(empty($localRequest))
		{
			if(method_exists($this, "defaultRequest")){
				$this->defaultRequest();
			} else {
				$this->cv->noPage();
			}
		}
		
		//Process the request
		if(in_array($localRequest, $this->req))
		{
			$load = $localRequest."Request";
			
			$this->$load();
			
			$found = true;
		}
		
		//Page not found
		if(!$found)
		{
			//Get the 404 page
			$this->cv->noPage();
		}
	}
	
	public function getID(){
		return $this->id;	
	}	
	
	public function setPageRequest($req){
		$this->request = $req;	
	}

	
	public function setID($id){
		$this->id = $id;	
	}
	
	public function getModel(){
		//Returns model
		return $this->cm;
	}
	
	public function getView(){
		//Returns model
		return $this->cv;
	}
	
	public function freeze_all(){
		exit;	
	}
	

    
	/**
	 * Sets up the basic variables
	 */
	protected function varSetup(){
		Observable::Observable();
		
		//Setup ID
		$this->setID($this->getModel()->getInputString("id", -1, "G"));
		
		//Set Page request
		$this->setPageRequest($this->getModel()->getInputString("page", "", "G")); 
	}
    
	/**
      	 * Dumps a variable for better viewing and exits;
     	 */
     	public function error_dump($var){
		
		//Print variable in preformatted method
		print "<pre>";
		
		//Dump Variable Out
		var_dump($var);
			
		//Print End of the preformatted section
		print "</pre>";
			
		//Stop Everything	
		$this->freeze_all();	
	}
    
    /**
     * Returns the directory containing all the pages.
     */
    public function getPageDirectory(){
    	return "data/pages";	
    }
    
    /** 
     * Gets the version running this CMS.
     */
    public function getVersion(){
    	return $this->getModel()->openFile("data/config/site_version.dat");
    }
    
    /**
     * Restricted access to the pages via defined usergroup.
     */
    protected function requireAuth($user = "*"){
    	
	// Set the state and tell plugins.
	$this->setState('REQUIRE_AUTH');
    	
    	if($user=="*"){
			if(!$this->getModel()->checkLogin(true)){
				//Login status check failed.
				$this->freeze_all();
			}
    	}else{
			if(!$this->getModel()->checkLogin(true)){
				//Login status check failed.
				$this->freeze_all();
			}else{
				//Success, but now be need to check usertype.
				if(!$this->checkUserLevel($user)){
					//Get Access Denied Page for his level
					$cont = $this->openFile("core/fragments/users/access_denied.phtml");
				
					//Set the content
					$this->getController()->getView()->setContentTitle("Access Denied");
					
					//Set the content
					$this->getController()->getView()->setContent($cont);
					
					//Display page
					$this->getController()->displayPage();
					
					//Stop All Processing
					$this->getController()->freeze_all();
				}
			}
    	}
    }
    
   	/**
	 * Requires logged in user to be administrator
	 */
	public function requireAdministrator(){
		$this->setState('REQUIRE_ADMINISTRATOR');
		
		$this->requireAuth("administrator");
	}
    
   	/**
	 * Checks the user access level
	 */
	protected function checkUserLevel($lvl){
		$this->setState('CHECKING_USER_LVL');
		
		//Get Access level (Force userage of Session variable - otherwise attack is possible via GET/POST)
		$access = $this->getModel()->getInputString("access_lvl", "", "S");
			
		if($lvl!=$access)
		{
			//The User does not have the rights to access this area
			return false;	
		}
		//Access Level OK
		else
		{
			//Access OK
			return true;	
		}
	}
    
    /**
     * Loads any plugins for for use in observation system.
     */ 
    public function loadPlugins(){
	
		//Get the system.
		$system = $this->getSystem();
		
		//Only Load plugins if the loading file exists.
		if(file_exists("data/config/modules/".$system.".dat")){
			//Get Plugin Loading File
			$plugs = $this->getModel()->openFile("data/config/modules/".$system.".dat");
			
			//Get containing plugs
			$plugs = explode("|", $plugs);
			
			//Load Each individual plugs
			for($i = 0; $i < count($plugs); $i++){
				
				//Include the plugin
				include_once("core/lib/Observer.php");
				
				if(file_exists("modules/".$plugs[$i]."/".$system."ControllerPlugin.php")){
					include_once("modules/".$plugs[$i]."/".$system."ControllerPlugin.php");
					
					$c = $plugs[$i]."Controller";
					$c = new $c;
					
					$c->setupObserver($this);
				}
				
				if(file_exists("modules/".$plugs[$i]."/".$system."ModelPlugin.php")){
					include_once("modules/".$plugs[$i]."/".$system."ModelPlugin.php");
					
					$c = $plugs[$i]."Model";
					$c = new $c;
					
					$model = $this->getModel();
					
					$c->setupObserver($model);
				}
				
				if(file_exists("modules/".$plugs[$i]."/".$system."ViewPlugin.php")){
					include_once("modules/".$plugs[$i]."/".$system."ViewPlugin.php");
					
					$c = $plugs[$i]."View";
					$c = new $c;
					
					$view = $this->getView();
					
					$c->setupObserver($view);
				}
			}
		}
    }
}

?>