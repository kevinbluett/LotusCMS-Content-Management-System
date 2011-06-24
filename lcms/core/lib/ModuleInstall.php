<?php
include("core/lib/io.php");
/**
 * Default Methods for module
 */
class Install extends InputOutput{
	
	//Variables for the system
	protected $unix;	

	/**
	 * Returns the module Title
	 */
	public function createStarter(){
		
		//Creates an external starting point for a module
		$this->saveFile("data/modules/".$this->getUnix()."/starter.dat", " ");
	}
	
	/**
	 * Creates the running data directory inside the 
	 */
	public function createModuleActive(){
		mkdir('data/modules/'.$this->getUnix(), 0777, true);
		@chmod('data', 0777);
		@chmod('data/modules', 0777);
		@chmod('data/modules/'.$this->getUnix(), 0777);
		
		$this->saveFile("data/modules/".$this->getUnix()."/index.php", " ");
	}
	
	/**
	 * Returns this unix name for the module
	 */
	public function getUnix(){
		return $this->unix;
	}
	
	/**
	 * Sets the unix name of the plugin to be used by various functions in module.
	 */ 
	public function setUnix($unix){
		$this->unix = $unix;
	}
	
	/**
	 * Checks for observable option in system
	 */
	public function checkPlug($system, $unix){
		
		if(file_exists("data/config/modules/".$system.".dat")){
			$data = $this->openFile("data/config/modules/".$system.".dat");
			
			$data = explode("|", $data);
			
			for($i = 0;$i < count($data); $i++){					
				
				if($data[$i]==$unix){
					return true;
				}	
			}
		}
		return false;
	}
	
	/**
	 * Checks for observable option in system
	 */
	public function addPlug($system, $unix){
		
		$on = $this->checkPlug($system, $unix);
		
		//If the check plug isn't on
		if(!$on){
			if(file_exists("data/config/modules/".$system.".dat")){
				$data = $this->openFile("data/config/modules/".$system.".dat");
				
				if(!empty($data)){
					//Add name to total list
					$data = $data."|".$unix;
				}else{
					$data = $unix;	
				}
				
				//Save file
				$this->saveFile("data/config/modules/".$system.".dat", $data);
			}else{
				//Directly create file
				$this->saveFile("data/config/modules/".$system.".dat", $unix);
			}
		}
	}
	
	/**
	 * Removes Observing plug from system
	 */
	public function removePlug($system, $unix){
		
		$on = $this->checkPlug($system, $unix);
		
		//If the check plug is on.
		if($on){
			//Existing Plugs
			$data = $this->openFile("data/config/modules/".$system.".dat");
			
			//New String
			$out = "";
				
			for($i = 0; $i < count($data); $i++){
				
				//Avoids empty strings.
				if($i==0){
					if($data[$i]!=$unix){
						$out .= $data[$i];
					}
				}else{
					if($data[$i]!=$unix){
						$out .= "|".$data[$i];
					}
				}
			}
				
			//Save file
			$this->saveFile("data/config/modules/".$system.".dat", $out);
		}
	}	
}
?>