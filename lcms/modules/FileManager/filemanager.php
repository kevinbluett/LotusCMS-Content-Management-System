<?php
if(!$_SESSION['login']) 
	exit;
if(!$_SESSION['access_lvl']=="administrator")
	print "Requires Administrator Level Access";
error_reporting(0);//Change to 0 for none.
//The Sandbox
//By Joey C. - http://joeyjwc.x3fusion.com/
//Version .95
//This software is in beta testing; please be careful.

//This program is free for all to use and modify as they see fit.  If you plan to redistribute it, please give me credit.  Thank you.
//This software comes with no warranty of anything whatsoever.


//					VARIABLES
//NAME								//DESCRIPTION
$fd = "data/files/";							//The directory for storing files.
$bd = "data/filebackups/";							//The directory for storing backups.
$ieHeight = 600;					//The height (without "px" at the end) for the IE hack (because Internet Explorer doesn't seem to allow dynamic resizing).
$autosaveInterval = 30000;			//The interval in milliseconds for automatically saving the document to the backup directory.
$maxFileSize = 10000000;			//The maximum size (in bytes) that an uploaded file can be.
$newPerm = "0755";					//The permissions to enter by default for a new file.
$uplPerm = "0644";					//The permissions to enter by default for an uploaded file.


//YOU DO NOT NEED TO EDIT ANYTHING BEYOND THIS POINT

$ud = $fd;							//Upload Directory.  This should always be the same as $fd.

