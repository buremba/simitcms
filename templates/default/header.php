<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title><?php smt_siteinfo('title');?> </title>
<meta name="generator" content="Simit Cms <?php smt_siteinfo('version');?>" />
<link href="<?php smt_theme('stylesheet_url');?>" rel="stylesheet" type="text/css" media="screen" />
<link href="<?php smt_theme('template_url');?>/lavalamp_test.css" type="text/css" media="screen" rel="stylesheet" />
<link rel="shortcut icon" href="<?php smt_theme('template_url');?>/favicon.ico" />
<?php addable_area('header');?>
</head>

<body>
	<div id="container">
	<div id="header">
		<h1>
			<?php smt_siteinfo('name');?>
		</h1>
		<span>
			<?php smt_siteinfo('description');?>
		</span>
	</div>
	<div id="navigation">
		<ul>
			<?php smt_page_list('<li>%s</li>'); ?>
		</ul>
	</div>