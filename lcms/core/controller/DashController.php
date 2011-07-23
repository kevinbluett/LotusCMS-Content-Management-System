<?php
class DashController extends Controller{
	
	public function DashController($page){
		$this->setup("Dash","Dashboard");
	}
	
	protected function putRequests(){
		$requests = array("index");
		$this->setRequests($requests);
	}
	
	/**
	 * Show default index
	 */
	protected function indexRequest(){
		$this->setState('LOADING_DASH');
		$this->getView()->showDash();
	}
}

?>