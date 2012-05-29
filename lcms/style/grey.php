<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" >
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
  		<meta name="description" content="<?php print $p->getMetaDescription(); ?>" />
  		<meta name="keywords" content="<?php print $p->getMetaKeywords(); ?>" />
		<meta name="author" content="name of author - Manjeet Singh Sawhney   www.manjeetss.com" />
		<link rel="stylesheet" type="text/css" href="style/comps/grey/css/style.css" media="screen" />
		<title><?php print $p->getSiteTitle(); ?> - <?php print $p->getContentTitle();  ?></title>
		<?php $p->getExtraMeta(); ?>
	</head>	
	<body>
		<div id="main">
			<div id="header">
				<div class="companyname"><?php print $p->getSiteTitle(); ?></div>				
				<div id="right">
					
				</div>
			</div>
			<div id="navbar">
				<ul>
					<?php print $p->getMenu(true); ?>
				</ul>
			</div>
			<div id="maincontent">
	          <?php if($p->getColumns()==2){ ?>
	          <div id="sidebar">
		      <h3><?php  print $p->getLeftTitle(); ?></h3>
						<?php print $p->getLeftContent();  ?>
	          </div> <?php } ?>
				<div class="content">
					<h1><?php print $p->getContentTitle();  ?></h1>
					<?php print $p->getErrorData(); ?>
					<?php print $p->getContent(); ?>
				</div>
			</div>
		</div>
		<div id="footer">
			<p>
				&copy; <?php date_default_timezone_set("GMT");echo(date("Y")); ?> <?php print $p->getSiteTitle(); ?>. Powered by: <a href="http://www.lotuscms.org">LotusCMS</a>.			
			</p>
		</div>
	</body>
</html>

