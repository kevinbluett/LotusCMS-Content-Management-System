<?php
include("core/lib/table.php");

class EditorView extends View{

	public function EditorView(){
		//Meta is usually not setup yet, so we manually do this before loading css file
		$this->meta = new Meta();
		
		//This class requires an extra css file
		$this->getMeta()->addExtra('<link href="core/fragments/css/admin.css" rel="stylesheet" type="text/css" />');	
	}	
	
	public function createEditor($data, $unix){
		$this->setState('CREATING_EDITOR');
		$content = $this->openFile("core/fragments/admin_editor_top.phtml");
		$content .= $this->singleForm($data[0], $data[1], $data[2], $data[3], $unix);
		$content .= $this->openFile("core/fragments/admin_editor_bottom.phtml");
		
		$this->setContent($content);	
		$this->setContentTitle($this->localize("Editor"));
	}
	
	/**
	 * Create a form for the page.
	 */
	protected function singleForm($title, $template, $visibility, $content, $unix){
		$this->setState('CREATING_PAGE_FORM');

		$out = $this->openFile("core/fragments/editor/editPageForm.phtml");
		
		$out = str_replace("%TITLE%", $title, $out);
	
		$out = str_replace("%YOU_ARE_EDITING_LOCALE%", $this->localize("You are editing"), $out);
		$out = str_replace("%TITLE_LOCALE%", $this->localize("Title"), $out);
		$out = str_replace("%TEMPLATE_LOCALE%", $this->localize("Template"), $out);
		$out = str_replace("%CONTENT_LOCALE%", $this->localize("Content"), $out);
		$out = str_replace("%SAVE_LOCALE%", $this->localize("Save"), $out);
		$out = str_replace("%PAGE_VISIBILITY_LOCALE%", $this->localize("Page Visibility"), $out);
		$out = str_replace("%PUBLISHED_LOCALE%", $this->localize("Published"), $out);
		$out = str_replace("%UNPUBLISHED_LOCALE%", $this->localize("Unpublished"), $out);

		if($visibility=="true"){
			$out = str_replace("%CHECKED_PUB%", "CHECKED", $out);
			$out = str_replace("%CHECKED_UNPUB%", "", $out);
		}else{
			$out = str_replace("%CHECKED_PUB%", "", $out);
			$out = str_replace("%CHECKED_UNPUB%", "CHECKED", $out);
		}

		$out = str_replace("%UNIX%", $unix, $out);
		
		$out = str_replace("%CURRENT_TEMPLATE%", "<option>".$template."</option>", $out);

		$temp = $this->getController()->getModel()->getTemplateOptions($template);

		$out = str_replace("%TEMPLATE_OPTIONS%", $temp, $out);
		$out = str_replace("%CONTENT%", $content, $out);
		$out = str_replace("%LOCALE%", $this->getLocale(), $out);
		
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
		$this->setState('CREATING_NEWPAGE_EDITOR');
		
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
		$out = str_replace("%PAGE_VISIBILITY_LOCALE%", $this->localize("Page Visibility"), $out);
		$out = str_replace("%PUBLISHED_LOCALE%", $this->localize("Published"), $out);
		$out = str_replace("%UNPUBLISHED_LOCALE%", $this->localize("Unpublished"), $out);
		$out = str_replace("%LOCALE%", $this->getLocale(), $out);
		
		$out = str_replace("%TEMPLATE_OPTIONS%", $this->getController()->getModel()->getTemplateOptions(), $out);
		$out = str_replace("%CHECKED_PUB%", "CHECKED", $out);
		$out = str_replace("%CHECKED_UNPUB%", "", $out);

		$this->setContent($out);
		$this->setContentTitle($this->localize("Create New Page"));
	}
	
	/**
	 * Displays the delete active system
	 */
	public function showDelete($name){
		$this->setState('SHOW_DELETE_PAGE');
		
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
		$this->setState('SUCCESS_REDIRECT');
		
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
		$this->setState('ERROR_REDIRECT');
		
		//Show error message on redirected to page
		$_SESSION['ERROR_TYPE'] = "error";
		$_SESSION['ERROR_MESSAGE'] = $message;
		
		//Go Redirect
		$this->setRedirect("index.php?system=PageList&page=list");
	}
}

?>