function messageBox($msgtxt, $redir, $errbox=FALSE) {				//Message Box
	if ($errbox==TRUE) $boxtype = "error";
	else $boxtype = "success";
	echo("
<div class=\"$boxtype\">
<p style=\"padding: 10px;\">$msgtxt</p>
<p><a href=\"$redir\" class=\"actn\">Okay.</a></p>
</div>
");
}

function choiceBox($msgtxt, $c1txt, $c1link, $c2txt, $c2link) {		//Choice Box
	echo("
<div class=\"messagebox success\"><br/>
<p>$msgtxt</p><br /><br />
<p><a href=\"$c1link\" class=\"actn\">$c1txt</a> <a href=\"$c2link\" class=\"actn\">$c2txt</a></p>
</div>
");
}

function fFSize($file) {											//Formatted File Size
	$size = filesize($file);
	if ($size>=1000000) return round($size/1000000,2)."mB";
	if ($size>=1000) return round($size/1000,2)."kB";
	else return $size."B";
}

function getDir($dir) {												//Get contents of directory.  Now supports PHP 4 and 5.
if (version_compare(PHP_VERSION, "5.0.0", ">=")) return scandir($dir);
else {
	$dh  = opendir($dir);
	while (false !== ($filename = readdir($dh))) {
		$files[] = $filename;
	}
	sort($files);
	return $files;
}
}

if (!$_REQUEST["a"]) {						//Index Page
	?>
<script type="text/javascript" src="fat.js"></script>
<script type="text/javascript">
<!--
function highlightBox(divID) {
	Fat.fade_element(divID, 60, 3300, '#FFFF55', '#DDFFAA')
	}

function confDelete(filename) {
	var primaryOK = confirm("Are you sure you want to delete "+filename+"?");
	if (primaryOK==true) { 
		var secondaryOK = confirm("Are you REALLY sure?");
		if (secondaryOK==true) { window.location="index.php?system=Modules&page=admin&active=FileManager&a=del&filename="+filename; }
		else { alert("Phew...  Saved it.  No action was taken."); }
	}
	else { alert("ABORT!  ABORT!  The file was not deleted."); }
}

function confirmClb() {
	var backupOKa = confirm("Are you sure you want to clear the backup directory?");
	if (backupOKa==true) {
		var backupOKb = confirm("Are you REALLY sure?");
		if (backupOKb==true) { window.location="index.php?system=Modules&page=admin&active=FileManager&a=clb"; }
		else { alert("Backup directory purge cancelled."); }
	}
	else { alert("Okay.  The backup directory was not cleared."); }
}
-->
</script>

<div class="fldiv"> <p class="dtitle">Files</p></div><div style="align : right; margin-left:5px;">
		<table id="filemanager">
<?php
clearstatcache();
$flist = getDir($fd);
for ($i=2; $i<count($flist); $i++) {
	$f2 = "";
	if ($i%2==0) $f2 = "2";
	echo "<tr class=\"afile\"><td class=\"tfile".$f2."\"><a href=\"".$fd.$flist[$i]."\" class=\"file\">".$flist[$i]."</a> <span class=\"fsize\">(".fFSize($fd.$flist[$i]).", ".substr(sprintf("%o",fileperms($fd.$flist[$i])),-4).")</span></td><td class=\"tactions".$f2."\"><a href=\"index.php?system=Modules&page=admin&active=FileManager&a=edt&filename=".$flist[$i]."\" class=\"actn\">Edit</a> | <a href=\"#function\" class=\"actn\" onClick=\"highlightBox('rename'); self.document.ren.newnm.focus(); document.ren.orignm.value = '".$flist[$i]."'\">Rename</a> | <a href=\"#function\" class=\"actn\" onClick=\"highlightBox('chmod'); self.document.chm.chval.focus(); document.chm.filename.value = '".$flist[$i]."'; document.chm.chval.value='".substr(sprintf("%o",fileperms($fd.$flist[$i])),-4)."';self.document.chm.chval.select()\">CHMOD</a> | <a href=\"#function\" class=\"actn\" onClick=\"highlightBox('copy'); self.document.cpy.newnm.focus(); document.cpy.orignm.value = '".$flist[$i]."'\">Copy</a> | <a href=\"#function\" class=\"actn\" onClick=\"confDelete('".$flist[$i]."')\">Delete</a></td></tr>";
	}
?>
		</table><br /><br />
</div>

<div class="fnc"> <p class="dtitle"><a name="function">Actions</a></p>

	<p class="action">New File</p>
		<div class="fnctn" id="newfile">
			<form action="index.php?system=Modules&page=admin&active=FileManager" method="post" name="new">
				<input type="hidden" name="a" value="new" />
				Create a new file named <input type="text" name="filename" value="" /> with permissions
				<input type="text" name="chval" value="<?php echo($newPerm); ?>" />
				<input type="submit" name="go" value="Go >" />
			</form>
		</div>
		
	<p class="action">Upload File</p>	
		<div class="fnctn" id="uploadfile">
			<form action="index.php?system=Modules&page=admin&active=FileManager" method="post" name="upl" enctype="multipart/form-data">
				<input type="hidden" name="a" value="upl" />
				<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo($maxFileSize); ?>" />
				Upload file <input type="file" name="upFile" /><br /> and set permissions to
				<input type="text" name="chval" value="<?php echo($uplPerm); ?>" />
				<input type="submit" name="go" value="Go >" />
			</form>
		</div>
		
	<p class="action">Rename</p>
		<div class="fnctn" id="rename">
			<form action="index.php?system=Modules&page=admin&active=FileManager" method="post" name="ren">
				<input type="hidden" name="a" value="ren" />
				From <input type="text" name="orignm" value="" /> to 
				<input type="text" name="newnm" value="" />
				<input type="submit" name="go" value="Go >" />
			</form>
		</div>

	<p class="action">CHMOD</p>
		<div class="fnctn" id="chmod">
			<form action="index.php?system=Modules&page=admin&active=FileManager" method="post" name="chm">
				<input type="hidden" name="a" value="chm" />
				Set file <input type="text" name="filename" value="" /> to have permissions
				<input type="text" name="chval" value="" />
				<input type="submit" name="go" value="Go >" />
			</form>
		</div>

	<p class="action">Copy</p>
		<div class="fnctn" id="copy">
			<form action="index.php?system=Modules&page=admin&active=FileManager" method="post" name="cpy">
				<input type="hidden" name="a" value="cpy" />
				Copy <input type="text" name="orignm" value="" /> to
				<input type="text" name="newnm" value="" />
				<a class="actn" href="#function" onclick="document.cpy.newnm.value=document.cpy.orignm.value+'.bu'">[Quick Backup]</a>
				<input type="submit" name="go" value="Go >" />
			</form>
		</div>
		
	<p class="action">Backup Directory Functions</p>
		<div class="fnctn" id="duplicate">
			<br />
			To go to the directory where files are autosaved, click <a class="actn" href="<?php echo($bd); ?>">here</a>.<br /><br />
			To clear the backup directory, click <a class="actn" href="#" onclick="confirmClb();">here</a>.<br />
		</div>
</div>
<?php }

elseif ($_REQUEST["a"] == "new") {			//New File
	if (ereg('\/', $_REQUEST["filename"])==1) die(messageBox("ERROR: Working out of directory is forbidden.", "index.php?system=Modules&page=admin&active=FileManager", TRUE));
	if (file_exists($fd.$_REQUEST["filename"])==1) die(messageBox("ERROR: File already exists.", "index.php?system=Modules&page=admin&active=FileManager", TRUE));

	///////////////////////////
	touch($fd.$_REQUEST["filename"]);		
	eval("chmod(\"". $fd.$_REQUEST["filename"] ."\", ". $_REQUEST["chval"] .");");
	$f = fopen($fd.$_REQUEST["filename"],"w");
	fwrite($f,"Remove this line and start editing your file here.");
	fclose($f);
	choiceBox("The file ".$_REQUEST["filename"]." was created with permissions ".$_REQUEST["chval"].".  Would you like to edit it or return home?", "Edit ".$_REQUEST["filename"], "index.php?system=Modules&page=admin&active=FileManager&a=edt&filename=".$_REQUEST["filename"], "Home", "index.php?system=Modules&page=admin&active=FileManager");	
}

elseif ($_REQUEST["a"] == "upl") {			//Upload File
	$ufname = stripslashes(basename($_FILES['upFile']['name']));
	$uf = $ud.$ufname;
	if (move_uploaded_file(stripslashes($_FILES['upFile']['tmp_name']), $uf)!=1) die(messageBox("ERROR: File upload failed.", "index.php?system=Modules&page=admin&active=FileManager", TRUE));
	eval("chmod(\"".$uf."\", ". $_REQUEST["chval"] .");");
	messageBox("The file ".$ufname." was uploaded and now has permissions ".$_REQUEST["chval"].".", "index.php?system=Modules&page=admin&active=FileManager");
}

elseif ($_REQUEST["a"] == "edt") { 			//Edit File
	if (ereg('\/', $_REQUEST["filename"])==1) die(messageBox("ERROR: Working out of directory is forbidden.", "index.php?system=Modules&page=admin&active=FileManager", TRUE)); 
	if (file_exists($fd.$_REQUEST["filename"])!=1) die(messageBox("ERROR: That file does not exist.", "index.php?system=Modules&page=admin&active=FileManager", TRUE));
	$f = fopen($fd.$_REQUEST["filename"],"r");
	$fdata = fread($f,filesize($fd.$_REQUEST["filename"]));
	fclose($f);
	
	$fdata = ereg_replace("&", "&amp;", $fdata);
	$fdata = ereg_replace("<", "&lt;", $fdata);
	$fdata = ereg_replace(">", "&gt;", $fdata); ?>

<script type="text/javascript">
<!--
//Unfortunately, I had to implement a hack because it doesn't seem to work on Internet Explor[d]er.  Sorry IE users.
function fhot() { //Source: http://www.thescripts.com/forum/thread89019.html
if (navigator.appName == "Microsoft Internet Explorer")	{
	document.getElementById("content").style.height="<?php echo($ieHeight); ?>px";
	}
else {
	var t = document.getElementById("content");
	var h = window.innerHeight ? window.innerHeight :
	t.parentNode.offsetHeight;
	t.style.height = (h - t.offsetTop - 37) + "px";
}
}
window.onresize = fhot;

function autosaveIt() {
	document.asv.acontent.value = document.edtbox.content.value;
	document.asv.submit();
	}
setInterval("autosaveIt()",<?php echo($autosaveInterval); ?>);

function confirmCancel() {
	var ok = confirm("Are you sure you want to cancel and return home?  Your changes will be lost.");
	if (ok==true) { 
		window.location='index.php?system=Modules&page=admin&active=FileManager';
	}
}
-->
</script>
<div onLoad=fhot()>
<div style="position:absolute; top:0px; left:0px"><iframe src="index.php?system=Modules&page=admin&active=FileManager&a=asv&filename=" style="width:0px; height: 0px; border: 0px dashed #006600" name="asvframe" scrolling="no"></iframe>
<form name="asv" method="post" target="asvframe" action="index.php?system=Modules&page=admin&active=FileManager">
<input type="hidden" name="a" value="asv" />
<input type="hidden" name="filename" value="<?php echo $_REQUEST["filename"]; ?>" />
<input type="hidden" name="acontent" value="" /></form></div>

<div class="fullsize"><p class="dtitle">Edit File</p>
<form method="post" action="index.php?system=Modules&page=admin&active=FileManager" name="edtbox"><input type="hidden" name="a" value="sav" /><input type="hidden" name="filename" value="<?php echo $_REQUEST["filename"]; ?>" />
<textarea name="content" class="fileContent" id="content"><?php echo $fdata; ?></textarea><br />
<input type="submit" name="save" value="Save Changes" /> <input type="button" name="home" value="Cancel" onClick="confirmCancel()" />
</form>
</div>
</div>
<?php }

elseif ($_REQUEST["a"] == "sav") { 			//Save File
	if (ereg('\/', $_REQUEST["filename"])==1) die(messageBox("ERROR: Working out of directory is forbidden.", "index.php?system=Modules&page=admin&active=FileManager", TRUE)); 
	if (file_exists($fd.$_REQUEST["filename"])!=1) die(messageBox("ERROR: That file does not exist.", "index.php?system=Modules&page=admin&active=FileManager", TRUE));
	$fdata = $_REQUEST["content"];
	if (get_magic_quotes_gpc() == 1) $fdata = stripslashes($fdata);
	$fdata = ereg_replace("&amp;", "&", $fdata);
	$fdata = ereg_replace("&lt;", "<", $fdata);
	$fdata = ereg_replace("&gt;", ">", $fdata);
	$f = fopen($fd.$_REQUEST["filename"],"w");
	fwrite($f,$fdata);
	fclose($f);
	choiceBox("The file ".$_REQUEST["filename"]." was saved.", "Home", "index.php?system=Modules&page=admin&active=FileManager", "Keep Editing", "index.php?system=Modules&page=admin&active=FileManager&a=edt&filename=".$_REQUEST["filename"]);
 }

elseif ($_REQUEST["a"] == "asv") { 			//Autosave File
	if (ereg('\/', $_REQUEST["filename"])==1) die(messageBox("ERROR: Working out of directory is forbidden.", "index.php?system=Modules&page=admin&active=FileManager", TRUE)); 
	if ($_REQUEST["filename"]=="") die("
		<html><head><title>Autosave</title>
		<link rel=\"stylesheet\" type=\"text/css\" href=\"style.css\">
		</head><body style=\"background-color:#DDFFAA\">
		<p class=\"autosave\">Not autosaved yet.</p>
		</body></html>");
	touch($bd.$_REQUEST["filename"].".bu");
	$fdata = $_REQUEST["acontent"];
	if (get_magic_quotes_gpc() == 1) $fdata = stripslashes($fdata);
	$fdata = ereg_replace("&amp;", "&", $fdata);
	$fdata = ereg_replace("&lt;", "<", $fdata);
	$fdata = ereg_replace("&gt;", ">", $fdata);
	$f = fopen($bd.$_REQUEST["filename"].".bu","w");
	fwrite($f,$fdata);
	fclose($f);
	echo "<html><head><title>Autosave</title>
		<link rel=\"stylesheet\" type=\"text/css\" href=\"style.css\">
		</head><body style=\"background-color:#DDFFAA\">
		<p class=\"autosave\">Autosaved: ".date("m/d/y g:i:s a")."</p>
		</body></html>";
 }
 
elseif ($_REQUEST["a"] == "ren") { 			//Rename File
	if (ereg('\/', $_REQUEST["orignm"])==1 || ereg('\/', $_REQUEST["newnm"])==1) die(messageBox("ERROR: Working out of directory is forbidden.", "index.php?system=Modules&page=admin&active=FileManager", TRUE));
	if (file_exists($fd.$_REQUEST["orignm"])!=1) die(messageBox("ERROR: The file ".$_REQUEST["orignm"]." does not exist!", "index.php?system=Modules&page=admin&active=FileManager", TRUE));
	if ($_REQUEST["newnm"] == "" || file_exists($fd.$_REQUEST["newnm"])==1) die (messageBox("ERROR: The new file ".$_REQUEST["newnm"]." already exists or does not have a valid name.", "index.php?system=Modules&page=admin&active=FileManager", TRUE));
	
	///////////////////////////
	if (rename($fd.$_REQUEST["orignm"],$fd.$_REQUEST["newnm"])==1) messageBox("The file ".$_REQUEST["orignm"]." was renamed to ".$_REQUEST["newnm"].".", "index.php?system=Modules&page=admin&active=FileManager");
	else messageBox("ERROR: Something went wrong when trying to rename the file.", "index.php?system=Modules&page=admin&active=FileManager", TRUE);
}

elseif ($_REQUEST["a"] == "chm") {			//CHMOD File
	if (ereg('\/', $_REQUEST["filename"])==1) die(messageBox("ERROR: Working out of directory is forbidden.", "index.php?system=Modules&page=admin&active=FileManager", TRUE));
	if (file_exists($fd.$_REQUEST["filename"])!=1) die(messageBox("ERROR: The file ".$_REQUEST["filename"]." does not exist!", "index.php?system=Modules&page=admin&active=FileManager", TRUE));
	
	///////////////////////////
	//Workaround for string-int problem.
	eval("chmod(\"". $fd.$_REQUEST["filename"] ."\", ". $_REQUEST["chval"] .");");
	messageBox("The file ".$_REQUEST["filename"]." now has permissions ".$_REQUEST["chval"].".", "index.php?system=Modules&page=admin&active=FileManager");
}

elseif ($_REQUEST["a"] == "cpy") {			//Duplicate File
	if (ereg('\/', $_REQUEST["orignm"])==1 || ereg('\/', $_REQUEST["newnm"])==1) die(messageBox("ERROR: Working out of directory is forbidden.", "index.php?system=Modules&page=admin&active=FileManager", TRUE));
	if (file_exists($fd.$_REQUEST["orignm"])!=1) die(messageBox("ERROR: The file ".$_REQUEST["orignm"]." does not exist!", "index.php?system=Modules&page=admin&active=FileManager", TRUE));
	if ($_REQUEST["newnm"] == "" || file_exists($fd.$_REQUEST["newnm"])==1) die (messageBox("ERROR: The new file ".$_REQUEST["newnm"]." already exists or does not have a valid name.", "index.php?system=Modules&page=admin&active=FileManager", TRUE));
	
	///////////////////////////
	if (copy($fd.$_REQUEST["orignm"],$fd.$_REQUEST["newnm"])==1) messageBox("The file ".$_REQUEST["orignm"]." was copied to ".$_REQUEST["newnm"].".", "index.php?system=Modules&page=admin&active=FileManager");
	else messageBox("ERROR: Something went wrong while trying to copy the file.", "index.php?system=Modules&page=admin&active=FileManager", TRUE);
}

elseif ($_REQUEST["a"] == "del") { 			//Delete Fiile
	if (ereg('\/', $_REQUEST["filename"])==1) die(messageBox("ERROR: Working out of directory is forbidden.", "index.php?system=Modules&page=admin&active=FileManager", TRUE));
	if (file_exists($fd.$_REQUEST["filename"])!=1) die(messageBox("ERROR: The file ".$_REQUEST["filename"]." does not exist!", "index.php?system=Modules&page=admin&active=FileManager", TRUE));
	
	///////////////////////////	
	if (unlink($fd.$_REQUEST["filename"])==1) messageBox("The file ".$_REQUEST["filename"]." was deleted.", "index.php?system=Modules&page=admin&active=FileManager");
	else messageBox("ERROR: Something went wrong when trying to delete that file.", "index.php?system=Modules&page=admin&active=FileManager", TRUE);
}

elseif ($_REQUEST["a"] == "clb") { 			//Clear Backup Directory
	clearstatcache();
	$flist = getDir($bd);
	$results = "";
	for ($i=2; $i<count($flist); $i++) {
		$bFile = $flist[$i];
		if (unlink($bd.$bFile)==1) $results = $results."The file ".$bFile." was deleted.<br />";
		else $results = $results."ERROR: Something went wrong while trying to delete ".$bFile.".<br />";
	}
	messageBox($results, "index.php?system=Modules&page=admin&active=FileManager");
}

?>