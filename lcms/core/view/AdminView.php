<?php
include("core/lib/table.php");

class AdminView extends View{
	
	public function AdminView(){}	
	
	public function missingDetails(){
		
		//Change ID after incorrect logn
		session_regenerate_id();

		//Login
		$out = $this->openFile("style/comps/admin/login.phtml");
			
		$out = str_replace("%MESSAGE%", "<p class='msg error'>".$this->openFile("core/fragments/missing_details.phtml")."</p>", $out);
		$out = str_replace("%LOTUS_ADMIN_LOCALE%", $this->localize("LotusCMS Administration"), $out);
		$out = str_replace("%USERNAME_LOCALE%", $this->localize("Username"), $out);
		$out = str_replace("%PASSWORD_LOCALE%", $this->localize("Password"), $out);
		$out = str_replace("%LOGIN_LOCALE%", $this->localize("Login"), $out);

		//This quits the normal login form,
		$this->overridePaging($out);
		/******* freeze_all() called ********/
	}	
	
	public function setWrongLogin(){
		
		//Change ID after incorrect logn
		session_regenerate_id();
		
		//Login
		$out = $this->openFile("style/comps/admin/login.phtml");
		
		$out = str_replace("%MESSAGE%", "<p class='msg error'>".$this->getController()->getModel()->openFile("core/fragments/wrong_details.phtml")."</p>", $out);
		$out = str_replace("%LOTUS_ADMIN_LOCALE%", $this->localize("LotusCMS Administration"), $out);
		$out = str_replace("%USERNAME_LOCALE%", $this->localize("Username"), $out);
		$out = str_replace("%PASSWORD_LOCALE%", $this->localize("Password"), $out);
		$out = str_replace("%LOGIN_LOCALE%", $this->localize("Login"), $out);

		//This quits the normal login form,
		$this->overridePaging($out);
		/******* freeze_all() called ********/
	}
}
?>