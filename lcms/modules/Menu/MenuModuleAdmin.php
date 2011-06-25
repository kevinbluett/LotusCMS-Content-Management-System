<?php

/**
 * The Administration loader for the module
 */
class ModuleAdmin extends Admin{

	/**
	 * The Default setup function
	 */
	public function ModuleAdmin(){
		//Sets the unix name of the plugin
		$this->setUnix("Menu");	
	}

	/**
	 * Default Requestion
	 */
	public function defaultRequest(){
		
		$data = $this->itemsToTable();
		
		$this->setContent($data);
	}
	
	/**
	 * Adding a new menu item
	 */
	public function addRequest(){
		
		$pages = $this->getPages();
		
		//Gets a fragment and sets it as content
		$this->setContent(
							str_replace("%INTERNAL_PAGES%",$pages, $this->getFragment("newItem"))
						 );
	}
	
	/**
	 * Saves a new or old link
	 */
	public function saveRequest(){
		
		$id = $this->getInputString("id", "-1", "G");
		
		$title = $this->getInputString("title", "", "P");
		
		//Gets the state of how to open the link
		$state = $this->getInputString("state", "", "P");
		
		if(empty($title)){
			$this->redirectError("Title was left empty.");
		}
		
		if($id=="-1")//This equates to a new link
		{
			$ex = $this->getInputString("external", "", "P");
			
			if(!empty($ex)){
				
				$d = $this->getMenuItems();	
				
				$d[] = array(
							   		$title,
							   		$ex,
							   		"ex"
							   );
				
				//Saves back into it's mangled form.
				$this->saveToMenu($d);
				
				//Make Menu available to templates
				$this->compileMenu($d);
				
				$this->redirectSuccess("Successfully changed menu.");
				
			}else{
				$in = $this->getInputString("group1", "", "P");
				
				if(empty($in)){
					$this->redirectError("No internal or external link entered.");
				}else{
					
					$d = $this->getMenuItems();	
				
					$d[] = array(
								   		$title,
								   		$in,
								   		"in"
								   );
					
					//Saves back into it's mangled form.
					$this->saveToMenu($d);
					
					//Make Menu available to templates
					$this->compileMenu($d);
					
					$this->redirectSuccess("Successfully changed menu.");
				}
			}
		}
		else//otherwise we are dealing with an existing link
		{
			$ex = $this->getInputString("external", "", "P");
			
			$d = $this->getMenuItems();	
			$num = count($d);
			
			if(!empty($ex)){
				
				$d[$id] = array(
							   		$title,
							   		$ex,
							   		"ex"
							   );
				
				//Saves back into it's mangled form.
				$this->saveToMenu($d);
				
				//Make Menu available to templates
				$this->compileMenu();
				
				$this->redirectSuccess("Successfully changed menu.");
				
			}else{
				$in = $this->getInputString("group1", "", "P");
				
				if(empty($in)){
					$this->redirectError("No internal or external link entered.");
				}else{
				
					$d[$id] = array(
								   		$title,
								   		$in,
								   		"in"
								   );
					
					//Saves back into it's mangled form.
					$this->saveToMenu($d);
					
					//Make Menu available to templates
					$this->compileMenu();
					
					$this->redirectSuccess("Successfully changed menu.");
				}
			}

		}
		
	}
	
	/**
	 * Adding a new menu item
	 */
	public function editRequest(){
		
		//Gets the Id
		$id = $this->getInputString("id", -1, "G");
		
		$d = $this->getMenuItems();
		
		$pages = ""; 
		
		$out =  $this->getFragment("editItem");
		
		if($d[$id][2]=="in"){
			$link = str_replace("index.php?page=", "", $d[$id][1]);

			$pages = $this->getPages($link);
			
			$out = str_replace("%EXTERNAL_LINK%", "", $out);
		}else{
			$pages = $this->getPages();	
			
			$out = str_replace("%EXTERNAL_LINK%", $d[$id][1], $out);
		}
		
		$out = str_replace("%INTERNAL_PAGES%",$pages, $out);
		
		$out = str_replace("%MENU_ID%", $id, $out);
		
		$out = str_replace("%TITLE%", $d[$id][0], $out);
				
		//Gets a fragment and sets it as content
		$this->setContent(
							$out
						 );
	}
	
