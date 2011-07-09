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
		
		//Set this tab active
		$content = $this->setTabActive(1, $content);
		
		$content = str_replace("%PAGELIST_LOCALE%", $this->localize("Existing Pages"), $content);
		$content = str_replace("%CREATE_NEW_PAGE_LOCALE%", $this->localize("Create New Page"), $content);
		
		//Creates a list from the supplied data
		$content .= $this->createPageList($pages);
		
		//Ends the divider
		$content .= "</div>";
		
		//Print this dashboard
		$this->setContent($content);	
		$this->setContentTitle($this->localize("Pages"));
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
					$this->localize("Page Title"),
					$this->localize("Edit Option"),
					$this->localize("View Option"),
					$this->localize("Delete Option")
				)					
			  );
		
		//Loop Through each page
		for($i = 0; $i < count($data); $i++)
		{
			//Add Page data for each row.
			$t->addRow(
					array(
						$data[$i],
						"<a href='?system=Editor&page=editor&active=".$data[$i]."'>".$this->localize("Edit")."</a>",
						"<a href='?page=".$data[$i]."'>".$this->localize("View")."</a>",
						"<a href='?system=Editor&page=delete&active=".$data[$i]."'>".$this->localize("Delete")."</a>",					
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