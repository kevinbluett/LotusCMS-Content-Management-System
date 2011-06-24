<?php

class LoginForm{
	
	//Text with form
	var $text;
	
	//The actual Login Form
	var $form;
	
	/**
	 * Sets up the login form
	 */
	public function LoginForm(){
		//Setup initial value
		$this->text = $this->getText();
		
		//Setup initial
		$this->form = $this->getForm();
	}
	
	/**
	 * Sets up the login form
	 */
	public function createForm(){
		//Puts the form togetherz.
		$form = $this->text.$this->form;
		
		//Creates the form for the system
		return $form;
	}
	
	/**
	 * Sets up the login form
	 */
	private function getForm(){
		return file_get_contents("core/fragments/loginbox.phtml");
	}
	
	/**
	 * Sets up default login form content
	 */
	private function getText(){
		return file_get_contents("core/fragments/logintext.phtml");
	}
	
	/**
	 * Sets up the login form
	 */
	public function setText($text){
		$this->text = $text;
	}
		
}

?>