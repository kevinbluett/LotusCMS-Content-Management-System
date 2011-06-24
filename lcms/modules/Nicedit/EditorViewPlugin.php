<?php
 
/**
 * Nicedit Plugin
 */
class NiceditView extends Observer{
	
	protected $view;
	
	public function NiceditView(){

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
    	$script = '<script src="http://js.nicedit.com/nicEdit-latest.js" type="text/javascript"></script><script type="text/javascript">bkLib.onDomLoaded(nicEditors.allTextAreas);</script><script type="text/javascript"> $(document).ready(function(){
       $("#main input").bind("click", function() {
		  nicEditors.findEditor("pagedata").saveContent();document.myForm.submit();
		});

     });</script>';
    	
    	//Add this script to the page
    	$this->view->getMeta()->addExtra($script);					
    }
}
 
?>