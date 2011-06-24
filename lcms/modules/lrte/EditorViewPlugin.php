<?php
 
/**
 * Nicedit Plugin
 */
class lrteView extends Observer{
	
	protected $view;
	
	public function lrteView(){

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
    	$script = '<link href="modules/lrte/code/jquery.rte.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="modules/lrte/code/jquery.rte.js"></script>
<script type="text/javascript" src="modules/lrte/code/jquery.rte.tb.js"></script>
<script type="text/javascript" src="modules/lrte/code/jquery.ocupload-1.1.4.js"></script><script type="text/javascript">
$(document).ready(function() {
	var arr = $("#pagedata").rte({
		css: ["default.css"],
		controls_rte: rte_toolbar,
		controls_html: html_toolbar
	});
});
</script>';
    	
    	//Add this script to the page
    	$this->view->getMeta()->addExtra($script);					
    }
}
 
?>