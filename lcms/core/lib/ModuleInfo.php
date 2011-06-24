<?php

/**
 * Default Methods for module
 */
class Info{
	
	//Variables for the system
	protected $title;
	protected $author;
	protected $organisation;
	protected $admin;
	protected $support;
	protected $overrider;
	protected $version;
	protected $unix;	
	protected $allowDisable = true;

	/**
	 * Returns the module Title
	 */
	public function getTitle(){
		return $this->title;	
	}
	
	/**
	 * Return the author
	 */
	public function getAuthor(){
		return $this->author;	
	}
	
	/**
	 * Return Support
	 */
	public function getSupport(){
		return $this->support;	
	}
	
	/**
	 * Returns adminstration
	 */
	public function getAdministration(){
		return $this->admin;	
	}
	
	/**
	 * Returns Overrider
	 */
	public function getOverrider(){
		return $this->overrider;	
	}
	
	/**
	 * Returns Organisation
	 */
	public function getOrganisation(){
		return $this->organisation;	
	}

	/**
	 * Returns Version
	 */
	public function getVersion(){
		return $this->version;	
	}
	
	/**
	 * Returns Unix Name
	 */
	public function getUnix(){
		return $this->unix;	
	}
}
?>