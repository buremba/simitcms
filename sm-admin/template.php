<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
<title>Basic CMS</title>
<?php if ($dir==FALSE) {echo<<<html
	<link rel="stylesheet" type="text/css" href="main.css" />
	<script type="text/javascript" src="js/jquery-1.4.4.min.js"></script>
html;
}else {echo<<<html
	<link rel="stylesheet" type="text/css" href="../main.css" />
	<script type="text/javascript" src="../plugin/jquery-1.4.4.min.js"></script>
	<script type="text/javascript" src="../plugin/jquery.qtip-1.0.0-rc3.min.js"></script>
	<script type="text/javascript" src="../plugin/fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
	<script type="text/javascript" src="../plugin/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
	<link rel="stylesheet" type="text/css" href="../plugin/fancybox/jquery.fancybox-1.3.4.css" media="screen" />
	<script type="text/javascript" src="js/jquery-ui-1.8.13.custom.min.js" ></script>
	<link type="text/css" href="js/Aristo/jquery-ui-1.8.7.custom.css" rel="stylesheet" />
	<script src="../plugin/codeedit.js" type="text/javascript"></script>
	<script type="text/javascript" src="me.js"></script>
	
html;
}
if($textarea==TRUE) {echo '<script type="text/javascript" src="../plugin/ckeditor/ckeditor.js"></script>';}
if($imgupload==TRUE) {echo '<script type="text/javascript" src="../plugin/ajaxupload.3.5.js" ></script>';}

echo $head; 
?>

</head>

<body>
<div id="top"></div>
   <div id="header">
<?php if($login==TRUE) {echo $ic;} else { ?>
   <div id="notification"><div class="box"><?php echo $not; ?></div></div>
   </div>
         
		 <nav class="orangen">
		 <?php 
			//$dir = opendir ("./");
			$nav.='<ul>';
			/*
			$sql=mysql_query("SELECT * FROM settings WHERE setting_owner='page' ORDER BY setting_id ASC");
			while($pg = mysql_fetch_assoc($sql)) {
			 $nav.="<li><a href='?pg=".$pg['setting_id']."'>".$pg['setting_name']."</a></li>";
			}
			*/
			$nav.="
			<li><a href='index.php'>Pages</a></li>
			<li><a href='gallery.php'>Gallery</a></li>
			<li><a href='blockadmin.php'>Blocks</a></li>
			<li><a href='blogadmin.php'>Blog</a></li>
			<li><a href='customadmin.php'>Customs</a></li>
			<li><a href='formadmin.php'>Forms</a></li>
			<li><a href='#'>Polls</a></li>
			<li style='float:right; margin-right:0;'><a style='padding-left:8px; padding-right:8px;' class='selected' href='?logout'>".$user->get_property('username')."</a></li>
			<li style='float:right;'><a href='#'>B</a></li>
			<li style='float:right;'><a href='#'>S</a></li>
			";
			$nav.='</ul>';
		?>
			<?php echo $nav; ?>
		 </nav>
			<ul class="submenu">
			<li><a href="">Custom Areas</a></li>
			<li><a href="">New Custom Area</a></li>
			<li><a href="">Options</a></li>
			<li><a href="">Help</a></li>
			</ul>
		<div id="content">
			<div id="contentinline">
			<?php echo $ic; ?>
			<div style="clear:both;"></div>
			</div>
		 </div>
		 <?php } ?>
</body>
</html>
