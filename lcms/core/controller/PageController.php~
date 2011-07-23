<?php
//This class avoids loading default controller for speed leaving out any loadable plugins and unused functions.
class PageController{
	
	var $page;
	protected $cv;
	protected $cm;
	protected $p;
	
	/**
	 * Starts the controller of the classes system
	 */
	public function PageController($page){
		//Give page to class
		$this->page = str_replace("/", "",$page);
		
		//Setup the page
		$this->setup("Pages");
		
		//Process Request - skip any page setting systems for just pure speed
		$this->wildCardRequest();
		
		$this->cv->displayPage();
	}
	
	/**
	 * Any Request will be processed 
	 */
	protected function wildCardRequest(){
		//Collects all the classes and their info as an array (will also display 404 page if required.)
		$this->getModel()->checkInCacheAndCreate($this->page);
		
		//Load and display page
		$this->getModel()->loadPage($this->page);
	}
	
	/**
	 * Setup the required page
	 */
	public function setup($title){
		//Setup Classes
		$this->setupClasses();
		
		//Setup paging system
		$this->setupPaging();
		
		//Set title of the Dashbaord.
		$this->cv->setContentTitle($title);
	}
	
	/**
	 *	Setup the required classes
	 */
	protected function setupClasses(){
		
		//View
		include("core/view/PageView.php");
		
		//Model
		include("core/model/PageModel.php");
		
		//Setup the classview
		$this->cv = new PageView();
		
		//Set this as controller
		$this->cv->setController($this);
		
		//Setup the classmodel
		$this->cm = new PageModel();
		
		//Set this as controller
		$this->cm->setController($this);
	}
	
	/**
	 * Setup the paging system if required
	 */
	protected function setupPaging(){
		//Setup the required page
		$this->cv->setupPage();	
	}
	
	/**
	 * Get Model
	 */
	public function getModel(){
		//Returns model
		return $this->cm;
	}
	
	/**
	 * Get View
	 */
	public function getView(){
		//Returns model
		return $this->cv;
	}
}

?>