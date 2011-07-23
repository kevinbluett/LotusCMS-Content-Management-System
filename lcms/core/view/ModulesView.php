<?php
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
		$this->setState('CREATE_EDITOR');
		
		//Change the content into a form
		$content = $this->singleForm($data[0], $data[1]);
		
		//Print this dashboard
		$this->setContent($content);	
		$this->setContentTitle($this->localize("Module Manager"));
	}
	
	/**
	 * Convert data into table
	 */
	protected function runModulesToTable($data){
		$this->setState('MODULES_TO_TABLE');
		
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
			$row[] = "<div style='width: 97px;border: 1px solid #e3e3e3;margin-bottom: 5px;'><a href='?system=Modules&page=admin&active=".$data[$i]['title']."'><img style='padding-left: 4px;border-style: none;' src='".$data[$i]['img']."' alt='Module Image' /></a><br /><p style='text-align:center; font-size: 13.5px;margin-top: 0px;'><a href='?system=Modules&page=admin&active=".$data[$i]['title']."'>".$this->localize($data[$i]['title'])."</a></p></div>";
			
			if(($i+1)<$y)
			{
				//Create the second row
				$row[] = "<div style='width: 97px;border: 1px solid #e3e3e3;margin-bottom: 5px;'><a href='?system=Modules&page=admin&active=".$data[$i+1]['title']."'><img style='padding-left: 4px;border-style: none;' src='".$data[$i+1]['img']."' alt='Module Image' /></a><br /><p style='text-align:center; font-size: 13.5px;margin-top: 0px;'><a href='?system=Modules&page=admin&active=".$data[$i+1]['title']."'>".$this->localize($data[$i+1]['title'])."</a></p></div>";
			}
			
			if(($i+2)<$y)
			{
				//Create the second row
				$row[] = "<div style='width: 97px;border: 1px solid #e3e3e3;margin-bottom: 5px;'><a href='?system=Modules&page=admin&active=".$data[$i+2]['title']."'><img style='padding-left: 4px;border-style: none;' src='".$data[$i+2]['img']."' alt='Module Image' /></a><br /><p style='text-align:center; font-size: 13.5px;margin-top: 0px;'><a href='?system=Modules&page=admin&active=".$data[$i+2]['title']."'>".$this->localize($data[$i+2]['title'])."</a></p></div>";
			}
			
			if(($i+3)<$y)
			{
				//Create the second row
				$row[] = "<div style='width: 97px;border: 1px solid #e3e3e3;margin-bottom: 5px;'><a href='?system=Modules&page=admin&active=".$data[$i+3]['title']."'><img style='padding-left: 4px;border-style: none;' src='".$data[$i+3]['img']."' alt='Module Image' /></a><br /><p style='text-align:center; font-size: 13.5px;margin-top: 0px;'><a href='?system=Modules&page=admin&active=".$data[$i+3]['title']."'>".$this->localize($data[$i+3]['title'])."</a></p></div>";
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
		$this->setState('SHOW_MODULE_ADMIN');
		
		//Get the top bit of the page
		$out = $this->getController()->getModel()->openFile("core/fragments/moduleAdministration.phtml");
		
		//Put in the unix name
		$out = str_replace("%UNIX%", $data[0], $out);
		$out = str_replace("%ADMINISTRATION_LOCALE%", $this->localize("Module Administration"), $out);
		$out = str_replace("%MODULE_INFORMATION_LOCALE%", $this->localize("Module Information"), $out);
		
		//Place the content
		$out .= $data[1];
		
		//Set content
		$this->setContent($out);
		
		//Set Title
		$this->setContentTitle($this->localize("Module:")." ".$data[0]);
	}
	
	/**
	 * Show Module Information
	 */
	public function showModuleInformation($modules){
		$this->setState('SHOW_MODULE_INFO');
		
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
			$admin = "<a href='?system=Modules&page=admin&active=".$modules->getUnix()."'>".$this->localize("Module Administration")."</a>";
		}
		//If no administration exists;
		else
		{
			$admin = $this->localize("Module Administration");
		}
		
		//Support link
		$support = $modules->getSupport();
		
		//Sets the content title
		$this->setContentTitle("Module: ".$title);
		
		//Get the default content
		$out = $this->getController()->getModel()->openFile("core/fragments/moduleInfo.phtml");
		
		//Replace Administraion
		$out = str_replace("%ADMINISTRATION%", $admin, $out);
		$out = str_replace("%ADMINISTRATION_LOCALE%", $this->localize("Administration"), $out);
		
		//Replace Title
		$out = str_replace("%TITLE%", $title, $out);
		$out = str_replace("%TITLE_LOCALE%", $this->localize("Title"), $out);
		
		//Replace Unix
		$out = str_replace("%UNIX%", $modules->getUnix(), $out);

		//Replace AUthor
		$out = str_replace("%AUTHOR%", $author, $out);
		$out = str_replace("%AUTHOR_LOCALE%", $this->localize("Author"), $out);
		
		//Replace Organisation
		$out = str_replace("%ORGANISATION%", $organisation, $out);
		$out = str_replace("%ORGANISATION_LOCALE%", $this->localize("Organisation"), $out);
		
		//Replace Support
		$out = str_replace("%SUPPORT%", $support, $out);
		$out = str_replace("%SUPPORT_AVAILABLE_AT_LOCALE%", $this->localize("Support Available at"), $out);
		
		//Replace Version
		$out = str_replace("%VERSION%", $version, $out);
		$out = str_replace("%VERSION_NUMBER_LOCALE%", $this->localize("Version Number"), $out);
		
		$out = str_replace("%MODULE_INFORMATION_LOCALE%", $this->localize("Module Information"), $out);
		
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
		$this->setContentTitle($this->localize("Module Manager"));
	}
	
	/**
	 * Shows update message or not
	 */
	public function updateMessage($data){
		
		$this->setState('SHOWING_UPDATE_MESSAGE');

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
	}
	
	/**
	 * Shows installed plugins.
	 */
	public function showPlugins($data){
		$this->setState('START_SHOWING_PLUGINS');
		
		//Get the text for above the table of plugins
		$out = $this->openFile("core/fragments/listTop.phtml");
		
		$out = $this->setTabActive(2, $out);
		
		//Create new Table item
		$t = new Table();
		
		
		//Create new Table
		$t->createTable("Modules");
		
		$t->addID("moduleTable");
		
		//Sets intial row as headings
		$t->setHead(true);
		
		$titles = array(
					$this->localize("Module Name"),
					$this->localize("Change Status"),
					$this->localize("Updates"),
					$this->localize("Uninstall")
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
				$link = "<a href='index.php?system=Modules&page=deactivate&req=".$data[$i][0]."'>".$this->localize("Deactivate")."</a>";
			}
			
			if(!$data[$i][1]){
				$link = "<a href='index.php?system=ModulesInstall&page=activate&active=".$data[$i][0]."'>".$this->localize("Activate")."</a>";
			}
			
			if($this->getController()->getModel()->checkModForUpdates($data[$i][0])){
				$update = "<a style='color: red;' href='index.php?system=Modules&page=updateCheck&req=".$data[$i][0]."'>".$this->localize("Updates Available")."</a>";
			}else{
				$update =  "<a style='color: green;' href='index.php?system=Modules&page=updateCheck&req=".$data[$i][0]."'>".$this->localize("Up to Date")."</a>";
			}
			
			if(!($data[$i][2])){
				$uninstall = "<a href='index.php?system=Modules&page=uninstall&req=".$data[$i][0]."'>".$this->localize("Uninstall")."</a>";
			}
			
			//Create a row
			$row = array(
							$this->localize($data[$i][0]),
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
		
		//Localise Title
		$this->setContentTitle($this->localize("Module Manager"));
		$this->setState('END_SHOWING_PLUGINS');
	}
	
	/**
	 * Check All Mods
	 */
	public function showCheckAllMods($data){
		$this->setContent($data);
	}
	
	/**
	 * Shows uninstall success or fail message
	 */
	public function showUninstallMessage($out){
		
		if($out){
			//success - redirect to list with success message above.
			$this->setState('SUCCESS_REDIRECT');
		
			//Show success message on redirected to page
			$_SESSION['ERROR_TYPE'] = "success";
			$_SESSION['ERROR_MESSAGE'] = "Module successfully removed from the CMS.";
			
			//Go Redirect
			$this->setRedirect("index.php?system=Modules&page=list");	
			
		}else{
			
			//success - redirect to list with success message above.
			$this->setState('FAIL_MESSAGE');
			
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
				
		if($out){
			//success - redirect to list with success message above.
			$this->setState('SUCCESS_REDIRECT');
		
			//Show success message on redirected to page
			$_SESSION['ERROR_TYPE'] = "success";
			$_SESSION['ERROR_MESSAGE'] = "Module successfully deactivated.";
			
			//Go Redirect
			$this->setRedirect("index.php?system=Modules&page=list");	
			
		}else{
			
			//success - redirect to list with success message above.
			$this->setState('FAIL_MESSAGE');
			
			//Failed
			$data .= $this->openFile("core/fragments/modules/failedUninstall.phtml");
			
			//Set the content area.
			$this->setContent($data);
			$this->setContentTitle($this->localize("Module Manager"));
		}
	}
	
	/**
	 * A function for the settings file that sets which tab is active + Localization of Tabs
	 */ 
	protected function setTabActive($active = 1, $tabs){
		
		//Localization
		$tabs = str_replace("%CURRENTLY_ACTIVE_MODULES_LOCALE%", $this->localize("Currently Active Modules"), $tabs);
		$tabs = str_replace("%ALL_INSTALLED_MODULES_LOCALE%", $this->localize("All Installed Modules"), $tabs);
		$tabs = str_replace("%FIND_MORE_PLUGINS_LOCALE%", $this->localize("Find More Plugins"), $tabs);
		
		if($active==1){
			$tabs = str_replace("%ONE%", "active", $tabs);
		}else{
			$tabs = str_replace("%ONE%", "inactive", $tabs);
		}
		if($active==2){
			$tabs = str_replace("%TWO%", "active", $tabs);
		}else{
			$tabs = str_replace("%TWO%", "inactive", $tabs);
		}
		if($active==3){
			$tabs = str_replace("%THREE%", "active", $tabs);
		}else{
			$tabs = str_replace("%THREE%", "inactive", $tabs);
		}
		return $tabs;
	}
	
	/**
	 * Create a form for the page.
	 */
	public function showInstalledModules($data){
		$this->setState('SHOW_INSTALLED_MODULES');
		
		//Get the form
		$out = $this->openFile("core/fragments/listTop.phtml");
		
		$out = $this->setTabActive(1, $out);
		
		//Run the data into table
		$out .= $this->runModulesToTable($data);
		
		//Set all the content
		$this->setContent($out);
		$this->setContentTitle($this->localize("Module Manager"));
		
		$this->setState('GETTING_MODULE_INFO');
	}
}

?>