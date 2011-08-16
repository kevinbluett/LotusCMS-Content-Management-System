<?php
//Avoids Loading Main Model for speed + minimum comments
class PageModel{
	
	protected $con;
	
	public function PageModel(){}
	
	public function setController($con){
		$this->con = $con;
	}
	
	public function getController(){
		return $this->con;
	}
	
	protected function getPageData($page)
	{
		$data = $this->openFile($this->getPageDirectory().$page.".dat");
		$data = explode("|<*div*>|",$data);
		return $data;
	} 
    
	public function loadPage($page){
		$content = $this->openFile('cache/'.$page.'.html');
		$this->getController()->getView()->overridePaging($content);
	}
    
	public function checkInCacheAndCreate($page){
	    $is = $this->isCached($page);
	    if(!$is){
			if(!file_exists("data/pages/".$page.".dat")){
				$this->getController()->getView()->noPage();
				$this->getController()->getView()->displayPage();
				exit;
			    
			}else{
				include("core/lib/cacher.php");	
				$cacher = new Cacher($page);
			}
	    }
	
	}
    
	public function isCached($page){
	    if(file_exists("cache/".$page.".html")){
		    return true;
	    }else{
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