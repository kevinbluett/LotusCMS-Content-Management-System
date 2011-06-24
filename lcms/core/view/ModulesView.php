<?php

include("core/view/view.php");
include("core/lib/table.php");

class ModulesView extends View{
	
	
	/**
	 * Starts the controller of the classes system
	 */
	public function ModulesView(){	
		//Meta is usually not setup yet, so we manually do this before loading css file
		$this->meta = new Meta();
		
		//This class requires an extra css file
		$this->getMeta()->addExtra('<link href="core/fragments/css/admin.css" rel="stylesheet" type="text/css" />');
	}	
	
	/**
	 * Show the Dashboard
	 */
	public function createEditor($data){
		
		// Set the state and tell plugins.
		$this->setState('CREATE_EDITOR');
		$this->notifyObservers();
		
		//Change the content into a form
		$content = $this->singleForm($data[0], $data[1]);
		
		//Print this dashboard
		$this->setContent($content);	
	}
	
	/**
	 * Convert data into table
	 */
	protected function runModulesToTable($data){
		
		// Set the state and tell plugins.
		$this->setState('MODULES_TO_TABLE');
		$this->notifyObservers();
		
		//Create new Table item
		$t = new Table();
		
		//Create new Table
		$t->createTable("Modules");
		
		//Sets intial row as headings
		$t->setHead(false);
		
		//Get length of the count
		$y = count($data);
		
		//Loop Through each page
		for($i = 0; $i < $y; $i = $i + 4)
		{
			//Create a row
			$row = array();
			
			//Create first row
			$row[] = "<div style='width: 97px;border: 1px solid #e3e3e3;margin-bottom: 5px;'><a href='?system=Modules&page=load&active=".$data[$i]['title']."'><img style='padding-left: 4px;border-style: none;' src='".$data[$i]['img']."' alt='Module Image' /></a><br /><p style='text-align:center; font-size: 13.5px;margin-top: 0px;'><a href='?system=Modules&page=load&active=".$data[$i]['title']."'>".$data[$i]['title']."</a></p></div>";
			
			if(($i+1)<$y)
			{
				//Create the second row
				$row[] = "<div style='width: 97px;border: 1px solid #e3e3e3;margin-bottom: 5px;'><a href='?system=Modules&page=load&active=".$data[$i+1]['title']."'><img style='padding-left: 4px;border-style: none;' src='".$data[$i+1]['img']."' alt='Module Image' /></a><br /><p style='text-align:center; font-size: 13.5px;margin-top: 0px;'><a href='?system=Modules&page=load&active=".$data[$i+1]['title']."'>".$data[$i+1]['title']."</a></p></div>";
			}
			
			if(($i+2)<$y)
			{
				//Create the second row
				$row[] = "<div style='width: 97px;border: 1px solid #e3e3e3;margin-bottom: 5px;'><a href='?system=Modules&page=load&active=".$data[$i+2]['title']."'><img style='padding-left: 4px;border-style: none;' src='".$data[$i+2]['img']."' alt='Module Image' /></a><br /><p style='text-align:center; font-size: 13.5px;margin-top: 0px;'><a href='?system=Modules&page=load&active=".$data[$i+2]['title']."'>".$data[$i+2]['title']."</a></p></div>";
			}
			
			if(($i+3)<$y)
			{
				//Create the second row
				$row[] = "<div style='width: 97px;border: 1px solid #e3e3e3;margin-bottom: 5px;'><a href='?system=Modules&page=load&active=".$data[$i+3]['title']."'><img style='padding-left: 4px;border-style: none;' src='".$data[$i+3]['img']."' alt='Module Image' /></a><br /><p style='text-align:center; font-size: 13.5px;margin-top: 0px;'><a href='?system=Modules&page=load&active=".$data[$i+3]['title']."'>".$data[$i+3]['title']."</a></p></div>";
			}
			
			//Add Page data for each row.
			$t->addRow(
					$row
				  );
		}
		
		//Creates a table from inserted data
		$t->runTable();
		
		//Returns the created table.
		return $t->getTable();
	}
	
