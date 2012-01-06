<?php defined('SYSPATH') or die('No direct script access.');

class Simit_Forms extends Controller_Template {
	public $template = 'smadmin/template';

	public function action_before() {
		$this -> template -> scripts = array("media/admin/js/addform.js");
		$this -> template -> menu = "<li><a href='?new'>Add new form</a></li>";
	}

	public function action_index() {

	}

	public function action_new() {
		$view = View::factory("admin/form/newform");
		$this -> response -> body($view);

		if (isset($_POST['html'])) {
			$table = findfreetable('form');
			$count = (int)$_POST['count'];
			for ($i = 1; $i <= $count; $i++) { $comment[$i] = $_POST['label' . $i];
			}
			createtable($table, $count, $comment);

			for ($i = 1; $i <= $count; $i++) {
				$posts[] = 'mysql_real_escape_string($_POST["data' . $i . '"])';
				$numbers[] = ' `$i` = "%s"';
			}
			$numbers = implode(', ', $numbers);
			$posts = implode(', ', $posts);
			$currentquery = $_SERVER['QUERY_STRING'];
			$post = '$_POST["' . $table . '"]';
			$newfile = <<<html

<?php
if(isset({$post})) {
	sm_sendmail({$formnumber});
	$sm->dbquery = sprintf('INSERT INTO `{$table}`', {$posts});
}
?>

<html>
<head>
<link rel="stylesheet" href="../css/validationEngine.jquery.css" type="text/css"/>
<script src="../js/jquery-1.6.min.js" type="text/javascript"></script>
<script src="../js/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"> </script>
<script src="../js/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<script>
	jQuery(document).ready(function(){
		jQuery("#formID").validationEngine();
		$("{$table}").bind("jqv.form.validating", function(event){
			$("{$table}").prepend("");
		});

		$("#formID").bind("jqv.form.result", function(event , errorFound){
			if(errorFound) $("#{$table}").prepend("There is some problems with your form");
		});
	});
</script>
		
<style>
	label {display:block;}
</style>
</head>

<form id="form{$formnumber}" method="post" action="?{$currentquery}">
<input name="form{$formnumber}" type="hidden">
{$_POST['html']}
</form>
html;

			$file = "../website/forms/{$table}.php";
			$put = putfile($file, $newfile);
			if (!$put) {$ic .= $put;
			}
		}
	}

}
