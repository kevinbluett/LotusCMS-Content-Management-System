<?php
class EditorController extends Controller{

	public function EditorController($page){
		$this->setup("Editor", "Edit Page");
	}
	
	protected function putRequests(){
		$requests = array(
					"clearCache",
					"editor",
					"createPage",
					"delete"
				 );
		$this->setRequests($requests);
	}

	protected function deleteRequest(){
		$this->setState('DELETE_REQUEST');
		//If no data has been submitted
		if($this->getModel()->getInputString("acc", null, "G")==null)
		{
			//Print the Dash
			$this->getView()->showDelete($this->getModel()->getActiveRequest);
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
	
	protected function clearCacheRequest(){
		$this->setState('CLEARING_CACHE');
		$this->getModel()->clearCache();	
	}
	
	/**
	 * Editor Request to get a page.
	 */
	protected function editorRequest(){
		$this->setState('EDITOR_REQUEST');
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
			$data['unix'] = $this->getModel()->fixPageName($data['unix']);
			
			//Save page into the page directly
			$this->getModel()->savePage($data['unix'], $data['title'], $data['template'], $data['published'], $data['content']);
			
			//Show the saved successfully yoke.
			$this->getView()->redirectSuccess($this->getView()->localize("The page was successfully saved."));
		}
	}
	
	/**
	 * Editor Request to get a page.
	 */
	protected function createPageRequest(){
		$this->setState('CREATE_PAGE_REQUEST');
		if($this->getModel()->getInputString("title", null, "P")==null)
		{
			//Shows a form to create a new page
			$this->getView()->showCreateForm();
		}else{
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