	public function upRequest(){
		
		//Gets the Id
		$id = $this->getInputString("id", -1, "G");
		
		if($id==0||$id==-1){
			//Already at top and this should never occur except in a hacking attempt.
			exit;	
		}else{
			$d = $this->getMenuItems();	
			
			$y = ($id-1);
			
			$cache = $d[$y];
			
			$d[$y] = $d[$id];
			
			$d[$id] = $cache;
			
			//Saves back into it's mangled form.
			$this->saveToMenu($d);
			
			//Make Menu available to templates
			$this->compileMenu();
			
			$this->redirectSuccess("Successfully changed menu order.");
		}
	}
	
	public function downRequest(){
		
		//Gets the Id
		$id = $this->getInputString("id", -1, "G");
		
		$d = $this->getMenuItems();	
		
		if($id==count($d)||$id==-1){
			//Already at top and this should never occur except in a hacking attempt.
			exit;	
		}else{
			
			$y = ($id+1);
			
			$cache = $d[$y];
			
			$d[$y] = $d[$id];
			
			$d[$id] = $cache;
			
			//Saves back into it's mangled form.
			$this->saveToMenu($d);
			
			//Make Menu available to templates
			$this->compileMenu();
			
			$this->redirectSuccess("Successfully changed menu order.");
		}
	}
	
	/**
	 * Delete Request
	 */
	public function deleteRequest(){
		//Gets the Id
		$id = $this->getInputString("id", -1, "G");
		
		$d = $this->getMenuItems();
		
		$out = $this->getFragment("deleteSure");
		
		$out = str_replace("%MENU_ITEM%", $d[$id][0], $out);
		$out = str_replace("%MENU_ID%", $id, $out);
		
		$this->setContent($out);
	}
	
	/** 
	 * Accepted the delete form request system
	 */
	public function deleteSureRequest(){
		
		//Gets the Id
		$id = $this->getInputString("id", -1, "G");
		
		if($id==-1){
			//Failsafe
			exit;	
		}
		
		//Get Menu items.
		$d = $this->getMenuItems();
		
		$d = $this->remove_item_by_value($d, $d[$id], false);
		
		//Saves back into it's mangled form.
		$this->saveToMenu($d);
			
		//Make Menu available to templates
		$this->compileMenu();
		
		$this->redirectSuccess("The item was successfully removed from the menu.");
	}

	/**
	 * Removes item from array
	 */
	function remove_item_by_value($array, $val = '', $preserve_keys = true) {
		if (empty($array) || !is_array($array)) return false;
		if (!in_array($val, $array)) return $array;
	
		foreach($array as $key => $value) {
			if ($value == $val) unset($array[$key]);
		}
	
		return ($preserve_keys === true) ? $array : array_values($array);
	}
	
	/**
	 * Saves back into menu format
	 */
	public function saveToMenu($d){

		for($i = 0; $i < count($d); $i++)
		{
			if(!empty($d[$i])){
				//Convert int array
				$d[$i] = implode("|*inter*|", $d[$i]);	
			}
		}
		
		$e = implode("|*outer*|", $d);
		
		$this->saveFile("data/modules/Menu/items/menu_items.dat", $e);	
		
	}
	
