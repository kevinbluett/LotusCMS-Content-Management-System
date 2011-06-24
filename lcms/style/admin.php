<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
  
  <title><?php print $p->getSiteTitle(); ?> - <?php print $p->getContentTitle();  ?></title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <meta name="description" content="<?php print $p->getMetaDescription(); ?>" />
  <meta name="keywords" content="<?php print $p->getMetaKeywords(); ?>" />
  <link href="style/comps/admin/css/global.css" rel="stylesheet" type="text/css" />
  <script src="style/comps/admin/js/jquery.js"></script>
  <script type="text/javascript" src="style/comps/admin/js/jquery.corner.js"></script>
  <script type="text/javascript">
	<!--
	$(document).ready(function() {
  		// Handler for .ready() called.
  		$('body').corner();
  		$('#footer').corner();
	  	$('#menu').corner("right");
	});
	-->
	</script>
  	<?php $p->getExtraMeta(); ?>
</head>
<body>

  <div id="masthead">
    <div style="float: left;"><a href="index.php?page=index"><img src="style/comps/admin/img/smalllogo.png" style="text-decoration: none; border: 0;" alt="LotusCMS Adminstration"/></a></div>
    <div style="float: right;"><a href="http://www.lotuscms.org"><img src="style/comps/admin/img/version.png" style="text-decoration: none; border: 0;" alt="LotusCMS Adminstration"/></a></div>
  </div>
  
  <div id="content">
    
    <div id="main">
      
      <div class="article">
      
	<h2><a href="#"><?php print $p->getContentTitle();  ?></a></h2>
      
	<?php print $p->getErrorData(); ?>
	<?php print $p->getContent(); ?>
	
      </div>
      
    </div>
    
    <div id="secondary">
      
      <h2>Menu</h2>
      <ul id="menu">
	<li <?php if($p->getInputString("system")=="Dash"){ print "id='active'";} ?>><a style="background-image: url('style/comps/admin/img/dashboard.png');background-repeat: no-repeat;background-position: 0px 2px;" href="?system=Dash&page=index">Dashboard</a></li>
	<li <?php if($p->getInputString("system")=="PageList"){ print "id='active'";} ?>><a style="background-image: url('style/comps/admin/img/pages.png');background-repeat: no-repeat;background-position: 0px 2px;" href="?system=PageList&page=list">Pages</a></li>
	<li <?php if($p->getInputString("system")=="UsersList"){ print "id='active'";} ?>><a style="background-image: url('style/comps/admin/img/users.png');background-repeat: no-repeat;background-position: 0px 2px;" href="?system=UsersList&page=list">Users</a></li>
	<li <?php if($p->getInputString("system")=="Modules"){ print "id='active'";} ?>><a style="background-image: url('style/comps/admin/img/modules.png');background-repeat: no-repeat;background-position: 0px 2px;" href="?system=Modules&page=index">Modules</a></li>
	<li <?php if($p->getInputString("system")=="Settings"){ print "id='active'";} ?>><a style="background-image: url('style/comps/admin/img/settings.png');background-repeat: no-repeat;background-position: 0px 2px;" href="?system=Settings&page=index">Settings</a></li>
	<li <?php if($p->getInputString("system")=="Admin"){ print "id='active'";} ?>><a style="background-image: url('style/comps/admin/img/logout.png');background-repeat: no-repeat;background-position: 0px 2px;" href="?system=Admin&page=logout">Logout</a></li>
      </ul>
      
    </div>
    
    <ul id="footer" class="clearfix">
      
      <li style="float: right;">Proudly Powered by: <a href="http://www.lotuscms.org">LotusCMS</a></li>
      
      <li style="text-align: left;float:left;">&copy; <?php date_default_timezone_set("GMT"); echo(date("Y")); print " ".$p->getSiteTitle();?>. All Rights Reserved.</li>
      
    </ul>
    
  </div>

</body>
</html>
