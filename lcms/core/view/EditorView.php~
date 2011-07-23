<?php

include("core/view/view.php");
include("core/lib/table.php");

class EditorView extends View{
	
	
	/**
	 * Starts the controller of the classes system
	 */
	public function EditorView(){
		//Meta is usually not setup yet, so we manually do this before loading css file
		$this->meta = new Meta();
		
		//This class requires an extra css file
		$this->getMeta()->addExtra('<link href="core/fragments/css/admin.css" rel="stylesheet" type="text/css" />');	
	}	
	
	/**
	 * Show the Dashboard
	 */
	public function createEditor($data, $unix){
		
		// Set the state and tell plugins.
		$this->setState('CREATING_EDITOR');
		$this->notifyObservers();
		
		//Get Top of the Editor
		$content = $this->openFile("core/fragments/admin_editor_top.phtml");
		
		//Change the content into a form
		$content .= $this->singleForm($data[0], $data[1], $data[2], $unix);
		
		//Get the bottom of the editor
		$content .= $this->openFile("core/fragments/admin_editor_bottom.phtml");
		
		//Print this dashboard
		$this->setContent($content);	
		$this->setContentTitle($this->localize("Editor"));
	}
	
	/**
	 * Create a form for the page.
	 */
	protected function singleForm($title, $template, $content, $unix){
		
		// Set the state and tell plugins.
		$this->setState('CREATING_PAGE_FORM');
		$this->notifyObservers();
		
		//Get the form
		$out = $this->openFile("core/fragments/editor/editPageForm.phtml");
		
		//Replace Title in File
		$out = str_replace("%TITLE%", $title, $out);
		
		//Locale
		$out = str_replace("%YOU_ARE_EDITING_LOCALE%", $this->localize("You are editing"), $out);
		$out = str_replace("%TITLE_LOCALE%", $this->localize("Title"), $out);
		$out = str_replace("%TEMPLATE_LOCALE%", $this->localize("Template"), $out);
		$out = str_replace("%CONTENT_LOCALE%", $this->localize("Content"), $out);
		$out = str_replace("%SAVE_LOCALE%", $this->localize("Save"), $out);
		
		//Replace Unix in File
		$out = str_replace("%UNIX%", $unix, $out);
		
		//Replace current template options
		$out = str_replace("%CURRENT_TEMPLATE%", "<option>".$template."</option>", $out);
		
		//Template
		$temp = $this->getController()->getModel()->getTemplateOptions($template);
		
		//Replace template options
		$out = str_replace("%TEMPLATE_OPTIONS%", $temp, $out);

		//Replace Content in File
		$out = str_replace("%CONTENT%", $content, $out);
		
		//Return the out data
		return $out;
	}
	
	/**
	 * Displays a success / error message depending on how the save went
	 */
	public function displayAdmin($title, $content)
	{
		//Start Divider for the time being.
		$out = "<div style='font-family: arial, helvetica, sans serif;'>";
		
		//Add the title
		$out .= "<h3>".$title."</h3>";
		
		//Add the content
		$out .= "<div>".$content."</div>";
		
		//Add End Divider
		$out .= "</div>";
		
		//Set the content to be displayed
		$this->setContent($out);
	}
	
	/**
	 * Displays a form for users to set the unixname of a page.
	 */
	public function showCreateForm(){
		
		// Set the state and tell plugins.
		$this->setState('CREATING_NEWPAGE_EDITOR');
		$this->notifyObservers();
		
		//Create the form
		$out = $this->getController()->getModel()->openFile("core/fragments/editor/newPage.phtml");
		
		$out = str_replace("%UNIX_NAME_TEXT_GENERATED_LOCALE%", $this->localize("A unix name for this page will be generated from the title."), $out);
		$out = str_replace("%YOU_EDITING_UNTITLED_LOCALE%", $this->localize("You are creating a new page (Untitled)"), $out);
		$out = str_replace("%TITLE_LOCALE%", $this->localize("Title"), $out);
		$out = str_replace("%DEFINE_UNIX_NAME_MANUALLY_LOCALE%", $this->localize("Define Unix Name Manually"), $out);
		$out = str_replace("%UNIX_NAME_LOCALE%", $this->localize("Unix Name"), $out);
		$out = str_replace("%TEMPLATE_LOCALE%", $this->localize("Template"), $out);
		$out = str_replace("%CONTENT_LOCALE%", $this->localize("Content"), $out);
		$out = str_replace("%SAVE_LOCALE%", $this->localize("Save"), $out);
		
		$out = str_replace("%TEMPLATE_OPTIONS%", $this->getController()->getModel()->getTemplateOptions(), $out);
		
		$this->setContent($out);
		$this->setContentTitle($this->localize("Create New Page"));
	}
	
	/**
	 * Displays the delete active system
	 */
	public function showDelete($name){
		
		// Set the state and tell plugins.
		$this->setState('SHOW_DELETE_PAGE');
		$this->notifyObservers();
		
		//Create the form
		$out = $this->getController()->getModel()->openFile("core/fragments/editor/deleteCheck.phtml");
		
		//Set Locales
		$out = str_replace("%SURE_DELETE_LOCALE%", $this->localize("Are you sure you want to delete"), $out);
		$out = str_replace("%YES_LOCALE%", $this->localize("Yes"),$out);
		$out = str_replace("%NO_LOCALE%", $this->localize("No"),$out);
		
		//Loads the css required to have buttons yes and no.
		$this->getMeta()->appendExtra('<link href="core/fragments/css/admin.css" rel="stylesheet" type="text/css">');
		
		//Set the page name inside the template
		$out = str_replace("%PAGE_NAME%", $name, $out);
		
		//Set output content
		$this->setContent($out);
		$this->setContentTitle($this->localize("Editor"));
	}
	
	/**
	 * Redirects to a page with a success message
	 */
	public function redirectSuccess($message){
		
		// Set the state and tell plugins.
		$this->setState('SUCCESS_REDIRECT');
		$this->notifyObservers();
		
		//Show success message on redirected to page
		$_SESSION['ERROR_TYPE'] = "success";
		$_SESSION['ERROR_MESSAGE'] = $message;
		
		//Go Redirect
		$this->setRedirect("index.php?system=PageList&page=list");	
	}
	
	/**
	 * Redirects to a page with an error message
	 */
	public function redirectError($message){
		
		// Set the state and tell plugins.
		$this->setState('ERROR_REDIRECT');
		$this->notifyObservers();
		
		//Show error message on redirected to page
		$_SESSION['ERROR_TYPE'] = "error";
		$_SESSION['ERROR_MESSAGE'] = $message;
		
		//Go Redirect
		$this->setRedirect("index.php?system=PageList&page=list");
	}
}

?>