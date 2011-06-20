<?php
include_once('../../config.php');
$uploaddir = '../../uploads/';

$message[1] = 'İmaj Başarıyla Silindi';
$not = notification($message);

if(isset($_FILES['upload'])) {
$way = $_FILES['upload']['name'];
$way = str_replace(" ", "-", $way);
$filename = basename($way);
$file = $uploaddir . basename($way);
$name = basename($way);
$filename = explode('.', $filename);

$i=1;
while(file_exists($file)) {
$file= $uploaddir.$filename[0].'-'.$i.'.'.$filename[1];
$name= $filename[0].'-'.$i.'.'.$filename[1];
$i++;
}

 
if (move_uploaded_file($_FILES['upload']['tmp_name'], $file)) { 
	imagejpeg (thumbnail($uploaddir.$name, '115', '90'), $uploaddir.'thumbs/thumb_'.$name, 90 );
	echo $name;
	echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction(1, '".SITE_ADDRESS."/uploads/$name');</script>";
} else {
	echo "error";
}
}
else {
if(isset($_GET['del'])) {
$del = $_GET['del'];
$dir = $_GET['dir'];
if(file_exists($uploaddir.$dir.'/'.$del)) {
unlink($uploaddir.$dir.'/'.$del);
unlink($uploaddir.$dir.'/thumbs/thumb_'.$del);
header('Location: ?message=1');
}else 
	{
		echo 'Yanlış Sorgu<br>';
		die;
	}
}

$ic.='<ul id="gallery">';
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
							$ic.="
							<li>
							<img alt='{$file}' src='".SITE_ADDRESS."/uploads/{$year}/{$month}/thumbs/thumb_{$file}'>
							<span>{$file} {$month}/{$year} --> {$filedesc[0]}*{$filedesc[1]}</span>
							<span><a href='?del={$file}&dir={$year}/{$month}' onclick='confirm()' title='Delete this image'>Delete image</a></span>
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
$ic.='</ul>';

}
include("../template.php");
?>