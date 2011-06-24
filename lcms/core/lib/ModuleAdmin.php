<?php

/**
 * Default Methods for module admin
 */
class Admin{
	
	//Variables for the system
	protected $req;
	protected $content;
	protected $unix;
	protected $con;
	
	/**
	 * Process the page request
	 */
	public function process(){
		
		//Gets the request
		$req = (String)$this->getRequest();
		
		//Requests the default system
		if(empty($req))
		{
			//Run the default request
			$this->defaultRequest();
			
			//This breaks processing of all the following requests
			return false;
		}
		
		//Create Request
		$process = "\$this->".($req)."Request();";
				
		//Process the request
		eval($process);
	}
	
	/**
	 * Converts a single page request into a url suitable for links
	 */
	public function toRequest($req){
		
		//Creates the string
		$default = "?system=Modules&page=admin&active=".$this->getUnix()."&req=".$req;
		
		//Returns the created string
		return $default;
	}
	
	/**
	 * Checks for observable option in system
	 */
	public function checkPlug($system, $unix){
		
		if(file_exists("data/config/modules/".$system.".dat")){
			$data = $this->openFile("data/config/modules/".$system.".dat");
			
			$data = explode("|", $data);
			
			for($i = 0;$i < count($data); $i++){					
				
				if($data[$i]==$unix){
					return true;
				}	
			}
		}
		return false;
	}
	
	/**
	 * Checks for observable option in system
	 */
	public function addPlug($system, $unix){
		
		$on = $this->checkPlug($system, $unix);
		
		//If the check plug isn't on
		if(!$on){
			if(file_exists("data/config/modules/".$system.".dat")){
				$data = $this->openFile("data/config/modules/".$system.".dat");
				
				if(!empty($data)){
					//Add name to total list
					$data = $data."|".$unix;
				}else{
					$data = $unix;	
				}
				
				//Save file
				$this->saveFile("data/config/modules/".$system.".dat", $data);
			}else{
				//Directly create file
				$this->saveFile("data/config/modules/".$system.".dat", $unix);
			}
		}
	}
	
	/**
	 * Removes Observing plug from system
	 */
	public function removePlug($system, $unix){
		
		$on = $this->checkPlug($system, $unix);
		
		//If the check plug is on.
		if($on){
			//Existing Plugs
			$data = $this->openFile("data/config/modules/".$system.".dat");
			
			//New String
			$out = "";
				
			for($i = 0; $i < count($data); $i++){
				
				//Avoids empty strings.
				if($i==0){
					if($data[$i]!=$unix){
						$out .= $data[$i];
					}
				}else{
					if($data[$i]!=$unix){
						$out .= "|".$data[$i];
					}
				}
			}
				
			//Save file
			$this->saveFile("data/config/modules/".$system.".dat", $out);
		}
	}
	
	/**
	 * gets the Request
	 */
	public function getRequest(){
		return $this->req;	
	}
	
	/**
	 * Sets the Request
	 */
	public function setRequest($req){
		$this->req = str_replace(";","",$req);	
	}
	
	/**
	 * gets the Request
	 */
	public function getController(){
		return $this->con;	
	}
	
	/**
	 * Sets the Request
	 */
	public function setController($con){
		$this->con = $con;	
	}
	
	/**
	 * gets the Unix name
	 */
	public function getUnix(){
		return $this->unix;	
	}
	
	/**
	 * Sets the Unix Name
	 */
	public function setUnix($unix){
		$this->unix = $unix;	
	}
	
	/**
	 * Sets the content to be outputted by the system.
	 */
	public function setContent($content){
		$this->content = $content;
	}
	
	/**
	 * Gets the set content for this administraion page.
	 */
	public function getContent(){
		return $this->content;
	}
	
	/**
	 * Gets a fragment from the fragment folder of a plugin
	 */
	public function getFragment($frag){
		
		//Gets unix name of the plugin
		$unix = $this->getUnix();
		
		if(is_dir("modules/".$unix."/fragments")){
			if(file_exists("modules/".$unix."/fragments/".$frag.".phtml")){
				return $this->openFile("modules/".$unix."/fragments/".$frag.".phtml");	
			}else{
				return "";	
			}
		}else{
			return "";	
		}
	}
	
	/**
	 * Save the set file, with the requested content.
	 * $m = file
	 * $n = file contents
	 * $o = Error message.
	 */
	protected function saveFile($m, $n, $o = 0){
    	
		//Save to disk if the space is available
		if($this->disk_space())
		{
			$n=trim($n);
			if($n==''){$n=' ';}$n=str_replace("\n\n","\n",$n);$p=0;
			do{$fd=fopen($m,"w+") or die($this->openFile("core/fragments/errors/error21.phtml")." - Via SEOModel.php");$fout=fwrite($fd,$n);
			fclose($fd);$p++;}while(filesize($m)<5&&$p<5);
		}
		else
		{
			//Print Out of Space Error Message
			die($this->openFile("core/fragments/errors/error22.phtml"));	
		}
	}
    
	/**
	 * Checks that there is enough space left to save the file on the harddisk.
	 */
	protected function disk_space(){
		$s = true;
		
		if(function_exists('disk_free_space'))
		{
			$a = disk_free_space("/");
			if(is_int($a)&&$a<204800)
			{
				$s = false;
			}
		}
		return $s;
	}
	
    /**
     * Returns the contents of the requested page
     */
    public function openFile($n){
    	$fd=fopen($n,"r") or die('Error 11: File Cannot be opened, '.$n);
		$fs=fread($fd,filesize($n));
		fclose($fd);
		return $fs;
    }
    
	/**
	 * Returns a list of all the files in a specified directory (Not Recursive) - excluding confirguration files and 'index.php'.
	 */
	protected function listFiles($start_dir)
	{
		$files = array();
		$dir = opendir($start_dir);
		while(($myfile = readdir($dir)) !== false)
			{
			if($myfile != '.' && $myfile != '..' && !is_file($myfile) && $myfile != 'resource.frk' && $myfile != 'index.php' && !eregi('^Icon',$myfile) )
				{
				$files[] = $myfile;
				}
			}
		closedir($dir);
		return $files;
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
	 * Redirects to a page with a success message
	 */
	public function redirectSuccess($message){
		
		//Show success message on redirected to page
		$_SESSION['ERROR_TYPE'] = "success";
		$_SESSION['ERROR_MESSAGE'] = $message;
		
		//Go Redirect
		$this->getController()->getView()->setRedirect("index.php?system=Modules&page=admin&active=".$this->getUnix());	
	}
	
	/**
	 * Redirects to a page with an error message
	 */
	public function redirectError($message){
		
		//Show error message on redirected to page
		$_SESSION['ERROR_TYPE'] = "error";
		$_SESSION['ERROR_MESSAGE'] = $message;
		
		//Go Redirect
		$this->getController()->getView()->setRedirect("index.php?system=Modules&page=admin&active=".$this->getUnix());	
	}
}
?>