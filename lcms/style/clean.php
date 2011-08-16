<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
  
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <meta name="description" content="<?php print $p->getMetaDescription(); ?>" />
  <meta name="keywords" content="<?php print $p->getMetaKeywords(); ?>" />
  <link href="style/comps/clean/css/global.css" rel="stylesheet" type="text/css" />
  <title><?php print $p->getSiteTitle(); ?> - <?php print $p->getContentTitle();  ?></title>
  <?php $p->getExtraMeta(); ?>
  
</head>

<body>

  <div id="masthead">
    
    <h1><a href=""><?php print $p->getSiteTitle(); ?></a></h1>
    
	<?php print $p->getMenu(false); ?>
  </div>
  
  <div id="content">
    
    <div id="main">
      
      <div class="article">
      
	<?php if(isset($_GET['page'])){ if($_GET['page']=="index"||$_GET['page']==""){ ?><img src="style/comps/clean/images/main.jpg" alt="Your image here" /><?php } } ?>
      
	<h2><a href="#"><?php print $p->getContentTitle();  ?></a></h2>
      
	<p class="subheader">Visit the <a href="http://www.arboroia.com">arboroian project network</a> for even more!</p>
      
	<?php print $p->getErrorData(); ?>
		<?php print $p->getContent(); ?>
	
      </div>
      
    </div>
    
    <div id="secondary">
      
      <h2><?php  print $p->getLeftTitle(); ?></h2>
		<?php if($p->getColumns()==2){ ?>
				<?php print $p->getLeftContent();  ?>
		<?php } ?>
      
    </div>
    
    <ul id="footer" class="clearfix">
      
      <li>Design: <a href="http://www.letseat.at">LetsEat</a></li>
      
      <li>&copy; <?php echo(date("Y")); ?> Arboroian Project Network. All Rights Reserved.</li>
      
    </ul>
    
  </div>

</body>
</html>
