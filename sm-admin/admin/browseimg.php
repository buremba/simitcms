<style>
body {
 font-family:Helvetica,Arial,sans-serif,verdana;
 font-size: 12px;
 color: #333;
 }
ul.browseimg {
list-style:none;
}
ul.browseimg li {
padding:2px;
float:left;
width:102px;
margin:3px;
border:1px solid #EBEBEB;
background:#FBFBFB;
-moz-border-radius:2px;
-webkit-border-radius:2px;
}
</style>
<?php
include_once('../../config.php');
$uploaddir = '../../uploads/';
$ref = $_GET['ref'];
$name = $_GET['name'];
$address = SITE_ADDRESS.'/uploads/';
?>
<select>
	<option>Tümü</option>
</select>
<?php
echo '<ul class="browseimg">';
if ($dir = opendir($uploaddir)) {
	while (false !== ($year = readdir($dir))) {
		if (is_dir($uploaddir.$year) && $year!=='.' && $year!=='..') {
			$yeardir = opendir($uploaddir.$year);
			while (false !== ($month = readdir($yeardir))) {
				if (is_dir($uploaddir.$year.'/'.$month) && $month!=='.' && $month!=='..') {
				$monthdir = opendir($uploaddir.$year.'/'.$month);
					while (false !== ($file = readdir($monthdir))) {
						if(filetype($uploaddir.$year.'/'.$month.'/'.$file) == 'file') {
						$filedesc = getimagesize($uploaddir.$year.'/'.$month.'/'.$file);
							if($filedesc['mime'] == 'image/jpeg' || $filedesc['mime'] == 'image/png' || $filedesc['mime'] == 'image/gif') {
							echo "
<li>
	<img alt='{$file}' height='80' src='".SITE_ADDRESS."/uploads/thumbs/thumb_{$file}' onclick=\"browseimg('{$ref}', '{$address}{$file}', '{$name}')\">
	<span>{$file}</span>
</li>
";
}
						}
					}
				}
			}
		}
	}
closedir($dir);
}
	echo '</ul>';
?>