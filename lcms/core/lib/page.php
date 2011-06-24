<?php

//Include Login
include_once("core/lib/Login.php");
include_once("core/view/LoginForm.php");
include_once("core/view/RunDesign.php");
include_once("core/lib/Meta.php");
include_once("core/lib/Observable.php");


/**
 * Creates templates from scratch
 */
class Page extends Observable{
	
	//Title of page
	protected $titlePage;
	
	//Title of content
	protected $titleName;
	
	//Title of leftbar
	protected $leftTitle;
	
	//Content of the leftBar
	protected $leftContent;
	
	//Content of page
	protected $content;
	
	//The Actual content of the page
	protected $thePage;
	
	//The number of columns on this page
	protected $columns;
	
	//The actual logged in user
	public $user;
	
	//The root location of the file
	public $base;
	
	//The login variable
	public $login;
	
	//Error Messages
	protected $error_message;
	
	//Type of Error Message
	protected $error_type;
	
	//Set Redirect
	protected $redirect;
	
	//Set Meta Data
	public $meta;
	
	//Template overriding
	protected $tempOverride;
	
	/**
	 * Intially sets up the page
	 */
	public function setupPage($login = false){
		
		//Allow Plugins.
		Observable::Observable();
	
		$this->login = $login;
		
		//Initial value for each variable
		$this->titlePage = $this->openFile("data/config/site_title.dat");
		$this->titleName = "Impropery Loaded Page";
		$this->leftTitle = "Left Column";
		$this->leftContent = "";
		$this->content = "";
		$this->thePage = "";
		$this->columns = 2;
		$this->tempOverride = null;
		
		if(empty($this->meta)){
			//Setup Meta Class
			$this->meta = new Meta();
		}
		
		$this->error_type = null;
		$this->redirect = null;
		
		//Setup the location of this file
		$this->base = "";
		
		//Get left title		
		$this->setLeftTitle("");
		
		//Get left content
		$this->setLeftContent("");
	
		//Check if any error messages are to be displayed
		$this->checkError();
	}	
	
	/**
	 * Generates and displays the page
	 */
	public function displayPage(){
		
		//If a redirect has been setup
		if(!empty($this->redirect))
		{
			//Setup redirect
			header("Location: ".$this->redirect);
			
			//Quit the system
			exit;
		}
		
		//Generate the Page
		$this->generatePage();
		
		//Print the page
		print $this->thePage;
	}
	
	/**
	 * Sets a redirect instead of displaying a page
	 */
	public function setRedirect($location){
		$this->redirect = $location;
	}
	
	/**
	 * Gets the Site title
	 */
	public function getSiteTitle(){
		return $this->titlePage;
	}
	
	/**
	 * Gets the content title
	 */
	public function getContentTitle(){
		return $this->titleName;
	}
	
	/**
	 * Get the number of columns in this page
	 */
	public function getColumns(){
		return $this->column;	
	}
	
	/**
	 * Gets the left title
	 */
	public function getLeftTitle(){
		return $this->leftTitle;
	}
	
	/**
	 * Gets the left content
	 */
	public function getLeftContent(){
		return $this->leftContent;
	}
	
	/**
	 * Gets the content
	 */
	public function getContent(){
		return $this->content;
	}
	
	/**
	 * Sets the page title
	 */
	public function setSiteTitle($text){
		$this->titlePage = $text;
	}
	
	/**
	 * Generates the page from the supplied data
	 */
	private function generatePage(){
		
		if(empty($this->tempOverride))
		{
			//Run the Design
			$r = new RunDesign($this);
		}
		else
		{
			$r = new RunDesign($this, $this->tempOverride);
		}
		
		//Generate the page from the design
		$this->thePage = $r->generate();
	}
	
	/**
	 * Sets the content title
	 */
	public function setContentTitle($text){
		$this->titleName = $text;
	}
	
	/**
	 * Sets the left title
	 */
	public function setLeftTitle($text){
		$this->leftTitle = $text;
	}
	
