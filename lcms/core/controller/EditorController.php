<?php
class EditorController extends Controller{

	public function EditorController($page){
		$this->setup("Editor", "Edit Page");
	}
	
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
	 * Show default index
	 */
	protected function indexRequest(){
		// Set the state and tell plugins.
		$this->setState('LOADING_DASH');
		
		//Print the Dash
		$this->getView()->showDash();
	}

	/**
	 * Show default index
	 */
	protected function deleteRequest(){
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
								$this->getView()->localize("Page was deleted successfully.")
							 );
		}
	}
	
	/**
	 * Show default classes
	 */
	protected function clearCacheRequest(){
		//Redirect to Dashboard
		$this->getModel()->clearCache();	
	}
	
	/**
	 * Editor Request to get a page.
	 */
	protected function editorRequest(){
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
			$this->getView()->redirectSuccess($this->getView()->localize("The page was successfully saved."));
		}
	}
	
	/**
	 * Editor Request to get a page.
	 */
	protected function createPageRequest(){
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

?>