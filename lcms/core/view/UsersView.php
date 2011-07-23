<?php
class UsersView extends View{
	
	
	/**
	 * Starts the controller of the classes system
	 */
	public function UsersView(){
		//Meta is usually not setup yet, so we manually do this before loading css file
		$this->meta = new Meta();
		
		//This class requires an extra css file
		$this->getMeta()->addExtra('<link href="core/fragments/css/admin.css" rel="stylesheet" type="text/css" />');
	}	
	
	/**
	 * Show Users Create Form.
	 */
	public function showCreateForm(){
		
		// Set the state and tell plugins.
		$this->setState('SHOW_CREATE_FORM');
		$this->notifyObservers();
		
		//Get Create Form Title
		$title = $this->localize("Create New User");
		
		//Get Create Form
		$form = $this->openFile("core/fragments/users/newUser.phtml");
		
		//Localization
		$form = str_replace("%USERNAME_LOCALE%", $this->localize("Username"), $form);
		$form = str_replace("%USERNAME_WARNING_LOCALE%", $this->localize("one word - no spaces or special characters"), $form);
		$form = str_replace("%FULLNAME_LOCALE%", $this->localize("Full Name"), $form);
		$form = str_replace("%EMAIL_LOCALE%", $this->localize("E-mail"), $form);
		$form = str_replace("%PASSWORD_LOCALE%", $this->localize("Password"), $form);
		$form = str_replace("%NEW_PASSWORD_LOCALE%", $this->localize("New Password"), $form);
		$form = str_replace("%VERIFY_PASSWORD_LOCALE%", $this->localize("Verify Password"), $form);
		$form = str_replace("%ACCESSLEVEL_LOCALE%", $this->localize("Access Level"), $form);
		$form = str_replace("%SAVE_LOCALE%", $this->localize("Save"), $form);

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
		$title = $this->localize("Deleting User");
		
		//Get Create Form
		$form = $this->openFile("core/fragments/users/deleteUserContent.phtml");
		
		//Replace Username Option
		$form = str_replace("%USERNAME%", $username, $form);
		
		//Localization
		$form = str_replace("%ARE_SURE_USER_LOCALE%", $this->localize("Are You sure you want to delete the user"), $form);
		$form = str_replace("%YES_LOCALE%", $this->localize("Yes"), $form);
		$form = str_replace("%NO_LOCALE%", $this->localize("No"), $form);
		
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
		$form = $this->localize("The user account has been deleted successfully.");
	
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
			$content = $this->localize("The user's details was successfully saved.");
	
			//Redirect to user list
			$this->redirectSuccess($content);
		}
		//Not all fields were filled.
		else
		{
			//Get Create Form Title
			$title = $this->localize("Sorry - You left a field empty or the passwords entered didn't match.");
				
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
		$title = $this->localize("Edit User");
		
		//Get Create Form
		$form = $this->openFile("core/fragments/users/editUserForm.phtml");
		
		//Localization
		$form = str_replace("%USERNAME_LOCALE%", $this->localize("Username"), $form);
		$form = str_replace("%USERNAME_WARNING_LOCALE%", $this->localize("one word - no spaces or special characters"), $form);
		$form = str_replace("%FULLNAME_LOCALE%", $this->localize("Full Name"), $form);
		$form = str_replace("%EMAIL_LOCALE%", $this->localize("E-mail"), $form);
		$form = str_replace("%PASSWORD_LOCALE%", $this->localize("Password"), $form);
		$form = str_replace("%NEW_PASSWORD_LOCALE%", $this->localize("New Password"), $form);
		$form = str_replace("%VERIFY_PASSWORD_LOCALE%", $this->localize("Verify Password"), $form);
		$form = str_replace("%ACCESSLEVEL_LOCALE%", $this->localize("Access Level"), $form);
		$form = str_replace("%SAVE_LOCALE%", $this->localize("Save"), $form);
		
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