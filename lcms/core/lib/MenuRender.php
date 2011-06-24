<?php
include_once("core/lib/io.php");

class MenuRender {
	
	public function compileMenu($request = null, $d = null){
      
      //Allow compiling menu from inputted menu
      if(empty($d)){
      	$d = $this->getMenuItems();
      }
      
      
      
      $out = "<ul>";
      
      for($i = 0; $i < count($d); $i++){
      	

         
         //Collects link
         $link = $d[$i][1];
               
         //Avoids occasional parse error
         $link = str_replace("|*int", "", $link);
         
      	if(empty($request)){
         	$out .= "<li>";
      	}else{
      		if($request==$link){
      			$out .= "<li class='active'>";
      		}else{
      			$out .= "<li>";
      		}
      	}
         
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
   
      return $out;
   }	
   
	/**
	 * List all pages in a directory.
	 */
	public function getMenuItems(){
		
		//Load File Abstraction Layer
		$io = new InputOutput();
		
		//Lists the pages in a directory
		$pages = $io->openFile("data/modules/Menu/items/menu_items.dat");
		
		$pages = explode("|*outer*|", $pages);

		for($i = 0; $i < count($pages); $i++)
		{
			//Convert int array
			$pages[$i] = explode("|*inter*|", $pages[$i]);	
		}
		
		return $pages;	
	}
}
?>