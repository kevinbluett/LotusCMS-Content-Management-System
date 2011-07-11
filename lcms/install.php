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

include_once("core/lib/io.php");

$io = new InputOutput();

$out = $io->openFile("style/comps/admin/install.html");

$id = 1;

if(isset($_GET['step'])){
	$id = $_GET['step'];	
}

if($id==1){

	//Put Step number in
	$out = str_replace("%STEP%", "1", $out);

	$perms = substr(base_convert(fileperms("data/config/site_title.dat"), 10, 8), 3);
	$perms1 = substr(base_convert(fileperms("cache/index.html"), 10, 8), 3);
	$perms2 = substr(base_convert(fileperms("modules/Dashboard/DashViewPlugin.php"), 10, 8), 3);
	$perms3 = substr(base_convert(fileperms("style/admin.php"), 10, 8), 3);
	$perms4 = substr(base_convert(fileperms("core/lib/io.php"), 10, 8), 3);
	
	$content = "<p><strong>Please continue to next step once all of the below boxes are green:</strong></p>";
	
	if($perms!="777"&&$perms!="666"){
		$content .= "<p class='message error'>'data' directory not recursively set permissions to 777.</p>";	
	}else{
		$content .= "<p class='message success'>'data' is set to correct permissions.</p>";
	}
	
	if($perms1!="777"&&$perms1!="666"){
		$content .= "<p class='message error'>'cache' directory not recursively set permissions to 777.</p>";	
	}else{
		$content .= "<p class='message success'>'cache' is set to correct permissions.</p>";	
	}
	
	if($perms2!="777"&&$perms2!="666"){
		$content .= "<p class='message error'>'modules' directory not recursively set permissions to 777.</p>";	
	}else{
		$content .= "<p class='message success'>'modules' is set to correct permissions.</p>";	
	}
	
	if($perms3!="777"&&$perms3!="666"){
		$content .= "<p class='message error'>'styles' directory not recursively set permissions to 777.</p>";	
	}else{
		$content .= "<p class='message success'>'styles' is set to correct permissions.</p>";	
	}
	
	if($perms4!="777"&&$perms4!="666"){
		$content .= "<p class='message error'>'core' directory not recursively set permissions to 777.</p>";	
	}else{
		$content .= "<p class='message success'>'core' is set to correct permissions.</p>";	
	}
	
	$content .= "<form action='install.php?step=2' method='post'><input style='height: 40px;width: 90px;float: right;' type='submit' value='Continue' /></form>";

	$out = str_replace("%MESSAGE%", $content, $out);
	
	if(isset($_SESSION['error'])){
		
		//Save error message
		$out = str_replace("%ERROR_MESSAGE%", $_SESSION['error_msg'], $out);
		
		//Unset errors
		unset($_SESSION['error']);
		unset($_SESSION['error_msg']);
		
	}else{
		$out = str_replace("%ERROR_MESSAGE%", "", $out);	
	}
	
}else if($id==2){
	//Put Step number in
	$out = str_replace("%STEP%", "2", $out);
	
	include_once("core/lib/Locale.php");
	$locale = new Locale();
	$listLocale = $locale->getListOfLocale();
	
	$localeOptions = "";
	
	$fID = 0;
	
	//Ensures the english one is first.
	for($i = 0; $i < count($listLocale[0]); $i++){
		if("en"==str_replace(".txt", "", $listLocale[0][$i])){
			$fID = $i;
			break;
		}
	}
		
	$localeOptions .= "<option>";
	$localeOptions .= $listLocale[1][$fID]." [".str_replace(".txt", "", $listLocale[0][$fID])."]";
	$localeOptions .= "</option>";
	
	//Create the list
	for($i = 0; $i < count($listLocale[0]); $i++){
		if($fID!=$i){
			$localeOptions .= "<option>";
			$localeOptions .= $listLocale[1][$i]." [".str_replace(".txt", "", $listLocale[0][$i])."]";
			$localeOptions .= "</option>";
		}
	}
	
	$content = "<form action='install.php?step=3' method='post'><strong style='font-size: 12px;'>New Username:</strong><br /><input style='height: 25px;width: 99%;float: left;' name='username' value='' /><br /><br /><br /><strong style='font-size: 12px;'>Email:</strong><br /><input style='height: 25px;width: 99%;float: left;' name='email' value='' /><br /><br /><br /><strong style='font-size: 12px;'>Full Name:</strong><br /><input style='height: 25px;width: 99%;float: left;'   name='fullname' value='' /><br /><br /><br /><br /><strong style='font-size: 12px;'>New Password:</strong><br /><input style='height: 25px;width: 99%;float: left;'  type='password' name='password' value='' /><br /><br /><strong style='font-size: 12px;'>Confirm Password:</strong><br /><input style='height: 25px;width: 99%;float: left;'  type='password' name='password1' value='' /><br /><br /><br /><strong style='font-size: 12px;'>Language:</strong><br /><select name='locale'>".$localeOptions."</select><br /><br /><strong style='font-size: 12px;'>Website Title:</strong><br /><input style='height: 25px;width: 99%;float: left;' name='title' value='' /><br /><br /><br /><input style='height: 40px;width: 90px;float: right;' type='submit' value='Save' /></form>";
	
	$out = str_replace("%MESSAGE%", $content, $out);
	
	if(isset($_GET['error'])){
		
		if($_GET['error']==1){
			//Save error message
			$out = str_replace("%ERROR_MESSAGE%", "<p class='messae error'>One or more fields was left blank.</p>", $out);
		}else if($_GET['error']==2){
			//Save error message
			$out = str_replace("%ERROR_MESSAGE%", "<p class='messae error'>Passwords did not match.</p>", $out);
		} 
		
		//Unset errors
		unset($_SESSION['error']);
		unset($_SESSION['error_msg']);
		
	}else{
		$out = str_replace("%ERROR_MESSAGE%", "", $out);	
	}

}else if($id==3){
	
	$username = $_POST['username'];
	$password = $_POST['password'];
	$password1 = $_POST['password1'];
	$title = $_POST['title'];
	$name = $_POST['fullname'];
	$email = $_POST['email'];
	
	//Get Locale text
	$loc = explode("[", $_POST['locale']);
	$locale = str_replace("]", "", $loc[1]);
	
	if((!empty($username))&&(!empty($password))&&(!empty($password1))&&(!empty($title))&&(!empty($name))&&(!empty($email))){
		
		//Passwords don't match
		if($password!=$password1){
			redirect(2, true, 2);
		}
		
		//Creates a long random string as salt.
		$salt = generateRandStr(30);	
		
		//Saves site title
		$io->saveFile("data/config/site_title.dat", $title);
		$io->saveFile("data/config/salt.dat", $salt);
		
		//Save the site Locale
		$io->saveFile("data/config/locale.dat", $locale);
		
		//Include user libraries
		include("core/lib/User.php");
		
		//Create new user
		$u = new User();
		
		//Removing administrator user.
		@unlink("data/users/admin.dat");
		
		//Creates the new user
		$u->saveUser($username, $fullname, $email, $password, "administrator");
		
		//Install essentially complete
		@unlink("cache/index.html");
		
		$out = str_replace("%MESSAGE%", "<p><strong>Complete!</strong><br />The CMS will now try to delete the installation file due to security reasons.</p><form action='install.php?step=4' method='post'><input style='height: 40px;width: 150px;float: right;' type='submit' value='Delete Install Files' /></form>", $out);
	}else{
		redirect(2, true, 1);	
	}
	
	//Put Step number in
	$out = str_replace("%STEP%", "3", $out);

	if(isset($_GET['error'])){
		
		//Save error message
		$out = str_replace("%ERROR_MESSAGE%", "Delete failed. Please remove it via FTP.", $out);
		
	}else{
		$out = str_replace("%ERROR_MESSAGE%", "", $out);	
	}
}else if($id==4){
	
	//Try to delete
	unlink("install.php") or noSuccess();
	
	header("Location: index.php?system=Admin");
}

print $out;

function noSuccess(){
	redirect(3, true, 1);	
}

/**
 * Allows redirect
 */
function redirect($step, $errors = false, $error_msg = ""){
	
	$error = "";
	
	if($errors){
		$_SESSION['error'] = true;
		$error = "&error=".$error_msg;
	}
	
	//Redirect
	header("Location: install.php?step=".$step.$error);
}

function generateRandStr($length){
      $randstr = "";
      for($i=0; $i<$length; $i++){
         $randnum = mt_rand(0,61);
         if($randnum < 10){
            $randstr .= chr($randnum+48);
         }else if($randnum < 36){
            $randstr .= chr($randnum+55);
         }else{
            $randstr .= chr($randnum+61);
         }
      }
      return $randstr;
} 

?>