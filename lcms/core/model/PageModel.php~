<?php
//Avoids Loading Main Model for speed
class PageModel{
	
	public $t;
	protected $con;
	
	/**
	 * Starts the controller of the classes system
	 */
	public function PageModel(){
		
	}
	
	/**
	 * Set the local Controller
	 */
	public function setController($con){
		$this->con = $con;
	}
	
	/**
	 * Get the local Controller
	 */
	public function getController(){
		return $this->con;
	}
	
	/**
	 * Returns the contents of the page as an array
	 */
	protected function getPageData($page)
	{
		//Open the page file
		$data = $this->openFile($this->getPageDirectory().$page.".dat");
		    
		//Explode the available data
		$data = explode("|<*div*>|",$data);
		    
		//Return the collected data
		return $data;
	} 
    
	/**
	 * Load a page
	 */
	public function loadPage($page){
	    
		//Get the page content
		$content = $this->openFile('cache/'.$page.'.html');
		    
		//This overrides any created paging and just displays the cached page.
		$this->getController()->getView()->overridePaging($content);
	}
    
	/**
	 * 
	 */
	public function checkInCacheAndCreate($page){
	    
	    //Check if in cache
	    $is = $this->isCached($page);
	    
	    //cache it if it isn't yet
	    if(!$is){
		    
		    // Make sure the file exists;
			if(!file_exists("data/pages/".$page.".dat")){
			    
				//Display 404 page
				$this->getController()->getView()->noPage();
				    
				//Display page
				$this->getController()->getView()->displayPage();
				    
				//Stop All Processing.
				exit;
			    
			}else{
		    
				//Include the cacher
				include("core/lib/cacher.php");	
			    
				//Create Cacher and cache
				$cacher = new Cacher($page);
			}
	    }
	
	}
    
	/**
	 * Check if the page is availabe in the cache, if not
	 */
	public function isCached($page){
	    if(file_exists("cache/".$page.".html")){
		    //The page exists in the cache
		    return true;
	    }else{
		    //Page not in cache
		    return false;	
	    }
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
}
?>