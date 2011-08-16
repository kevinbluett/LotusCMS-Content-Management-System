<?php
include("core/lib/io.php");

class Model extends Observable{
	
	protected $p;
	
	//Controller Variable Local
	protected $con;
	
	//Form variable
	public $form;
	
	//Form Function
	public $formProcess;
	
	//The form values
	public $formValues;
	
	public $io;
	
	/**
	 * Set the local Controller
	 */
	public function setController($con){
		$this->con = $con;
		
		if(!isset($this->io)){
			$this->io = new InputOutput();	
		}
	}
	
	/**
	 * Get the local Controller
	 */
	public function getController(){
		return $this->con;
	}
	
	/**
	 * Check login and create login page if requested otherwise say access deinied.
	 * param $login boolean, true - display login form if not logged in, otherwise display access denied message.
	 */
	public function checkLogin($login, $infoLoad = false){

		//if not logged in
		if(!$this->loggedIn())
		{	
			//Show a login form if asked for
			if($login)
			{
				//Create new page system
				$p = new Page(null, false);
				
				//Create login system
				$l = new Login();
				
				//Create a login form for this anonymous user
				$l->createLogin($p, $this->getController()->getView(), true);
				
				//NEVER GETS HERE LOGIN CLASS EXITS
				
				//Display the page.
				$p->displayPage();
				
				//Quit the system
				$this->getController()->freeze_all();	
			}
			else
			{
				//Set the title of the page
				$this->getController()->getView()->setContentTitle(file_get_contents("core/fragments/access_denied_title.phtml"));
				
				//Set the content for access denied
				$this->getController()->getView()->setContent(file_get_contents("core/fragments/access_denied_content.phtml"));
				
				//Set to 2 columns
				$this->getController()->getView()->setTwoColumn();
				
				$this->getController()->getView()->setLeftTitle("");
				$this->getController()->getView()->setLeftContent("");
				
				//Display the page.
				$this->getController()->getView()->displayPage();
				
				//Quit the system
				$this->getController()->freeze_all();
			}
		}
		else
		{
			return true;
		}
	}
	
	/**
	 * Check the login tokens
	 */
	protected function loggedIn(){
		
		if($this->getInputString('login', false, 'S')==true)
		{
			return true;	
		}
		
		return false;
	}
	
	/**
	 * Returns the page requested by the system
	 */
	public function getPageRequest(){
		return $this->getInputString("page");	
	}
	
	/**
	 * Returns the page requested by the system
	 */
	public function getActiveRequest(){
		return $this->getInputString("active", null, "G");	
	}
	
	/**
	 * Returns the active locale
	 * Kevin Bluett July 2011
	 */
	public function getActiveLocale(){
		return $this->getController()->getView()->getLocale();	
	}
	
	/**
	 * Returns the username of the logged in User
	 */
	protected function getCurrentUser()
	{
		//TODO
	}
	
	/**
	 * Returns the group of the logged in User
	 */
	protected function getCurrentUserGroup()
	{
		//TODO
	}
	
	/**
	 * Sets the session locale
	 */
	public function setLocale(){
		
		//Failsafe incase the install did not complete correctly.
		if(file_exists("data/config/locale.dat")){
			//Gets the saved Locale
			$_SESSION['locale'] = $this->openFile("data/config/locale.dat");
		}else{
			/******* START TO BE REMOVED IN 4.0 MILESTONE - ADDED 3.5 *******/
			
			//Create new default Locale
			$this->saveFile("data/config/locale.dat", "en");
			
			/******* END TO BE REMOVED IN 4.0 MILESTONE - ADDED 3.5 *******/
		}
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
		    return htmlentities ( trim ( $GLOBALS[$format_defines[$glb]][$name] ) , ENT_NOQUOTES ) ;
		}
	    }
	  
	    return $default_value;
	} 
	
	
	/**
	 * Save the set file, with the requested content.
	 */
	public function saveFile($m, $n, $o = 0){
		$this->io->saveFile($m, $n, $o = 0);
	}
	
	/**
	 * Allows direct from function exception throwing.
	 */	
	function throwException($message = null,$code = null) {
	    throw new Exception($message,$code);
	}

	public function openFile($n){
	    $fd=fopen($n,"r") or die('Error 11: File Cannot be opened, '.$n);
	    $fs=fread($fd,filesize($n));
	    fclose($fd);
	    return $fs;
	}
	
	public function listFiles($start_dir){
		return $this->io->listFiles($start_dir);
	}
	
   	public function destroyDir($dir, $virtual = false){
		return $this->io->destroyDir($dir, $virtual);
	}
}

?>