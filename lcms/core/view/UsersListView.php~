<?php

include("core/view/view.php");
include("core/lib/table.php");

class UsersListView extends View{
	
	
	/**
	 * Starts the controller of the classes system
	 */
	public function UsersListView(){
		//Meta is usually not setup yet, so we manually do this before loading css file
		$this->meta = new Meta();
		
		//This class requires an extra css file
		$this->getMeta()->addExtra('<link href="core/fragments/css/admin.css" rel="stylesheet" type="text/css" />');
		$this->getMeta()->addExtra('<script type="text/javascript" src="core/fragments/js/application.js"></script>');
	}	
	
	/**
	 * Shows a list of the pages 
	 */
	public function showUsersList($pages){
		
		// Set the state and tell plugins.
		$this->setState('SHOWING_USER_LIST');
		$this->notifyObservers();
		
		//Get Top of pagelist
		$content = $this->openFile("core/fragments/admin_userslist.phtml");
		
		//Set this tab active
		$content = $this->setTabActive(1, $content);
		
		$content = str_replace("%USERLIST_LOCALE%", $this->localize("Manage Users"), $content);
		$content = str_replace("%CREATE_NEW_USER_LOCALE%", $this->localize("Create New User"), $content);
		$content = str_replace("%USER_TITLE_LOCALE%", $this->localize("LotusCMS Users"), $content);
		$content = str_replace("%USER_TYPE_FILTER_LOCALE%", $this->localize("Type here to filter results..."), $content);
		$content = str_replace("%NO_RESULTS_LOCALE%", $this->localize("No Results"), $content);
		
		//Creates a list from the supplied data
		$content .= $this->createUsersList($pages);
		
		//Print this dashboard
		$this->setContent($content);	
		
		//Localize content
		$this->setContentTitle($this->localize("Users"));
	}
	
	/**
	 * Changes an array of pagenames into a table with "edit", "view" and "delete" options
	 */
	protected function createUsersList($data){
		
		// Set the state and tell plugins.
		$this->setState('CREATING_USER_TABLE');
		$this->notifyObservers();
		
		//Create new Table item
		$t = new Table();
		
		//Create new Table
		$t->createTable("Userslist");
		
		//Sets intial row as headings
		$t->setHead(false);
		
		//Loop Through each page
		for($i = 0; $i < count($data); $i++)
		{
			//Add Page data for each row.
			$t->addRow(
					array(
						$data[$i],
						"<a href='?system=Users&page=edit&active=".$data[$i]."'>".$this->localize("Edit")."</a>",
						"<a href='?system=Users&page=delete&active=".$data[$i]."'>".$this->localize("Delete")."</a>",					
					)
				  );
		}
		
		//Creates a table from inserted data
		$t->runTable();
		
		//Returns the created table.
		return $t->getTable();
	}
}

?>