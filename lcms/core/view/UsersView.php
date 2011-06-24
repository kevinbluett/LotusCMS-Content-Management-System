<?php

include("core/view/view.php");

class UsersView extends View{
	
	
	/**
	 * Starts the controller of the classes system
	 */
	public function UsersView(){
			
	}	
	
	/**
	 * Show Users Create Form.
	 */
	public function showCreateForm(){
		
		// Set the state and tell plugins.
		$this->setState('SHOW_CREATE_FORM');
		$this->notifyObservers();
		
		//Get Create Form Title
		$title = $this->openFile("core/fragments/users/newUserTitle.phtml");
		
		//Get Create Form
		$form = $this->openFile("core/fragments/users/newUser.phtml");

		//Set form content to show
		$this->setContentTitle($title);
	
		//Set form content to show
		$this->setContent($form);
	}
	
	/**
	 * Are you sure you want to delete user check
	 */
	public function showDeleteCheck($username){
		
		// Set the state and tell plugins.
		$this->setState('SHOW_DELETE_CHECK');
		$this->notifyObservers();
		
		//Get Create Form Title
		$title = $this->openFile("core/fragments/users/deleteUserTitle.phtml");
		
		//Get Create Form
		$form = $this->openFile("core/fragments/users/deleteUserContent.phtml");
		
		//Replace Username Option
		$form = str_replace("%USERNAME%", $username, $form);
		
		//Set form content to show
		$this->setContentTitle($title);
	
		//Set form content to show
		$this->setContent($form);	
	}
	
	/**
	 *
	 */
	public function showDeleteSuccess($username){
		
		// Set the state and tell plugins.
		$this->setState('SHOW_DELETE_SUCCESS');
		$this->notifyObservers();
		
		//Get Create Form
		$form = $this->openFile("core/fragments/users/deleteUserSuccessContent.phtml");
		
		//Replace Username Option
		$form = str_replace("%USERNAME%", $username, $form);
	
		//Set form content to show
		$this->redirectSuccess($form);		
	}
	
	/**
	 * Shows user used or failed saving.
	 */
	public function showSubmitMessage($saved){
		
		// Set the state and tell plugins.
		$this->setState('SHOW_SUBMIT_DATA');
		$this->notifyObservers();
		
		//If was successfully saved
		if($saved)
		{
			//Get Create Form
			$content = $this->openFile("core/fragments/users/savedUserContent.phtml");
	
			//Redirect to user list
			$this->redirectSuccess($content);
		}
		//Not all fields were filled.
		else
		{
			//Get Create Form Title
			$title = $this->openFile("core/fragments/users/failedUserTitle.phtml");
				
			//Redirect back to the form showing the error
			$this->redirectError($title, "?system=Users&page=edit&active=".$this->getController()->getModel()->getInputString("active"));
		}
	}
	
	/**
	 * Show Users Create Form.
	 */
	public function showEditForm($username, $fullname, $email, $level1, $level2){
		
		// Set the state and tell plugins.
		$this->setState('SHOW_EDIT_FORM');
		$this->notifyObservers();
		
		//Get Create Form Title
		$title = $this->openFile("core/fragments/users/editUserTitle.phtml");
		
		//Get Create Form
		$form = $this->openFile("core/fragments/users/editUserForm.phtml");
		
		//Replace Username Option
		$form = str_replace("%USERNAME%", $username, $form);
		
		//Replace Full Name Option
		$form = str_replace("%FULLNAME%", $fullname, $form);
		
		//Replace Email option
		$form = str_replace("%EMAIL%", $email, $form);

		//Replace Level 1 option
		$form = str_replace("%OPTION1%", $level1, $form);

		//Replace Level 2 option
		$form = str_replace("%OPTION2%", $level2, $form);
				
		//Set form content to show
		$this->setContentTitle($title);
	
		//Set form content to show
		$this->setContent($form);
	}
	
	/**
	 * Redirects to a page with a success message
	 */
	public function redirectSuccess($message, $url = "index.php?system=UsersList&page=list"){
		
		// Set the state and tell plugins.
		$this->setState('REDIRECT_SUCCESS');
		$this->notifyObservers();
		
		//Show success message on redirected to page
		$_SESSION['ERROR_TYPE'] = "success";
		$_SESSION['ERROR_MESSAGE'] = $message;
		
		//Go Redirect
		$this->setRedirect($url);	
	}
	
	/**
	 * Redirects to a page with an error message
	 */
	public function redirectError($message, $url = "index.php?system=UsersList&page=list"){
		
		// Set the state and tell plugins.
		$this->setState('REDIRECT_ERROR');
		$this->notifyObservers();
		
		//Show error message on redirected to page
		$_SESSION['ERROR_TYPE'] = "error";
		$_SESSION['ERROR_MESSAGE'] = $message;
		
		//Go Redirect
		$this->setRedirect($url);
	}
}

?>