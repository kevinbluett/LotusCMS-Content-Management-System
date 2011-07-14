<?php

include("core/view/view.php");
include("core/lib/table.php");

class TemplateView extends View{
	
	
	/**
	 * Starts the controller of the classes system
	 */
	public function TemplateView(){
		//Meta is usually not setup yet, so we manually do this before loading css file
		$this->meta = new Meta();
		
		//This class requires an extra css file
		$this->getMeta()->addExtra('<link href="core/fragments/css/admin.css" rel="stylesheet" type="text/css" />');
	}	
	
	/**
	 * Show Installable Templates
	 */
	public function showInstallableTemplates($content){
		
		//Tabs above settings
		$tabs = $this->openFile("core/fragments/settings/SettingsDash.phtml");	
		
		//Activate Tab
		$tabs = $this->setTabActive(3, $tabs);
		
		//Localise
		$tabs = str_replace("%GENERAL_SETTINGS_LOCALE%", $this->localize("General Settings"), $tabs);
		$tabs = str_replace("%SEO_SETTINGS_LOCALE%", $this->localize("SEO Settings"), $tabs);
		$tabs = str_replace("%TEMPLATE_SETTINGS_LOCALE%", $this->localize("Template Settings"), $tabs);
		$tabs = str_replace("%CLEAR_CACHE_LOCALE%", $this->localize("Clear Cache"), $tabs);
		
		$getMore = $this->openFile("core/fragments/settings/backtemplate.phtml");
		
		$getMore = str_replace("%INSTALL_MORE_TEMPLATES_LOCALE%", $this->localize("Install More Templates"), $getMore);
		$getMore = str_replace("%INSTALLED_TEMPLATES_LOCALE%", $this->localize("Installed Templates"), $getMore);
		
		//Print this dashboard
		$this->setContent($tabs.$getMore.$content);	
		
	}
	
	/**
	 * Show the Dashboard
	 */
	public function createTable($active, $data){
		
		// Set the state and tell plugins.
		$this->setState('CREATE_TABLE');
		$this->notifyObservers();
                
                $content = '<form method="post" class="jNice" action="?system=Template&page=change" name="edit">' ;
                
		//Change the content into a form
		$content .= $this->singleTable($active, $data);
                
                $content .= '<input type="submit" value="'.$this->localize("Save").'" /></form>';
                
 		//Tabs above settings
		//Tabs above settings
		$tabs = $this->openFile("core/fragments/settings/SettingsDash.phtml");	
		
		//Activate Tab
		$tabs = $this->setTabActive(3, $tabs);
		
		//Localise
		$tabs = str_replace("%GENERAL_SETTINGS_LOCALE%", $this->localize("General Settings"), $tabs);
		$tabs = str_replace("%SEO_SETTINGS_LOCALE%", $this->localize("SEO Settings"), $tabs);
		$tabs = str_replace("%TEMPLATE_SETTINGS_LOCALE%", $this->localize("Template Settings"), $tabs);
		$tabs = str_replace("%CLEAR_CACHE_LOCALE%", $this->localize("Clear Cache"), $tabs);
			
		$getMore = $this->openFile("core/fragments/settings/gettemplate.phtml");
		
		$getMore = str_replace("%INSTALL_MORE_TEMPLATES_LOCALE%", $this->localize("Install More Templates"), $getMore);
		$getMore = str_replace("%INSTALLED_TEMPLATES_LOCALE%", $this->localize("Installed Templates"), $getMore);
		
		//Print this dashboard
		$this->setContent($tabs.$getMore.$content);	
	}
	
	/**
	 * Lets user know that template was successfully installed
	 */
	public function showMessage($data){
		//Show success message on redirected to page
		$_SESSION['ERROR_TYPE'] = "success";
		$_SESSION['ERROR_MESSAGE'] = $this->localize("Template was successfully installed. It will take a minute to appear.");
		
		//Go Redirect
		$this->setRedirect("index.php?system=GeneralSettings&page=edit");
	}
	
	/**
	 * Redirects after successfully saving the data
	 */
	public function setTemplateRedirect(){
		
		//Show success message on redirected to page
		$_SESSION['ERROR_TYPE'] = "success";
		$_SESSION['ERROR_MESSAGE'] = $this->localize("Template change successfully saved.");
		
		//Go Redirect
		$this->setRedirect("index.php?system=GeneralSettings&page=edit");	
	}
	
	/**
	 * Create a form for the page.
	 */
	protected function singleTable($active, $data){
            
		// Set the state and tell plugins.
		$this->setState('CREATE_SINGLE_TABLE');
		$this->notifyObservers();
		
		//Get the form
		//$out = $this->openFile("core/fragments/settings/SEOSettings.phtml");
		
		//Create new Table item
		$t = new Table();
		
		//Create new Table
		$t->createTable("Template Set");
		
		//Sets intial row as headings
		$t->setHead(true);
		
		//Add the heading row.
		$t->addRow(
				array(
					$this->localize("Template"),
					//"More Information",
					//"Preview Option",
                                        $this->localize("Activation State"),
					//"Delete Option"
				)					
			  );
		
		//Loop Through each page
		for($i = 0; $i < count($data); $i++)
		{
                    if($active==$data[$i]['title'])
                    {
			//Add Page data for each row.
			$t->addRow(
					array(
						$data[$i]['title'],
						//"<a href='?system=Editor&page=editor&active=".$data[$i]['unix']."'>More Info</a>",
						//"<a href='?page=".$data[$i]['unix']."'>View</a>",
                                                "<input type='radio' name='template' value=".$data[$i]['unix']." CHECKED/> ".$this->localize("Active"),
						//"<a href='?system=Editor&page=delete&active=".$data[$i]['unix']."'>Delete</a>",					
					)
				  );
                    }
                    else
                    {
			//Add Page data for each row.
			$t->addRow(
					array(
						$data[$i]['title'],
						//"<a href='?system=Editor&page=editor&active=".$data[$i]."'>More Info</a>",
						//"<a href='?page=".$data[$i]."'>View</a>",
                                                "<input type='radio' name='template' value=".$data[$i]['unix']." /> ".$this->localize("Inactive"),
						//"<a href='?system=Editor&page=delete&active=".$data[$i]."'>Delete</a>",					
					)
				  );
                    }
		}
		
		//Creates a table from inserted data
		$t->runTable();
                
		//Return the out data
		return $t->getTable();
	}

	/**
	 * A function for the settings file that sets which tab is active
	 */ 
	protected function setTabActive($active = 1, $tabs){
		
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
		
		if($active==4){
			$tabs = str_replace("%FOUR%", "active", $tabs);
		}else{
			$tabs = str_replace("%FOUR%", "inactive", $tabs);
		}
		
		return $tabs;
	}
}

?>