	/**
	 * Creates the placeholder for a plugins administraion
	 */ 
	public function showModuleAdministration($data){
		
		// Set the state and tell plugins.
		$this->setState('SHOW_MODULE_ADMIN');
		$this->notifyObservers();
		
		//Get the top bit of the page
		$out = $this->getController()->getModel()->openFile("core/fragments/moduleAdministration.phtml");
		
		//Put in the unix name
		$out = str_replace("%UNIX%", $data[0], $out);
		
		//Place the content
		$out .= $data[1];
		
		//Set content
		$this->setContent($out);
		
		//Set Title
		$this->setContentTitle("Module: ".$data[0]);
	}
	
	/**
	 * Show Module Information
	 */
	public function showModuleInformation($modules){
		
		// Set the state and tell plugins.
		$this->setState('SHOW_MODULE_INFO');
		$this->notifyObservers();
		
		//Get Module Title
		$title = $modules->getTitle();
		
		//Get Module Author
		$author = $modules->getAuthor();
		
		//Get Organisation
		$organisation = $modules->getOrganisation();
		
		//Gets the version
		$version = $modules->getVersion();
		
		//Administration
		$admin = $modules->getAdministration();
		
		//If Administraion Exists;
		if($admin)
		{
			$admin = "<a href='?system=Modules&page=admin&active=".$modules->getUnix()."'>Module Administration</a>";
		}
		//If no administration exists;
		else
		{
			$admin = "No Administration";
		}
		
		//Support link
		$support = $modules->getSupport();
		
		//Sets the content title
		$this->setContentTitle("Module: ".$title);
		
		//Get the default content
		$out = $this->getController()->getModel()->openFile("core/fragments/moduleInfo.phtml");
		
		//Replace Administraion
		$out = str_replace("%ADMINISTRATION%", $admin, $out);
		
		//Replace Title
		$out = str_replace("%TITLE%", $title, $out);
		
		//Replace Unix
		$out = str_replace("%UNIX%", $modules->getUnix(), $out);

		//Replace AUthor
		$out = str_replace("%AUTHOR%", $author, $out);
		
		//Replace Organisation
		$out = str_replace("%ORGANISATION%", $organisation, $out);
		
		//Replace Support
		$out = str_replace("%SUPPORT%", $support, $out);
		
		//Replace Version
		$out = str_replace("%VERSION%", $version, $out);
		
		//Basic 
		$image = "";
		
		if(file_exists("modules/".$modules->getUnix()."/logo.png")){
			$image = "modules/".$modules->getUnix()."/logo.png";
		}
		else
		{
			$image = "style/comps/admin/img/module_noimg.png";
		}
		
		//Replace Image
		$out = str_replace("%IMAGE%", $image, $out);
		
		//Set this as content;
		$this->setContent($out);
	}
	
	/**
	 * Shows update message or not
	 */
	public function updateMessage($data){
		
		$this->setState('SHOWING_UPDATE_MESSAGE');
		$this->notifyObservers();
		
		//Gets the input string
		$req = $this->getController()->getModel()->getInputString("req", "", "G");
	
		//Update is available.
		if($data){
			//Gets the template in this case.
			$out = $this->openFile("core/fragments/modules/updateAvailable.phtml");
			
			//Puts the module in the link
			$out = str_replace("%MODULE%", $req, $out);
			
			$this->setContent($out);	
		}
		//Else no update available
		else{			
			//Show error message on redirected to page
			$_SESSION['ERROR_TYPE'] = "error";
			$_SESSION['ERROR_MESSAGE'] = "No Module Update Available.";
			
			//Go Redirect
			$this->setRedirect("index.php?system=Modules&page=list");	
		}
		
		$this->setState('FINISHED_UPDATE_MESSAGE');
		$this->notifyObservers();
	}
	
