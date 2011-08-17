<?php
/**
 *
 * GPL v4 
 * LotusCMS 2010.
 *
 * Written by Kevin Bluett
 *
 */
//Start the session.
session_start();

//DEV
error_reporting(E_ALL);

//Failsafe to install
if(file_exists("install.php")){
	//header("Location: install.php");	
}

//Load up the routing system
require("core/lib/router.php");

//Route the page request to the specified system, eg. Page retrieval, administration or essentially anything.
new Router();

?>