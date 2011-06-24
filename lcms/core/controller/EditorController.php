<?php

//Default Controller
include("core/controller/controller.php");

class EditorController extends Controller{
	
	/**
	 * Starts the controller of the classes system
	 */
	public function EditorController($page){
		
		//Allow Plugins.
		Observable::Observable();
		
		//Setup basic variables
		$this->varSetup();
		
		//Sets the name of the other classes
		$this->setSystem("Editor");
		
		//Setup the page
		$this->setup("Edit Page");
		
		//Set to Administration template
		$this->getView()->setTemplate("admin");
		
		//Check for Plugins
		$this->loadPlugins();
		
		//Set the requests accepted
		$this->putRequests();
		
		//Process Request
		$this->processRequest();
		
		$this->displayPage();
	}
	
	/**
	 * Sets the requests of the system
	 */
	protected function putRequests(){
		
		//Create the array of request
		$requests = array(
					"index",
					"clearCache",
					"editor",
					"createPage",
					"delete"
				 );
		
		//Set all the request
		$this->setRequests($requests);
	}
	
	/**
	 * Show default classes
	 */
	protected function defaultRequest(){
		
		//If the user is logged in
		if($this->getModel()->checkLogin(true))
		{
			//Redirect to Dashboard
			$this->getPaging()->setRedirect('?system=Dash&page=index');	
		}
		else
		{
			//Redirect to Login
			$this->getPaging()->setRedirect('?system=Admin&page=login');	
		}
	}

	/**
	 * Show default index
	 */
	protected function indexRequest(){
		
		//If the user is logged in
		if($this->getModel()->checkLogin(false))
		{
			// Set the state and tell plugins.
			$this->setState('LOADING_DASH');
			$this->notifyObservers();
			
			//Print the Dash
			$this->getView()->showDash();
		}
		else
		{
			//Redirect to Login
			$this->getPaging()->setRedirect('?system=Admin&page=login');	
		}
	}

	/**
	 * Show default index
	 */
	protected function deleteRequest(){
		
		//If the user is logged in
		if($this->getModel()->checkLogin(false))
		{
			//If no data has been submitted
			if($this->getModel()->getInputString("acc", null, "G")==null)
			{
				//Print the Dash
				$this->getView()->showDelete($this->getModel()->getInputString("active"));
			}
			//Data has been submitted to be processed
			else
			{
				//Delete the page
				$this->getModel()->deletePage();
				
				//Show the saved successfully yoke.
				$this->getView()->redirectSuccess(
									$this->getModel()->openFile("core/fragments/editor/deletePageTitle.phtml")
								 );
			}
		}
	}
	
	/**
	 * Show default classes
	 */
	protected function clearCacheRequest(){
		
		//If the user is logged in
		if($this->getModel()->checkLogin(true))
		{
			//Redirect to Dashboard
			$this->getModel()->clearCache();	
		}
		
	}
	
	/**
	 * Editor Request to get a page.
	 */
	protected function editorRequest(){
		
		//If the user is logged in
		if($this->getModel()->checkLogin(true))
		{
			//If no data has been submitted
			if($this->getModel()->getInputString("pagedata", null, "P")==null)
			{
				//Get the page content request
				$data = $this->getModel()->getPageData($this->getModel()->getActiveRequest());
				
				//Fix the tags in the page data
				$data = $this->getModel()->fixTags($data);
				
				//Create an editor for data
				$this->getView()->createEditor($data, $this->getModel()->getActiveRequest());
			}
			//Data has been submitted to be processed
			else
			{
				//Get the page content request
				$data = $this->getModel()->getSubmitData();
				
				//Fix the tags in the page data - incase it was somehow forced to be different.
				$data[0] = $this->getModel()->fixPageName($data[0]);
				
				//Save page into the page directly
				$this->getModel()->savePage($data[0], $data[1], $data[2], $data[3]);
				
				//Show the saved successfully yoke.
				$this->getView()->redirectSuccess($this->getModel()->openFile("core/fragments/editor/savePageTitle.phtml"));
			}
		}
	}
	
	/**
	 * Editor Request to get a page.
	 */
	protected function createPageRequest(){
		
		//If the user is logged in
		if($this->getModel()->checkLogin(true))
		{
			//If no data has been submitted
			if($this->getModel()->getInputString("title", null, "P")==null)
			{
				//Shows a form to create a new page
				$this->getView()->showCreateForm();
			}
			//If data has been submitted
			else
			{
				//Collects submission data
				$data = $this->getModel()->getSubmitData();
				
				//checks if any of the values are empty.
				$this->getModel()->checkPageValues($data);
				
				//Creates the new page name and saves initial file
				$this->getModel()->createNewPage($data);
				
				//Return to list of pages.
				$this->getPaging()->setRedirect('?system=PageList&page=list');
			}
		}
	}
}

?>