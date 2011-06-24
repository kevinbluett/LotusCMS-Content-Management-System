<?php
 
/**
 * Nicedit Plugin
 */
class TinyMCEView extends Observer{
	
	protected $view;
	
	public function TinyMCEView(){

	}
	
	/**
	 * Sets up the listening system
	 */
	public function setupObserver(& $view){
		Observer::Observer($view);
		
		$this->view = $view;
	}

    /**
    * Implement the parent update() method checking the state
    * of the observable subject
    * @return void
    */
    function update () {
        if ( $this->subject->getState() == 'CREATING_NEWPAGE_EDITOR'|| $this->subject->getState() == 'CREATING_PAGE_FORM' ) {
        	
        	//Add editor to all the text areas.
            $this->addEditor();
        }
    }
    
    public function addEditor(){
    	//The actual Script
    	$script = '<script type="text/javascript" src="modules/TinyMCE/tiny_mce/tiny_mce.js"></script><script type="text/javascript" src="modules/TinyMCE/editor.js"></script>';
    	
    	//Add this script to the page
    	$this->view->getMeta()->addExtra($script);					
    }
}
 
?>
