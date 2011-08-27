<?php
//Minimised
class PageModel{
	protected $con;
	public function PageModel(){}
	public function setController($con){ $this->con = $con; }
	public function getController(){ return $this->con; }	
	public function loadPage($page){ $content = $this->openFile('cache/'.$page.'.html'); $this->getController()->getView()->overridePaging($content); }
    
	public function checkInCacheAndCreate($page){
	    if(!file_exists("cache/".$page.".html")){
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
	public function openFile($n){ $fd=fopen($n,"r") or die('Error 11: File Cannot be opened, '.$n); $fs=fread($fd,filesize($n)); fclose($fd); return $fs; }
} ?>