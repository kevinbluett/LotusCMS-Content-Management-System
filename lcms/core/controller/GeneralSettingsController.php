<?php
class GeneralSettingsController extends Controller{
	
	public function GeneralSettingsController($page){
		$this->setup("GeneralSettings","LotusCMS Settings");
	}
	
	/**
	 * Sets the requests of the system
	 */
	protected function putRequests(){
		
		//Create the array of request
		$requests = array(
					"edit"
				);
		
		//Set all the request
		$this->setRequests($requests);
	}
	
	/**
	 * Editor Request to get a page.
	 */
	protected function editRequest(){
		//If no data has been submitted
		if($this->getModel()->getInputString("title", null, "P")==null)
		{
			// Set the state and tell plugins.
			$this->setState('CREATING_EDITOR');
			$this->notifyObservers();
	
			//Get the page content request
			$data = $this->getModel()->getWebsiteData();
			
			//Create an editor for data
			$this->getView()->createEditor($data);
		}
		//Data has been submitted to be processed
		else
		{
			// Set the state and tell plugins.
			$this->setState('SAVING_DETAILS');
			$this->notifyObservers();
			
			//Save data
			$this->getModel()->saveWebsiteData();
			
			//Redirect
			$this->getView()->setWebsiteRedirect();
		}
	}
}

?>