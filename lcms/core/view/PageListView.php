<?php

include("core/view/view.php");
include("core/lib/table.php");

class PageListView extends View{
	
	
	/**
	 * Starts the controller of the classes system
	 */
	public function PageListView(){
		//Meta is usually not setup yet, so we manually do this before loading css file
		$this->meta = new Meta();
		
		//This class requires an extra css file
		$this->getMeta()->addExtra('<link href="core/fragments/css/admin.css" rel="stylesheet" type="text/css" />');
	}	
	
	/**
	 * Shows a list of the pages 
	 */
	public function showPageList($pages){
		
		// Set the state and tell plugins.
		$this->setState('SHOWING_PAGE_LIST');
		$this->notifyObservers();
		
		//Get Top of pagelist
		$content = $this->openFile("core/fragments/admin_pagelist.phtml");
		
		//Creates a list from the supplied data
		$content .= $this->createPageList($pages);
		
		//Get Bottom of page list
		$content .= $this->openFile("core/fragments/admin_pagelist_bottom.phtml");
		
		//Print this dashboard
		$this->setContent($content);	
	}
	
	/**
	 * Changes an array of pagenames into a table with "edit", "view" and "delete" options
	 */
	protected function createPageList($data){
		
		// Set the state and tell plugins.
		$this->setState('CREATING_PAGE_LIST_TABLE');
		$this->notifyObservers();
		
		//Create new Table item
		$t = new Table();
		
		//Create new Table
		$t->createTable("Pagelist");
		
		//Sets intial row as headings
		$t->setHead(true);
		
		//Add the heading row.
		$t->addRow(
				array(
					"Page Title",
					"Edit Option",
					"View Option",
					"Delete Option"
				)					
			  );
		
		//Loop Through each page
		for($i = 0; $i < count($data); $i++)
		{
			//Add Page data for each row.
			$t->addRow(
					array(
						$data[$i],
						"<a href='?system=Editor&page=editor&active=".$data[$i]."'>Edit</a>",
						"<a href='?page=".$data[$i]."'>View</a>",
						"<a href='?system=Editor&page=delete&active=".$data[$i]."'>Delete</a>",					
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