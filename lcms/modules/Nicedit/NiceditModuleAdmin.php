<?php

/**
 * The Administration loader for the module
 */
class ModuleAdmin extends Admin{

	/**
	 * The Default setup function
	 */
	public function ModuleAdmin($con){
		//Sets the unix name of the plugin
		$this->setUnix("Nicedit");	
		
		//Set the controller
		$this->setController($con);
	}

	/**
	 * Default Requestion
	 */
	public function defaultRequest(){
		
		//Check if enabled
		$on = $this->checkPlug("Editor", $this->getUnix());
		
		$a = "";
		$b = "";
		
		//Check the radio buttons
		if($on){
			$a = "checked";	
		}else{
			$b = "checked";	
		}
		
		$data = '<form method="post" action="'.$this->toRequest("save").'">
		<p>'.$this->localize("Would you like to enable this page editor").' (Nicedit editor)?</p>
		
		<p><input type="RADIO" name="enabled" value="on" '.$a.'>&nbsp;&nbsp;'.$this->localize("Enabled").'<br />
		<input type="RADIO" name="enabled" value="off" '.$b.'>&nbsp;&nbsp;'.$this->localize("Disabled").'<br /></p>
		
		<input type="submit" value="submit">
		</form>';
		
		$this->setContent($data);
	}
	
	/**
	 * Default Requestion
	 */
	public function saveRequest(){
		
		//Check what option was process through
		$act = $this->getInputString("enabled", null,"P");

		//Make sure that the acting system is empty.
		if(!empty($act))
		{
			//Switching on or off
			if($act=="on"){
				//Make sure the other editor isn't on.
				if($this->checkPlug("Editor","TinyMCE")){
					//If the Nicedit editor is on switch it off first
					$this->removePlug("Editor", "TinyMCE");
				}
				//Make sure the other editor isn't on.
				if($this->checkPlug("Editor","lrte")){
					//If the Nicedit editor is on switch it off first
					$this->removePlug("Editor", "lrte");
				}
				$this->addPlug("Editor", $this->getUnix());	
			}else if($act=="off"){
				$this->removePlug("Editor", $this->getUnix());	
			}
		}
		
		//Show success message on redirected to page
		$_SESSION['ERROR_TYPE'] = "success";
		$_SESSION['ERROR_MESSAGE'] = $this->localize("Successfully changed setting to")." '$act'.";
		
		//Create a request to the default
		$this->getController()->getView()->setRedirect($this->toRequest(""));
	}
}

?>