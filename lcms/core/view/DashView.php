<?php
include("core/lib/table.php");

class DashView extends View{

	public function DashView(){}	
	

	public function showDash(){
		
		//Localize Dashboard text (Before Plugins hook in.)
		$this->setContentTitle($this->localize("Dashboard"));

		$this->setState('LOADING_DASH');

		//Get Dashboard screen
		$content = $this->openFile("core/fragments/admin_dashboard.phtml");
		
		$this->setContent($content);	
		
		$this->setState('FINISHED_DASH');
	}
}

?>