	/**
	 * Shows installed plugins.
	 */
	public function showPlugins($data){
		
		// Set the state and tell plugins.
		$this->setState('START_SHOWING_PLUGINS');
		$this->notifyObservers();
		
		//Get the text for above the table of plugins
		$out = $this->openFile("core/fragments/modules/allPlugins.phtml");
		
		//Create new Table item
		$t = new Table();
		
		
		//Create new Table
		$t->createTable("Modules");
		
		$t->addID("moduleTable");
		
		//Sets intial row as headings
		$t->setHead(true);
		
		$titles = array(
							"Module Name",
							"Change Status",
							"Updates",
							"Uninstall"
					   );
					   
		//Adds a row as titles.
		$t->addRow($titles);
		
		//Loop Through each page
		for($i = 0; $i < count($data); $i++)
		{
			$link = "";
			$uninstall = "";
			
			//Ensure the disabling of plugin is allowed.
			if(!($data[$i][2])){
				$link = "<a href='index.php?system=Modules&page=deactivate&req=".$data[$i][0]."'>Deactivate</a>";
			}
			
			if(!$data[$i][1]){
				$link = "<a href='index.php?system=ModulesInstall&page=activate&active=".$data[$i][0]."'>Activate</a>";
			}
			
			$update = "<a href='index.php?system=Modules&page=updateCheck&req=".$data[$i][0]."'>Check</a>";
			
			if(!($data[$i][2])){
				$uninstall = "<a href='index.php?system=Modules&page=uninstall&req=".$data[$i][0]."'>Uninstall</a>";
			}
			
			//Create a row
			$row = array(
							$data[$i][0],
							$link,
							$update,
							$uninstall
						);
			
			//Add Page data for each row.
			$t->addRow(
					$row
				  );
		}
		
		//Creates a table from inserted data
		$t->runTable();
		
		//Returns the created table.
		$out .= $t->getTable();
		
		//Sets the generated content as output
		$this->setContent($out);
		
		// Set the state and tell plugins.
		$this->setState('END_SHOWING_PLUGINS');
		$this->notifyObservers();
	}
	
	/**
	 * Shows uninstall success or fail message
	 */
	public function showUninstallMessage($out){
		
		$data = $this->openFile("core/fragments/modules/allPlugins.phtml");
		
		if($out){
			//success - redirect to list with success message above.
			// Set the state and tell plugins.
			$this->setState('SUCCESS_REDIRECT');
			$this->notifyObservers();
		
			//Show success message on redirected to page
			$_SESSION['ERROR_TYPE'] = "success";
			$_SESSION['ERROR_MESSAGE'] = "Module successfully removed from the CMS.";
			
			//Go Redirect
			$this->setRedirect("index.php?system=Modules&page=list");	
			
		}else{
			
			//success - redirect to list with success message above.
			// Set the state and tell plugins.
			$this->setState('FAIL_MESSAGE');
			$this->notifyObservers();
			
			//Failed
			$data .= $this->openFile("core/fragments/modules/failedUninstall.phtml");
			
			//Set the content area.
			$this->setContent($data);
		}
	}
	
	/**
	 * Shows uninstall success or fail message
	 */
	public function showDisableMessage($out){
		
		$data = $this->openFile("core/fragments/modules/allPlugins.phtml");
		
		if($out){
			//success - redirect to list with success message above.
			// Set the state and tell plugins.
			$this->setState('SUCCESS_REDIRECT');
			$this->notifyObservers();
		
			//Show success message on redirected to page
			$_SESSION['ERROR_TYPE'] = "success";
			$_SESSION['ERROR_MESSAGE'] = "Module successfully deactivated.";
			
			//Go Redirect
			$this->setRedirect("index.php?system=Modules&page=list");	
			
		}else{
			
			//success - redirect to list with success message above.
			// Set the state and tell plugins.
			$this->setState('FAIL_MESSAGE');
			$this->notifyObservers();
			
			//Failed
			$data .= $this->openFile("core/fragments/modules/failedUninstall.phtml");
			
			//Set the content area.
			$this->setContent($data);
		}
	}
	
	/**
	 * Create a form for the page.
	 */
	public function showInstalledModules($data){
		
		// Set the state and tell plugins.
		$this->setState('SHOW_INSTALLED_MODULES');
		$this->notifyObservers();
		
		//Get the form
		$out = $this->openFile("core/fragments/listTop.phtml");
		
		//Run the data into table
		$out .= $this->runModulesToTable($data);
		
		//Set all the content
		$this->setContent($out);
		
		// Set the state and tell plugins.
		$this->setState('GETTING_MODULE_INFO');
		$this->notifyObservers();
	}
}

?>