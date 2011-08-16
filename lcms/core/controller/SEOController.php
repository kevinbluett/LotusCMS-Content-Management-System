<?php
class SEOController extends Controller{
	
	public function SEOController($page){
		
		$this->setup("SEO","SEO Settings");
	}
	
	protected function putRequests(){
		$requests = array("edit");
		$this->setRequests($requests);
	}
	
	/**
	 * Editor Request to get a page.
	 */
	protected function editRequest(){
		//If no data has been submitted
		if($this->getModel()->getInputString("seokeywords", null, "P")==null)
		{
			//Get the page content request
			$data = $this->getModel()->getSEOData();
			
			//Create an editor for data
			$this->getView()->createEditor($data);
		}
		//Data has been submitted to be processed
		else
		{
			//Save data
			$this->getModel()->saveSEOData();
			
			//Redirect
			$this->getView()->setSEORedirect();
		}
	}
}

?>