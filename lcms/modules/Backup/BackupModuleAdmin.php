<?php

/**
 * The Administration loader for the module
 */
class ModuleAdmin extends Admin{

	/**
	 * The Default setup function
	 */
	public function ModuleAdmin(){
		//Sets the unix name of the plugin
		$this->setUnix("Backup");	
	}

	/**
	 * Default Requestion
	 */
	public function defaultRequest(){
		
		$data = "<p>This plugin will only function if the libraries for zipping and unzipping are installed on your server. If it fails just download the contents of the '/data/' directory in the root folder.</p><div class='yes'><a href='".$this->toRequest("zip")."'>Backup Now</a></div><div class='yes'><a href='".$this->toRequest("restore")."'>Restore Backup</a></div>";
		
		$this->setContent($data);
	}
	
	/**
	 * Creates zip of data directory.
	 */
	public function zipRequest($messages = true){
		
		$data = "";
		date_default_timezone_set("GMT");
		if($messages){
			$data = "<p>Please wait while the zip is being archived...</p>";
		}
		
		$name = 'modules/Backup/zips/archive'.date("c").'-id-'.$this->generateRandStr(20).'.zip';
		
		include_once('modules/Backup/pclzip.lib.php');
		  
		$archive = new PclZip($name);
		$v_list = $archive->add('data/');
		
		if ($v_list == 0) {
			$data .= "<p><b>Error Zipping:</b> ".$archive->errorInfo(true)."</p><p>If an 'PCLZIP_ERR_READ_OPEN_FAIL' error has occured, this indicates that the permissions for the backup folder are sit incorrectly - if so please chmod 'modules/Backup/zips' to 777.'</p>";
		}else if($messages){
		  	$data .= "<p class='success'><strong>Success:</strong> Your backup has been successfully prepared.</p><p><strong>Download Zip of Backup: <a href='".$name."'>Here</a></strong></p>";
		}else{
			$data .= "";
		}

		
		if($messages){
			$this->setContent($data);
		}else{
			return $data;	
		}
	}
	
	/**
	 * Restore
	 */
	public function restoreRequest(){
		$data = "<p>Would you like to choose to upload a backup, or would you like to backup from available online backups.</p><p><form action='".$this->toRequest("upload")."' method='post' enctype='multipart/form-data'><strong>Upload Backup</strong><br /><input type='file' name='datafile'/><input type='submit' value='Upload' /></form></p><br /><strong style='font-size: 13px;text-decoration: underline;'>OR</strong><br /><br /><div class='yes'><a href='".$this->toRequest("choose")."'>Choose an Existing Restore Point</a></div>";
		
		$this->setContent($data);
	}
	
	/**
	 * Upload Restore Point
	 */
	public function uploadRequest(){
		
		// Where the file is going to be placed 
		$target_path = "modules/Backup/zips/";
		
		$file = $this->getInputString("datafile", "bin", "F");
		
		if($file!="bin"){
			/* Add the original filename to our target path.  Result is "uploads/filename.extension" */
			$target_path = $target_path . basename( $file['name']); 
			if(move_uploaded_file($file['tmp_name'], $target_path)) {
			    $this->extractFile(basename( $file['name']));
			}else{
			    $this->setContent("<p>There was an error uploading the file, please try again after chmod of 'modules/Backup/zips' to 777.</p>");
			}
		}else{
		    $this->setContent("<p>There was an error uploading the file, no file selected.</p>");
		}
	}
	
	/**
	 * 
	 */
	public function extractFile($file){
		
		//First Backup the current state of the data directory
		$output = $this->zipRequest(false);
		
		if(!empty($output)){
			$this->setContent("<p>Sorry, restore cannot continue as an error occur while LCMS was backing up the current state of the data directory. Please chmod 'modules/Backup/zips' to 777.</p>");
		}else{
			include_once('modules/Backup/pclzip.lib.php');
			
			//Checks if data directory exists.
			if (is_dir('data')) {
				//If it does delete it
		    	$this->destroyDir("data", true); 
			}
			
			$file = str_replace(" ", "+", $file);
			
			//Setup Archive
			$archive = new PclZip("modules/Backup/zips/".$file);
			
			//Extract Archive
			$list  =  $archive->extract();
			
			if ($archive->extract() == 0) {
				//Display Error Message
			   $this->setContent("<p><strong>Error</strong> : ".$archive->errorInfo(true)."</p><p>It may help to chmod (change write permissions) the root cms directory to 777.</p>");
			}else{
				//Display sucess message
				$this->setContent("<p class='success'<b>Success:</b> LotusCMS has been restored to the original state of the backup.</p> <p>Remember: <strong>User accounts and passwords created after the backup was made, have been reversed to their respective original state.</strong></p>");	
			}
		}
	}
	
	/**
	 * Choose Restore Point
	 */
	public function chooseRequest(){
		//Get Available backups.
		$data = $this->listFiles("modules/Backup/zips");
		
		$out = "<p>Choose a restore point from below, in each filename is the date in the ISO standard. They are located in 'modules/Backup/zips' incase you would like to remove backup points via FTP. Click on one to use it.</p>";
		
		for($i = 0; $i < count($data); $i++){
			$out .= "<p><a href='".$this->toRequest("choosing")."&file=".$data[$i]."'>".$data[$i]."</a></p>";
		}
		
		if(empty($data)){
			$out .= "<p><strong>No restore points available.</strong></p>";	
		}
		
		$this->setContent($out);
	}
	
	/**
	 * Restores choosen file
	 */
	public function choosingRequest(){
		$this->extractFile($this->getInputString("file", "", "G"));
	}
	
	/**
	 * Returns a list of all the files in a specified directory (Not Recursive) - excluding confirguration files and 'index.php'.
	 */
	protected function listFiles($start_dir)
	{
        
		/*
		returns an array of files in $start_dir (not recursive)
		*/
			
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
                return $GLOBALS[$format_defines[$glb]][$name];
            }
        }
      
        return $default_value;
    }
    
    /**
     * Destorys directory
     */
   	public function destroyDir($dir, $virtual = false)
	{
		$ds = DIRECTORY_SEPARATOR;
		$dir = $virtual ? realpath($dir) : $dir;
		$dir = substr($dir, -1) == $ds ? substr($dir, 0, -1) : $dir;
		if (is_dir($dir) && $handle = opendir($dir))
		{
			while ($file = readdir($handle))
			{
				if ($file == '.' || $file == '..')
				{
					continue;
				}
				elseif (is_dir($dir.$ds.$file))
				{
					$this->destroyDir($dir.$ds.$file);
				}
				else
				{
					unlink($dir.$ds.$file);
				}
			}
			closedir($handle);
			rmdir($dir);
			return true;
		}
		else
		{
			return false;
		}
	}
	
	public function generateRandStr($length){
      $randstr = "";
      for($i=0; $i<$length; $i++){
         $randnum = mt_rand(0,61);
         if($randnum < 10){
            $randstr .= chr($randnum+48);
         }else if($randnum < 36){
            $randstr .= chr($randnum+55);
         }else{
            $randstr .= chr($randnum+61);
         }
      }
      return $randstr;
} 
 
}

?>