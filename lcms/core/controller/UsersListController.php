<?php
class UsersListController extends Controller{

	public function UsersListController($page){
		$this->setup("UsersList", "Users List");
	}
	
	protected function putRequests(){
		$requests = array("list");
		$this->setRequests($requests);
	}
	
	protected function listRequest(){
		$this->setState('USERLIST_REQUEST');
		
		//Get the Page list data
		$data = $this->getModel()->getUsers();
		
		//Print the Page List
		$this->getView()->showUsersList($data);
	}
}

?>