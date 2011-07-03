<?php
include_once("core/lib/Observable.php");

class Controller extends Observable{
	
	//Class View
	protected $cv;
	
	//Class Model
	protected $cm;
	
	//The user variable
	protected $user;
	
	//The page setting system
	protected $p;
	
	//Protecetd
	protected $request;
	
	//Array of the 
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
	 */
	public function setup($title){
		
		//Setup Classes
		$this->setupClasses();
		
		//Setup paging system
		$this->setupPaging();
		
		//Setup as array
		$this->req = array();
		
		//Set title of the Dashbaord.
		$this->p->setContentTitle($title);
		
		//Set to two columns
		$this->p->setTwoColumn();
		
	}
	
	/**
	 *	Setup the required classes
	 */
	protected function setupClasses(){
		
		//Get class names
		$className = $this->getSystem();
		
		//View
		include("core/view/".$className."View.php");
		
		//Model
		include("core/model/".$className."Model.php");
		
		//Setup the classview
		eval("\$this->cv = new ".$className."View();");
		
		//Set this as controller
		$this->cv->setController($this);
		
		//Setup the classmodel
		eval("\$this->cm = new ".$className."Model();");
		
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
		
		$reqy = (String)$this->request;
		
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
		if(empty($reqy))
		{
			//Run the default request
			$this->defaultRequest();
			
			//This breaks processing of all the following requests
			return false;
		}
		
		//Work through all the set request
		for($i = 0;$i < count($this->req);$i++)
		{	
			//Process the request
			if($this->req[$i]==$reqy)
			{
				//Create Request
				$process = "\$this->".($this->req[$i])."Request();";
				
				//Process the request
				eval($process);
				
				//Set processed
				$found = true;
				
				//Stop the loop
				break;
			}	
		}
		
		//Page not found
		if(!$found)
		{
			//Get the 404 page
			$this->cv->noPage();
		}
	}
	
	/**
	 * ID
	 */
	public function getID(){
		return $this->id;	
	}	
	
	/**
	 * Page Request
	 */
	public function setPageRequest($req){
		$this->request = $req;	
	}

	
	/**
	 * ID
	 */
	public function setID($id){
		$this->id = $id;	
	}
	
	/**
	 * Get Model
	 */
	public function getModel(){
		//Returns model
		return $this->cm;
	}
	
	/**
	 * Get View
	 */
	public function getView(){
		//Returns model
		return $this->cv;
	}
	
	
	/**
	 * Exit the system
	 */
	public function freeze_all(){
		exit;	
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
                return $GLOBALS[$format_defines[$glb]][$name];
            }
        }
      
        return $default_value;
    } 
    
    /**
     * Sets up the basic variables
     */
    protected function varSetup(){
    	//Setup ID
		$this->setID($this->getInputString("id", -1, "G"));
		
		//Set Page request
		$this->setPageRequest($this->getInputString("page", "", "G")); 
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
					
					$c->setupObserver($this->getModel());
				}
				
				if(file_exists("modules/".$plugs[$i]."/".$system."ViewPlugin.php")){
					include_once("modules/".$plugs[$i]."/".$system."ViewPlugin.php");
					
					$c = $plugs[$i]."View";
					$c = new $c;
					
					$c->setupObserver($this->getView());
				}
			}
		}
    }
}

?>