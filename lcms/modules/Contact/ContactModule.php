<?php
include("modules/Contact/Mailer.php");
class ContactModule extends Module{

	protected $mailer;
	
	/**
	 * Sets up the news module
	 */
	public function ContactModule($page){

	}

	/**
	 * Sets the requests of the system
	 */
	public function putRequests(){
		
		//Create the array of request
		$requests = array(
					"index",
					"submit"
				);
		
		//Set all the request
		$this->setRequests($requests);
	}
	
	/**
	 *
	 */
	public function defaultRequest(){
		$this->getView()->noPage();
	}
	
	/**
	 * Products page request
	 */
	public function indexRequest(){
		
		//Get the contact form settings
		$data = $this->getSettings();
		
		$this->getView()->setContentTitle($data[2]);
		
		$form = $this->getModel()->openFile("modules/Contact/fragments/contact_form.phtml");
		
		$message = $this->getModel()->getInputString("message", null, "S");
		
		if(!empty($message)){
			$form = str_replace("%MESSAGE%", $message, $form);
			
			unset($_SESSION['message']);
		}else{
			$form = str_replace("%MESSAGE%", "", $form);
		}
		
		$this->getView()->setContent($form);
		
	}
	
	/** 
	 *
	 */
	public function submitRequest(){

		//Get Submitted Message
		$this->getSubmitted();
		
		$this->getView()->setContentTitle("Thank You");
		$this->getView()->setContent($this->getModel()->openFile("modules/Contact/fragments/success_page.phtml"));
	}
	

	/**
	 * Gets user submitted details and checks them
	 */
	public function getSubmitted(){
		
		//Get the Items
		$name = $this->getModel()->getInputString("name", null, "P");
		$email = $this->getModel()->getInputString("email", null, "P");
		$message = $this->getModel()->getInputString("message", null, "P");
		
		if(empty($name)){
			$this->setErrorRedirect("One or more of the fields was left blank please try again.");
			if(!empty($message)){
				$_SESSION['message'] = $message;
			}
		}else if(empty($email)){
			$this->setErrorRedirect("One or more of the fields was left blank please try again.");	
			if(!empty($message)){
				$_SESSION['message'] = $message;
			}
		}else if(empty($message)){
			$this->setErrorRedirect("One or more of the fields was left blank please try again.");	
		}else if($this->checkEmail($email, $message)){
		
			$this->checkIPs();
			
			//Setup the mailing system.
			$this->mailer = new Mailer($this);
			
			$ip = $this->getModel()->getInputString("REMOTE_ADDR", "N/A");
			
			//Send the message
			$this->mailer->send($name, $ip, $email, $message);
		}
	}
	
	/**
	 * Checking Email Address Validity.
	 */
	public function checkEmail($email, $message){
		
		//Checks the email
		$good = $this->check_email_address($email);
		
		//If not good stop the user here.
		if(!$good){
			$this->setErrorRedirect("The e-mail address you entered is invalid. Please try again");	
			$_SESSION['message'] = $message;
			return false;
		}
		
		return true;
	}
	
        /**

         * Check email address validity

         * @param   strEmailAddress     Email address to be checked

         * @return  True if email is valid, false if not

         */

   	public function check_email_address($email) {
			// First, we check that there's one @ symbol, and that the lengths are right
			if (!ereg("^[^@]{1,64}@[^@]{1,255}$", $email)) {
				// Email invalid because wrong number of characters in one section, or wrong number of @ symbols.
				return false;
			}
			// Split it into sections to make life easier
			$email_array = explode("@", $email);
			$local_array = explode(".", $email_array[0]);
			for ($i = 0; $i < sizeof($local_array); $i++) {
				if (!ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$", $local_array[$i])) {
					return false;
				}
			}
			if (!ereg("^\[?[0-9\.]+\]?$", $email_array[1])) { // Check if domain is IP. If not, it should be valid domain name
				$domain_array = explode(".", $email_array[1]);
				if (sizeof($domain_array) < 2) {
					return false; // Not enough parts to domain
				}
				for ($i = 0; $i < sizeof($domain_array); $i++) {
					if (!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$", $domain_array[$i])) {
						return false;
					}
				}
			}
			return true;
		}
	
	/**
	 * Not Currently Supported.
	 */
	public function checkIPs(){
		//Unsupported	
	}
	
	/**
	 *
	 */
	public function getSettings(){
		
		//Getting Site Details
		$data = $this->getModel()->openFile("data/modules/Contact/settings.dat");
		
		//Turn to Array
		$data = explode("||", $data);
		
		return $data;
	}
	
	/**
	 * Redirects due to error
	 */
	public function setErrorRedirect($error_msg){
		$_SESSION['ERROR_TYPE'] = "error";
		$_SESSION['ERROR_MESSAGE'] = $error_msg;	
		
		$this->getView()->setRedirect("index.php?system=Contact&page=index");
	}
}

?>