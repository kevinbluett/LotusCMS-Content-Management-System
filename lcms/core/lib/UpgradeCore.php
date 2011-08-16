<?php
include("core/lib/io.php");
include("core/lib/RemoteFiles.php");


/**
 * Default Methods for module
 */
class UpgradeCore extends InputOutput{
	
	//Variables for the system
	protected $unix;	

	/**
	 * Returns the module Title
	 */
	public function UpgradeCore(){
		//Create new remote file connector.
		$rf = new RemoteFiles();
		
		print "<p>Preparing for update...</p>";
		
		print "<p>Downloading new core...</p>";
		
		//Save this zip
		$rf->downloadRemoteFile("http://cdn.lotuscms.org/lcms-3-series/coreupgrade/latest.zip", "", "latest.zip");
		
		if(!file_exists("latest.zip")){
			die("<p>Fatal Error: Download failed. LotusCMS installation is untouched.</p>");		
		}else{
			print "<p>File downloaded.</p>";
		}
		
		include_once('core/lib/pclzip.lib.php');
			
		//Setup Archive
		$archive = new PclZip("latest.zip");
		
		print "<p>Preparing for update...<br /><br />Deleting Core...</p>";
		
		//Destroys the secondary folder before copy.
		$this->destroyDir("core", true);	
			
		print "<p>Unzipping Update...</p>";
			
		//Extract Archive
		$list  =  $archive->extract();
			
		if ($archive->extract() == 0) {
			
			//Display Error Message
			print("<p><strong>Error Occurred.</strong> : ".$archive->errorInfo(true)."</p><p>Download/Extraction failed and core lost. Please copy the 'core' folder from a new edition downloaded from lotuscms.org.</p>");
		}else{
			
			print "<p>Success... Tidying up folders...</p>";
			
			//Annoying mac folders deleted if they exist.
			if(is_dir("__MACOSX")){
				//Destroys the secondary folder before copy.
				$this->destroyDir("__MACOSX", true);
			}
			
			//Delete the temporary plugin zip file.
			unlink("latest.zip");
			
			$version = $rf->getURL("http://update.lotuscms.org/lcms-3-series/latestVersion.php");
			
			$this->saveFile("data/config/site_version.dat", $version);
			
			print "<p><b>Complete</b>. <a href='../../../index.php'>Website Home</a></p>";
			
			session_destroy();
		}
		
		public function checkForModuleUpdates(){
			
		}
		
		public function checkForTemplateUpdates(){
			
		}
	}	
	
}
?>