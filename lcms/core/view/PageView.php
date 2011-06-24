<?php
/**
 * Here as a dummy class as it is required by the framework, but as pages are only loaded from the cache this is never used.
 */
class PageView {
	
	
	/**
	 * Starts the controller of the classes system
	 */
	public function PageView(){
			
	}

	/**
	 * Printing the file from cache
	 */
	public function overridePaging($content){
		print $content;
	}
	
	public function noPage(){
		include("core/lib/page.php");
		
		$p = new Page();
		$p->setupPage();
		$p->setContentTitle("Page Not Found - Error 404");
		$p->setContent(file_get_contents("core/fragments/404.phtml"));
		$p->displayPage();
	}
	
	/**
	 * The Below Avoids loading the heavy templating system on page page load from cms if if in cache
	 */ 
	public function setupPage(){}
	public function setContentTitle($dummy){}
	public function setTwoColumn(){}
	public function displayPage(){}
	public function setController($dummy){}
}
?>