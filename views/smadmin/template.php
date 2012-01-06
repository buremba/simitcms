<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
<title>RGB CMS</title>

	<link rel="stylesheet" type="text/css" href="<?php echo URL::site('sm-media/main.css'); ?>" />
	<link rel="stylesheet" type="text/css" href="<?php echo URL::site('sm-media/js/Aristo/jquery-ui-1.8.7.custom.css'); ?>" />
	<script type="text/javascript" src="<?php echo URL::site('sm-media/js/jquery-1.6.4.min.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo URL::site('sm-media/js/jquery.plugins.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo URL::site('sm-media/js/jquery-ui-1.8.16.custom.min.js'); ?>" ></script>
	<script type="text/javascript" src="<?php echo URL::site('sm-media/js/me.js'); ?>"></script>
	
	<link rel="stylesheet" type="text/css" href="<?php echo URL::site('sm-media/js/chosen.jquery.min.css'); ?>" />
	<script type='text/javascript' src="<?php echo URL::site('sm-media/js/chosen.jquery.min.js'); ?>"></script>
	
	<script type="text/javascript" src="<?php echo URL::site('sm-media/js/jquery.image-gallery.js'); ?>" ></script>
	<link rel="stylesheet" type="text/css" href="<?php echo URL::site('sm-media/js/jquery.image-gallery.css'); ?>" />
	
	<link rel="stylesheet" type="text/css" href="<?php echo URL::site('sm-media/js/validationEngine.jquery.css'); ?>" />
	<script type='text/javascript' charset="utf-8" src="<?php echo URL::site('sm-media/js/validation_lang/jquery.validationEngine-tr.js'); ?>"></script>
	<script type='text/javascript' charset="utf-8" src="<?php echo URL::site('sm-media/js/jquery.validationEngine.js'); ?>"></script>
	<?php
	if(isset($scripts)) {
		if (is_array($scripts)) 
			foreach($scripts as $s) echo html::script($s);
		else 
			echo html::script($scripts);
	}
	if(isset($styles)) {
		if (is_array($styles)) 
			foreach($styles as $s) echo html::style($s);
		else 
			echo html::style($styles);
	}
	?>
</head>
<body>
   <div id="header">
   <img src="#" height="65">
   <div id="notification">
   <div class="box">
	<p></p>
	<span class="close pointer" onclick="$(this).parent().hide();">×</span>
   </div>
   </div>
   </div>
         
		<nav class="orangen">
			<ul id="menu" class="menu">
			<?php foreach (Kohana::list_files('classes/controller/smadmin') as $path) : ?>
				<?php if(is_array($path)) continue; ?>
				<li><a href="<?php echo ADMINPATH.'/'.basename($path, '.php') ?>"><?php echo basename($path, '.php') ?></a></li>
			<?php endforeach ?>
			</ul>
		</nav>
		<div id="content">
		<?php  if(isset($menu) && is_array($menu)) : ?>
			<ul class='submenu'>
			<?php foreach($menu as $m) : ?>
				<li><a href="<?php echo $m['link'] ?>"><?php echo $m['title'] ?></a></li>
			<?php endforeach; ?>
			</ul>
		<?php endif; ?>
			<div id="contentinline">
			<?php echo $content; ?>
			<div style="clear:both; height:10px;"></div>
			</div>
		 </div>
<?php
if(isset($textarea)) {echo '
	<script src="'.URL::site('sm-media/plugins/elrte/js/elrte.min.js').'" type="text/javascript" charset="utf-8"></script>
	<link rel="stylesheet" href="'.URL::site('sm-media/plugins/elrte/css/elrte.min.css').'" type="text/css" media="screen" charset="utf-8">
	<script src="'.URL::site('sm-media/plugins/elrte/js/i18n/elrte.tr.js').'" type="text/javascript" charset="utf-8"></script>

	<script type="text/javascript" charset="utf-8">
		$().ready(function() {
			$(".ckeditor").elrte({
				cssClass : "el-rte",
				//lang     : "tr",
				height   : 450,
				toolbar  : "maxi",
				cssfiles : ["sm-media/plugins/elrte/css/elrte-inner.css"]
			});
		})
	</script>
';}
if(isset($imgupload)) {echo '
<script type="text/javascript" src="'.URL::site("sm-media/js/fileuploader.js").'"></script>
';}
if(isset($code)) {echo '
<link rel="stylesheet" href="'.URL::site("sm-media/plugins/codemirror/codemirror.css").'">
<script src="'.URL::site("sm-media/plugins/codemirror/codemirror.js").'"></script>
<script src="'.URL::site("sm-media/plugins/codemirror/xml.js").'"></script>
<script src="'.URL::site("sm-media/plugins/codemirror/javascript.js").'"></script>
<script src="'.URL::site("sm-media/plugins/codemirror/css.js").'"></script>
<script src="'.URL::site("sm-media/plugins/codemirror/clike.js").'"></script>
<script src="'.URL::site("sm-media/plugins/codemirror/overlay.js").'"></script>
<script src="'.URL::site("sm-media/plugins/codemirror/php.js").'"></script>
<script src="'.URL::site("sm-media/plugins/codemirror/own.js").'"></script>
<link rel="stylesheet" href="'.URL::site("sm-media/plugins/codemirror/default.css").'">
';}
?>
<script>var dropdown=new TINY.dropdown.init("dropdown", {id:'menu', active:'menuhover'});</script>

</body>
</html>