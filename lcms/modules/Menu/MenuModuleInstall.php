<?php
/**
 * Menu Module Information
 */
class ModuleInstall extends Install{
	
	/**
	 * Setup system
	 */
	public function ModuleInstall(){
		
		//Setup Unix
		$this->unix = "Dashboard";
	}
	
	/**
	 * Install/Activation instructions 
	 */
	public function InstallStart(){
		
		//Makes the module active
		$this->createModuleActive();
		
		//Create the compiled menu items .dat
		$this->saveFile("data/modules/Menu/compiled.dat", "<ul><li><a class='firstM' href='index.php?page=index'>Home</a></li><li><a class='normalM' href='index.php?page=about_us'>About Us</a></li><li><a class='lastM' href='index.php?system=Admin'>Login</a></li></table>");
		
		//Save the menu_items file
		$this->saveFile("data/modules/Menu/items/menu_items.dat", "Home|*inter*|index.php?page=index|*inter*|in|*outer*|About Us|*inter*|index.php?page=about_us|*inter*|in|*outer*|Login|*inter*|index.php?system=Admin|*inter*|ex");
		
		//Save an empty index.php into items
		$this->saveFile("data/modules/Menu/items/index.php", " ");
	}
}

?>