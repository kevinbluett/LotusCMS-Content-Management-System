<?php

class Login{

	/**
	 * Creates Login
	 * @param No Parameters
	 */
	public function Login(){
	
	}	
	
	/**
	 * Create the login form
	 */
	public function createLogin($p){
		
		$out = $p->openFile("style/comps/admin/login.phtml");
		
		$errorType = $this->getInputString("ERROR_TYPE","","S");
		$error = $this->getInputString("ERROR_MESSAGE","","S");
		
		if(!empty($error)&&!empty($errorType)){
			
			//Unset Message
			unset($_SESSION['ERROR_TYPE']);
			unset($_SESSION['ERROR_MESSAGE']);
			
			$out = str_replace("%MESSAGE%", "<p class='msg ".$error_type."'>".$error_message."</p>", $out);
		}else{
			$out = str_replace("%MESSAGE%", "", $out);	
		}
		
		//This quits the normal login form,
		$p->overridePaging($out);
		/******* freeze_all() called ********/
		//Never Ever gets to Here
		
		//Creates the login form system
		$l = new LoginForm();
		
		//create the login form
		$content = $l->createForm();
		
		//Setup Page
		$p->setupPage("Administration");
		
		//Set the title of the page to Login
		$p->setContentTitle("Login to Member's Area");
		
		//Set the login form as content
		$p->setContent($content);
		
		//Set the title of the website
		$p->setSiteTitle("LotusCMS Administration");
		
		//Set to 2 columns
		$p->setTwoColumn();
		
		$p->setLeftTitle("");
		$p->setLeftContent("");
	}
	
	/**
	 * Process
	 */
	public function processLogin(){
		
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
}

?>