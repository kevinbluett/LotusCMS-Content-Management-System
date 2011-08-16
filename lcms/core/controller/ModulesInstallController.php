<?php
class ModulesInstallController extends Controller{
	
	public function ModulesInstallController($page){
		$this->setup("ModulesInstall","Module Find &amp; Install");
	}
	
	protected function putRequests(){

		$requests = array(
					"find",
					"install",
					"activate",
					"installDownload"
				);
		
		$this->setRequests($requests);
	}
	
	/**
	 * Editor Request to get a page.
	 */
	protected function findRequest(){
		//Get the installed module data
		$data = $this->getModel()->getFindInfo();
		
		//Show this data visually
		$this->getView()->showFindInfo($data);
	}
	
	/**
	 * This system downloads and installs a selected plugin
	 */
	protected function installDownloadRequest(){
		$data = $this->getModel()->downloadInstall();
		$this->getView()->showInstallRedirect($data);
	}
	
	/**
	 * Plugin Activation Request.
	 */
	protected function activateRequest(){
		$data = $this->getModel()->activatePlugin();
		$this->getView()->showPluginActivation($data);	
	}
}

?>