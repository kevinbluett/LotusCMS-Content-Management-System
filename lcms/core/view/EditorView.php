<?php

include("core/view/view.php");
include("core/lib/table.php");

class EditorView extends View{
	
	
	/**
	 * Starts the controller of the classes system
	 */
	public function EditorView(){
			
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
		
		$out = str_replace("%TEMPLATE_OPTIONS%", $this->getController()->getModel()->getTemplateOptions(), $out);
		
		$this->setContent($out);
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
		
		//Set the page name inside the template
		$out = str_replace("%PAGE_NAME%", $name, $out);
		
		//Set output content
		$this->setContent($out);
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