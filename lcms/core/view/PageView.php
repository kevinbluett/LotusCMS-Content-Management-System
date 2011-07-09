<?php
// Here as a dummy class as it is required by the framework, but as pages are only loaded from the cache this is never used.
class PageView {
	public function PageView(){}
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
	public function setupPage(){}
	public function setContentTitle($dummy){}
	public function setTwoColumn(){}
	public function displayPage(){}
	public function setController($dummy){}
}
?>