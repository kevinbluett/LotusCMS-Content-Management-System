<?php

class InputOutput{
	
	
	/**
	 * Starts the IO System
	 */
	public function InputOutput(){
		
	}
	
	/**
	 * Try to delete file
	 */
	public function delete($file){
		
		//If the users file exists try to delete it
		if(file_exists($file))
		{
			unlink($file) or die($this->openFile("core/fragments/errors/error34.phtml"));
		}
	}
	
	 /**
	  * Save the set file, with the requested content.
	  */
	public function saveFile($m, $n){
    	
		//Save to disk if the space is available
		if($this->disk_space())
		{
			$n=trim($n);
			if($n==''){$n=' ';}$n=str_replace("\n\n","\n",$n);$p=0;
			do{$fd=fopen($m,"w+") or die($this->openFile("core/fragments/errors/error21.phtml"));$fout=fwrite($fd,$n);
			fclose($fd);$p++;}while(filesize($m)<5&&$p<5);
		}
		else
		{
			//Print  Error Message
			die($this->openFile("core/fragments/errors/error22.phtml"));	
		}
	}
	
	/**
	 * Returns a list of all the files in a specified directory (Not Recursive) - excluding confirguration files and 'index.php'.
	 */
	public function listFiles($start_dir)
	{
		$files = array();
		$dir = opendir($start_dir);
		while(($myfile = readdir($dir)) !== false)
			{
			if($myfile != '.' && $myfile != '..' && !is_file($myfile) && $myfile != 'resource.frk' && $myfile != 'index.php' && $myfile != '.htaccess' )
				{
				$files[] = $myfile;
				}
			}
		closedir($dir);
		return $files;
	}
    
    /**
     * Checks that there is enough space left to save the file on the harddisk.
     */
    protected function disk_space(){
		$s = true;
		
		if(function_exists('disk_free_space'))
		{
			$a=disk_free_space("/");
			if(is_int($a)&&$a<204800)
			{
				$s=false;
			}
		}
		return $s;
	}
		
    /**
     * Returns the contents of the requested page
     */
    public function openFile($n){
    	$fd=fopen($n,"r") or die('Error 11: User File Cannot be opened, '.$n);
		$fs=fread($fd,filesize($n));
		fclose($fd);
		return $fs;
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
}

?>