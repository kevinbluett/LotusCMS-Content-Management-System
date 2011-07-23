<?php
class TemplateController extends Controller{
	
	public function TemplateController($page){
		$this->setup("Template", "Change Your Template");
	}
	
	protected function putRequests(){
		$requests = array(
					"change",
					"install",
					"getTemplates",
					"preview",
					"delete"
				);
		$this->setRequests($requests);
	}
	

	/**
	 * Editor Request to get a page.
	 */
	protected function getTemplatesRequest(){
		$this->setState('GETTING_TEMPLATES_LIST');
		
		//Gets template data
		$data = $this->getModel()->getFeaturedTemplates();
		
		//Show data
		$this->getView()->showInstallableTemplates($data);
	}
	
	/**
	 * Editor Request to get a page.
	 */
	protected function deleteRequest(){
		$this->setState('STARTING_DELETE_SEQUENCE');
		
		$d = $this->getModel()->getInputString("sure", null, "G");
		
		if(empty($d))
		{
			//Get the page content request
			$this->getView()->showSurePage($this->getModel()->getInputString("active", "", "G"));
		}
		else
		{

		}
	}
	
	/** 
	 * Downloads and installs a template.
	 */
	protected function installRequest(){
		$this->setState('INSTALLING_TEMPLATE');
		
		//Gets template data
		$data = $this->getModel()->getAndInstall();
		
		//Show data
		$this->getView()->showMessage($data);
	}
	
	/** 
	 * Downloads and installs a template.
	 */
	protected function previewRequest(){
		$this->setState('PREVIEWING_TEMPLATE');
		
		//Show data
		$this->getView()->previewRequest($this->getModel()->getInputString("template", null, "G"));
	}
	
	/**
	 * Editor Request to get a page.
	 */
	protected function changeRequest(){
		$this->setState('CHANGE_REQUEST');
		
		//If no data has been submitted
		if($this->getModel()->getInputString("template", null, "P")==null)
		{
			//Get the page content request
			$active = $this->getModel()->getCurrentTemplate();
			
			//Get all available templates
			$all = $this->getModel()->getAllTemplates();
			
			//Create an editor for data
			$this->getView()->createTable($active, $all);
		}
		//Data has been submitted to be processed
		else
		{
			//Save data
			$this->getModel()->saveTemplateData();
			
			//Redirect
			$this->getView()->setTemplateRedirect();
		}
	}
}

?>