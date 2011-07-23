<?php
class PageListController extends Controller{

	public function PageListController($page){
		
		$this->setup("Pagelist","Pages");
	}

	protected function putRequests(){
		$requests = array("index","list");
		$this->setRequests($requests);
	}
	

	/**
	 * Show default classes
	 */
	protected function listRequest(){
		//Get the Page list data
		$data = $this->getModel()->getPages();
		
		//Print the Page List
		$this->getView()->showPageList($data);
	}
}

?>