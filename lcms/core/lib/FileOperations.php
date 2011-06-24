<?php

class FileOperations{
	
	
	/**
	 * Sets up the class
	 */
	public function FileOperations(){
		
	}	
	
	/**
	 * Deletes the contents of a directory
	 */
	public function emptyDir($path) { 

		// INITIALIZE THE DEBUG STRING
		$debugStr = '';
		$debugStr .= "Deleting Contents Of: $path<br /><br />";
		
		// PARSE THE FOLDER
		if ($handle = opendir($path)) {
			
			while (false !== ($file = readdir($handle))) {
			
				if ($file != "." && $file != "..") {
				
					// IF IT"S A FILE THEN DELETE IT
					if(is_file($path."/".$file)) {
					
						if(unlink($path."/".$file)) {
						$debugStr .= "Deleted File: ".$file."<br />";	
						}
							
					} else {
					
						// IT IS A DIRECTORY
						// CRAWL THROUGH THE DIRECTORY AND DELETE IT'S CONTENTS
					
						if($handle2 = opendir($path."/".$file)) {
						
							while (false !== ($file2 = readdir($handle2))) {
	
								if ($file2 != "." && $file2 != "..") {
									if(unlink($path."/".$file."/".$file2)) {
									$debugStr .= "Deleted File: $file/$file2<br />";	
									}
								}
						
							}
							
						}
					
						if(rmdir($path."/".$file)) {
						$debugStr .= "Directory: ".$file."<br />";	
						}
						
					}
				
				}
				
			}
			
		}
		return $debugStr;
	}
}

?>