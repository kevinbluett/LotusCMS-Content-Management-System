<?php

class Module{
	
	protected $request;
	protected $model;
	protected $page;
	protected $view;
	protected $controller;
	protected $unix;
	
	/**
	 * Sets the page request.
	 */
	public function setPage($page){
		$this->page = $page;	
	}
	
	/**
	 * Allows the setting of a unix request.
	 */
	public function setUnix($unix){
		$this->unix = $unix;
	}
	
	/**
	 * Returns unix name of module
	 */
	public function getUnix($unix){
		return $this->unix;
	}
	
	/**
	 * Returns page request
	 */
	public function getPage(){
		return $this->page;	
	}
	
	/**
	 * Sets the request.
	 */
	public function setRequests($req){
		$this->request = $req;	
	}
	
	/**
	 * Returns this request
	 */
	public function getRequests(){
		return $this->request;	
	}
	
	/** 
	 * Returns the model
	 */
	public function getModel(){
		return $this->model;
	}

	/** 
	 * Returns the controller
	 */
	public function getController(){
		return $this->controller;
	}
	
	/** 
	 * Returns the view
	 */
	public function getView(){
		return $this->view;
	}

	/** 
	 * Sets the model
	 */
	public function setModel($model){
		$this->model = $model;
	}

	/** 
	 * Sets the controller
	 */
	public function setController($con){
		$this->controller = $con;
	}

	/** 
	 * Sets the view
	 */
	public function setView($view){
		$this->view = $view;
	}
	
	/**
	 * Returns an ID if in URL
	 */
	public function getID(){
		//Using the request function in the Model
		return $this->getModel()->getInputString("id", null, "G");
	}
	
	/**
	 * Creates a relative URL to link to another page 
	 */
	public function createLink($page){
		return "index.php?system=".$this->getUnix()."&page=".$page;	
	}
	
	/**
	 * Creates a link to another page with an ID ie &id=%ID%
	 */
	public function createIDLink($page, $id){
		return "index.php?system=".$this->getUnix()."&page=".$page."&id=".$id;	
	}
}

?>