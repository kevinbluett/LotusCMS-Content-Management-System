<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;  charset=iso-8859-1" />
  	<meta name="description" content="<?php print $p->getMetaDescription(); ?>" />
  	<meta name="keywords" content="<?php print $p->getMetaKeywords(); ?>" />
 	<link rel="stylesheet" type="text/css" href="style/comps/minimal/style.css" media="all" />  
	<title><?php print $p->getSiteTitle(); ?> - <?php print $p->getContentTitle();  ?></title>
	<?php $p->getExtraMeta(); ?>
</head>

<body>
   <div id="container">
   
        

        <div id="header"><h1><?php print $p->getSiteTitle(); ?></h1></div>

      <div id="wrapper">

        <div id="navigation">
			<?php print $p->getMenu(false); ?>
        </div>
        

      
        <div id="content-wrapper">
            <div id="content">
                <h3 class="post-title"><a href="#"><?php print $p->getContentTitle();  ?></a></h3><span class="date">&nbsp;</span>
               	<?php print $p->getErrorData(); ?>
				<?php print $p->getContent(); ?>
			</div>
		</div>
		
			  
        <div id="sidebar-wrapper">
          <div id="sidebar">
	      <h3><?php  print $p->getLeftTitle(); ?></h3>
			<?php if($p->getColumns()==2){ ?>
					<?php print $p->getLeftContent();  ?>
			<?php } ?>
          </div> 
        </div>
        
        <div id="footer">&copy; <?php date_default_timezone_set("GMT");echo(date("Y")); ?> <?php print $p->getSiteTitle(); ?>. Powered by: <a href="http://www.lotuscms.org">LotusCMS</a>.
        
      </div> 
      
   </div>
</body>

</html>

