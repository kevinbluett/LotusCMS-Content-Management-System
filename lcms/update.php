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

if($_SESSION['login']){
	include("core/lib/UpgradeCore.php");
	
	new UpgradeCore();
}else{
	print "permission denied.";	
}
?>