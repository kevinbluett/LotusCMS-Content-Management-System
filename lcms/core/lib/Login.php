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
	public function createLogin($p, $v = null, $infoLoad = false){
		
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
		
		$out = str_replace("%LOTUS_ADMIN_LOCALE%", $v->localize("LotusCMS Administration"), $out);
		$out = str_replace("%USERNAME_LOCALE%", $v->localize("Username"), $out);
		$out = str_replace("%PASSWORD_LOCALE%", $v->localize("Password"), $out);
		$out = str_replace("%LOGIN_LOCALE%", $v->localize("Login"), $out);
		
		//If LotusCMS should load information after killing the connection to speed up the panel.
		if($infoLoad)
		{
			include_once("core/lib/Preload.php");
					
			new Preload($out); 
					
		}
		
		//This quits the normal login form,
		$p->overridePaging("");
		/******* freeze_all() called ********/
		//Never Ever gets to Here
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