<?php
class ModuleUpdateController extends Controller{
	
	public function ModuleUpdateController($page){
		$this->setup("ModuleUpdate","Module Update");
	}
	
	protected function putRequests(){
		$requests = array("update");
		$this->setRequests($requests);
	}
	
	/**
	 * This system downloads and installs a selected plugin
	 */
	protected function updateRequest(){
		$data = $this->getModel()->downloadInstall();
		$this->getView()->showInstallRedirect($data);
	}
}

?>