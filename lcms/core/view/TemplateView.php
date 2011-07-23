<?php
include("core/lib/table.php");

class TemplateView extends View{
	
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
		
		$this->setState('CREATE_TABLE');
                
                $content = '<form method="post" class="jNice" action="?system=Template&page=change" name="edit">' ;
                
		//Change the content into a form
		$content .= $this->singleTable($active, $data);
                
                $content .= '<input type="submit" value="'.$this->localize("Save").'" /></form>';
                
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
		$this->setState('CREATE_SINGLE_TABLE');
		
		//Create new Table item
		$t = new Table();
		
		//Create new Table
		$t->createTable("Template Set");
		
		//Sets intial row as headings
		$t->setHead(true);
		
		//Get update array
		$u = $this->getController()->getModel()->getUpdateArray();
		
		//Add the heading row.
		$t->addRow(
				array(
					$this->localize("Template"),
					$this->localize("Preview Option"),
                                        $this->localize("Activation State"),
                                        $this->localize("Update"),
					$this->localize("Delete Option")
				)					
			  );
		
		//Loop Through each page
		for($i = 0; $i < count($data); $i++)
		{
		    
		    $update = "<a href='?system=Template&page=check&active=".$data[$i]['unix']."'>".$this->localize("Check")."</a>";
		    
		    if($u[$data[$i]['unix']]){
		    	    $update = "<a style='color:red;' href='?system=Template&page=check&active=".$data[$i]['unix']."'>".$this->localize("Updates Available")."</a>";
		    }else{
		    	$update = "<a style='color:green;' href='?system=Template&page=check&active=".$data[$i]['unix']."'>".$this->localize("Up to Date")."</a>";
		    }
			
                    if($active==$data[$i]['title'])
                    {
			$view = "<a href='?system=Template&page=preview&template=".$data[$i]['unix']."'>".$this->localize("View")."</a>";
			$localize = "<input type='radio' name='template' value=".$data[$i]['unix']." CHECKED/> ".$this->localize("Active");
			$delete = $this->localize("Active");
			
			if(file_exists("style/comps/".$data[$i]['unix']."/noDisableStatus.dat")){
				$view = "";
				$localize = "";
				$delete = "";
			}
                    	    
                    	    
			//Add Page data for each row.
			$t->addRow(
					array(
						$data[$i]['title'],
						$view,
                                                $localize,
                                                $update,
						$delete,					
					)
				  );
                    }
                    else
                    {
			
			$view = "<a href='?system=Template&page=preview&template=".$data[$i]['unix']."'>".$this->localize("View")."</a>";
			$localize = "<input type='radio' name='template' value=".$data[$i]['unix']." /> ".$this->localize("Inactive");
			$delete = "<a href='?system=Template&page=delete&active=".$data[$i]['unix']."'>".$this->localize("Delete")."</a>";
			
			if(file_exists("style/comps/".$data[$i]['unix']."/noDisableStatus.dat")){
				$view = "";
				$localize = "";
				$delete = "";
			}
                    	    
                    	    
			//Add Page data for each row.
			$t->addRow(
					array(
						$data[$i]['title'],
						$view,
                                                $localize,
                                                $update,
                                                $delete,					
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
	 * Shows a page to ask the user if they are sure if they really wish to delete the page.
	 */
	public function showSurePage($temp){}
	
	/**
	 * Previews a template
	 */
	public function previewRequest($temp){
		$this->setTemplate($temp);
		$this->setContentTitle("Previewing Template");
		$this->setContent("<a href='index.php?system=Template&page=change'>Return to template list</a>");
	}
}

?>