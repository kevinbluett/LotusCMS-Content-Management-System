<?php

class Mailer {

	protected $mod;
	
	/**
	 * Setups the e-mailer.
	 */
	public function Mailer($mod){
		$this->mod = $mod;
	}
	
	/**
	 * Returns the module
	 */
	protected function getMod(){
		return $this->mod;
	}	
	
	/**
	 * Sends the email with the specified settings.
	 */
    public function send($name, $ip, $email, $message){
    	
    	//Opens the settings
    	$data = $this->getMod()->getSettings();
		
		//Sends the email
		mail($data[0], $data[1],"From: $name\nIP: $ip\nMessage:\n $message", "From: $email" );

    }
	
	
	
}

?>