<?php

/**
 * This Form System is in development not working yet.
 */
class Form{
	
	protected $cols;
	
	protected $name;
	
	protected $up;
	
	protected $finished;
	
	protected $rows;
	
	protected $head;
	
	/**
	 *
	 */
	public function Form(){
		$this->rows = null;
		$this->head = "Form";
	}
	
	/**
	 *
	 */
	public function createTable($name){
		
		//Set the name of the table
		$this->name = $name;
		
		//Set the true
		$this->up = true;
	}	
	
	public function setHead($heading){
		$this->head = $heading;	
	}
	
	/**
	 *
	 */
	public function addRow($data){
		$this->rows[] = $data;
	}
	
	public function runTable(){
		$final = "<table width='100%'>";
		
		for($i = 0; $i < count($this->rows); $i++)
		{
			$final .= "<tr>";
				
				for($y = 0;$y < count($this->rows[$i]);$y++)
				{
					if($i==0&&$this->head){
						$final .= "<th>";
						
							$final .= $this->rows[$i][$y];
							
						$final .= "</th>";
					}else{
						$final .= "<td>";
						
							$final .= $this->rows[$i][$y];
							
						$final .= "</td>";
					}
				}
				
			$final .= "</tr>";
		}
		
		$final .= "</table>";
		
		//Set the final variable
		$this->finished = $final;
	}
	
	public function getTable(){
		return $this->finished;	
	}
}

?>