	/**
	 * Sets the left content
	 */
	public function setLeftContent($text){
		$this->leftContent = $text;
	}
	
	/**
	 * Sets the content
	 */
	public function setContent($text){
		$this->content = $text;
	}
	
	/**
	 * Appends more to the content
	 */
	public function appendContent($text){
		$this->content = $this->content.$text;
	}
	
	/**
	 * Sets one column data
	 */
	public function setOneColumn(){
		$this->column = 1;
	}
	
	/**
	 * Sets two column data
	 */
	public function setTwoColumn(){
		$this->column = 2;
	}
	
	/**
	 * Set the number of columns in this page
	 */
	public function setColumn($cols){
		$this->column = $cols;
	}
	
	/**
	 * Checks for any session set error messages
	 */
	public function checkError(){
		
		$errorType = $this->getInputString("ERROR_TYPE","","S");
		$error = $this->getInputString("ERROR_MESSAGE","","S");
		
		if(!empty($error)&&!empty($errorType)){
			
			//Set the error message
			$this->error_message = $error;	
			
			//Set error type
			$this->error_type = $errorType;
			
			//Unset Message
			unset($_SESSION['ERROR_TYPE']);
			unset($_SESSION['ERROR_MESSAGE']);
		}
	}
	
	/**
	 * Generates and returns error messages
	 */
	public function getErrorData(){
		//If error exists publish it
		if($this->error_type!=null)
		{
			//Creates and returns the full error message 
			return "<p class='msg ".$this->error_type."'>".$this->error_message."</p>";
		}
	}
	
	/**
	 * Allows quick set of error variables
	 */
	public function setErrorData($type, $msg){
		$this->error_type = $type;
		$this->error_message = $msg;
	}
	
	/**
	 * Returns a global variable
	 */
	public function getInputString($name, $default_value = "", $format = "GPCS")
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
     * Returns the contents of the requested page
     */
    public function openFile($n){
    	$fd=fopen($n,"r") or die('Error 22: Failed opening file: '.$n.', in Library Paging systems.');
		$fs=fread($fd,filesize($n))  or die('Error 22: Failed opening file: '.$n.', in Library Paging systems.');
		fclose($fd);
		return $fs;
    }
    
    /**
     * Gets the compilied Menu
     */
    public function getMenu($regen){
    	
    	if(!$regen)
    	{
	    	//Open and return Menu
	    	return $this->openFile("data/modules/Menu/compiled.dat");
    	}
    	else
    	{
    		//Active Recompile the menu
    		return $this->generateMenu();
    	}	
    }
    
    /**
     * Gets the compilied Menu
     */
    protected function generateMenu(){
    	//Recompiles the menu from scratch with identifier "active"
    	include("core/lib/MenuRender.php");
    	
    	$mr = new MenuRender();
    	
    	return $mr->compileMenu($this->getInputString("page"));
    }
    
    /**
     * Sets system up to return Meta data
     */
    public function getExtraMeta(){
		print $this->meta->getExtra();
    }
    
    /**
     * Sets system up to return Meta data
     */
    public function getMeta(){
		return $this->meta;
    }
    
    /**
     * Sets system up to return Meta data
     */
    public function getMetaKeywords(){
    	//Setup Meta Class
    	return $this->getMeta()->getKeywords();
    }
    
    /**
     * Sets system up to return Meta data
     */
    public function getMetaDescription(){
    	//Setup Meta Class
    	return $this->getMeta()->getDescription();
    }

	/**
	 * Override the default paging and dump content
	 */
	public function overridePaging($content){
		
		//Print the overriding content
		print $content;
		
		//Stop Processing PHP
		exit;
	}
	
	/**
	 * Allows the override of template to a different template
	 */
	public function setTemplate($temp){
		$this->tempOverride = $temp;
	}
	
	/**
	 * Generates and returns the generated page as a string
	 */
	public function softDisplayPage(){
		
		//Generate the Page
		$this->generatePage();
		
		//Print the page
		return $this->thePage;
	}
	
}

?>