	/**
	 * Converts items into a table.
	 */
	public function itemsToTable(){

		
		$d = $this->getMenuItems();
		
		$out = $this->getFragment("topMainPage");
		
		for($i = 0; $i < count($d); $i++){
			$out .= "<tr>";
			
			$out .= "<td>";
			
			if($i==0){
				$out .= "<a style='text-decoration: none;font-size: 14px;float: left;width: 7px;' href='#'>&nbsp;</a>&nbsp;<a href='index.php?system=Modules&page=admin&active=Menu&req=down&id=".$i."' style='text-decoration: none;font-size: 14px;'>&darr;</a>";
			}else if(($i+1)==count($d)){
				$out .= "<a style='text-decoration: none;font-size: 14px;' href='index.php?system=Modules&page=admin&active=Menu&req=up&id=".$i."'>&uarr;</a>&nbsp;<a href='#' style='text-decoration: none;font-size: 14px;'>&nbsp;</a>";
			}else{
			//Move
				$out .= "<a style='text-decoration: none;font-size: 14px;' href='index.php?system=Modules&page=admin&active=Menu&req=up&id=".$i."'>&uarr;</a>&nbsp;<a href='index.php?system=Modules&page=admin&active=Menu&req=down&id=".$i."' style='text-decoration: none;font-size: 14px;'>&darr;</a>";
			}
			$out .= "</td>";
			
			$out .= "<td>";
			//Name
			$out .= $d[$i][0];
			
			$out .= "</td>";
		
			$out .= "<td>";
			//Edit
			$out .= "<a href='index.php?system=Modules&page=admin&active=Menu&req=edit&id=".$i."'>Edit</a>";
			
			$out .= "</td>";
			
			$out .= "<td>";
			//Delete
			//Edit
			$out .= "<a href='index.php?system=Modules&page=admin&active=Menu&req=delete&id=".$i."'>Delete</a>";
			$out .= "</td>";
			
			$out .= "</tr>";	
		}
		
		$out .= "</table>";
	
		return $out;
	}
	

	
	public function compileMenu($d = null){
      
      //Allow compiling menu from inputted menu
      if(empty($d)){
      	$d = $this->getMenuItems();
      }
      
      $out = "<ul>";
      
      for($i = 0; $i < count($d); $i++){
         $out .= "<li>";
         
         //Collects link
         $link = $d[$i][1];
               
         //Avoids occasional parse error
         $link = str_replace("|*int", "", $link);
         
         if($d[$i][2]=="in"){
            $d[$i][1] = "index.php?page=".$link;   
         }
            
         if($i==0){
            $out .= "<a class='firstM' href='".$d[$i][1]."'>".$d[$i][0]."</a>";
         }else if(($i+1)==count($d)){
            $out .= "<a class='lastM' href='".$d[$i][1]."'>".$d[$i][0]."</a>";
         }else{
            $out .= "<a class='normalM' href='".$d[$i][1]."'>".$d[$i][0]."</a>";
         }
         
         $out .= "</li>";   
      }
      
      $out .= "</ul>";
   
      $this->saveFile("data/modules/Menu/compiled.dat", $out);
      
      $this->clearCache();
   }
	
	/**
	 * List all pages in a directory.
	 */
	public function getMenuItems(){
		
		//Lists the pages in a directory
		$pages = $this->openFile("data/modules/Menu/items/menu_items.dat");
		
		$pages = explode("|*outer*|", $pages);

		for($i = 0; $i < count($pages); $i++)
		{
			//Convert int array
			$pages[$i] = explode("|*inter*|", $pages[$i]);	
		}
		
		return $pages;	
	}
	
	/**
	 * List all pages in a directory.
	 */
	public function getPages($selected = ""){
		
		//The directory containing the pages.
		$dir = "data/pages";
		
		//Lists the pages in a directory
		$pages = $this->listFiles($dir);
		
		$out = $this->getFragment("topPageTable");
		
		//Loops through all page listings to remove the extension of .dat
		for($i = 0; $i < count($pages); $i++)
		{
			$y = "";
			
			if($selected==str_replace(".dat", "", $pages[$i])){
				$y = "checked";
			}	
			
			
			//Removes the .dat from an item in the array
			$out .= "<tr><td>".str_replace(".dat", "", $pages[$i]).'</td><td><input type="radio" name="group1" value="'.str_replace(".dat", "", $pages[$i]).'" '.$y.'></td></tr>';	
		}
		
		$out .= "</table>";
		
		return $out;	
	}
	
	public function clearCache(){
		include("core/lib/FileOperations.php");
		
		$f = new FileOperations();
		
		$f->emptyDir("cache");
	}
}

?>