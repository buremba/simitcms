<?php
$uploaddir = 'uploads/';
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
							<img alt='{$file}' src='".SITE_ADDRESS."/uploads/thumbs/thumb_{$file}'>
							<span>{$file}</span>
							<span><a href='?delete={$file}' onclick='confirm()' title='Delete this image'>Delete image</a></span>
							</li>
							";
							echo $file.' --> '.$month.'/'.$year.' --> '.$filedesc[0].'*'.$filedesc[1].'<br>';
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
?>