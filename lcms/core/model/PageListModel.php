<?php
class PageListModel extends Model{
	
	public function PageListModel(){
		Observable::Observable();
	}
	
	/**
	 * Returns the page requested by the system
	 */
	public function getPageRequest(){
		return $this->getInputString("page");	
	}
	
	/**
	 * List all pages in a directory.
	 */
	public function getPages(){
		
		// Set the state and tell plugins.
		$this->setState('GETTING_PAGES');
		$this->notifyObservers();
		
		//The directory containing the pages.
		$dir = $this->getController()->getPageDirectory();
		
		//Lists the pages in a directory
		$pages = $this->listFiles($dir);
		
		//Loops through all page listings to remove the extension of .dat
		for($i = 0; $i < count($pages); $i++)
		{
			//Removes the .dat from an item in the array
			$pages[$i] = str_replace(".dat", "", $pages[$i]);	
		}
		
		return $pages;	
	}
}

?>