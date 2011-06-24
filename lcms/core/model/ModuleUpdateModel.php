<?php
include("core/model/model.php");

class ModuleUpdateModel extends Model{
	
	public $t;
	
	/**
	 * Starts the controller of the classes system
	 */
	public function ModuleUpdateModel(){
		//Allow Plugins.
		Observable::Observable();
	}
	
	/**
	 * Returns the page requested by the system
	 */
	public function getActiveRequest(){
		return $this->getInputString("req", null, "G");	
	}
	
	/**
	 * Allows download and install
	 */
	public function downloadInstall(){
		
		// Set the state and tell plugins.
		$this->setState('DOWNLOADING_PLUGIN');
		$this->notifyObservers();
		
		//Get plugin name
		$plugin = $this->getActiveRequest();
		
		//Should never occur.
		if(empty($plugin)){
			exit("Plugin Undefined.");	
		}
		
		//Get remote files collector
		include_once("core/lib/RemoteFiles.php");
		
		//Create new remote file connector.
		$rf = new RemoteFiles();
		
		//Save this zip
		$rf->downloadRemoteFile("http://cdn.modules.lotuscms.org/lcms-3-series/zips/".$plugin.".zip", "data", $plugin.".zip");
		
		include_once('core/lib/pclzip.lib.php');
			
		//Setup Archive
		$archive = new PclZip("data/".$plugin.".zip");
		
		//Destroy existing plugin files if they exist.
		$this->destroyDir("modules/".$plugin, true);
			
		//Extract Archive
		$list  =  $archive->extract(PCLZIP_OPT_PATH, "modules");
			
		if ($archive->extract() == 0) {
			
			// Set the state and tell plugins.
			$this->setState('DOWNLOADING_PLUGIN_FAILED');
			$this->notifyObservers();
			
			unlink("data/".$plugin.".zip");
			
			//Display Error Message
			exit("<p><strong>Error</strong> : ".$archive->errorInfo(true)."</p><p>It may help to chmod (change write permissions) the 'modules' cms directory to 777.</p>");
		}else{
			// Set the state and tell plugins.
			$this->setState('DOWNLOADING_PLUGIN_SUCCESS');
			$this->notifyObservers();
			
			//If the original plugin folder is also there remove it
			if(is_dir($plugin)){
				
				//Destroys the secondary folder before copy.
				$this->destroyDir($plugin, true);	
				
				//Destroys the secondary folder before copy.
				$this->destroyDir("__MACOSX", true);
			}
			
			//Delete the temporary plugin zip file.
			unlink("data/".$plugin.".zip");
			
			return $plugin;
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
	
	/**
	 * Returns a list of all the files in a specified directory (Not Recursive) - excluding confirguration files and 'index.php'.
	 */
	protected function listFiles($start_dir)
	{
		$files = array();
		$dir = opendir($start_dir);
		while(($myfile = readdir($dir)) !== false)
			{
			if($myfile != '.' && $myfile != '..' && !is_file($myfile) && $myfile != 'resource.frk' && $myfile != 'index.php' )
				{
				$files[] = $myfile;
				}
			}
		closedir($dir);
		return $files;
	}
}

?>