<?php

function getadminurl($dir = NULL) {
return SITE_ADDRESS.'/sm-admin/'.$dir;
}

function smt_dir($where=NULL) {
	if($where==NULL) {
		echo SM_SCRIPTPATH.SITE_FILES.'media/';
	}else {
		echo SM_SCRIPTPATH.SITE_FILES.'/media/'.$where;
	}
}

function array_unserialize($text) {
	if(isset($text)) {
		$data = unserialize($text);
		if(!is_array($data)) {$data = array();}
		return $data;
	}
}

function multi_array_values($array) {
	if(is_array($array)) {
		foreach($array as $a) {
			$temp[]=$a['value'];
		}
		return $temp;
	}else {
		return false;
	}
}

function value_search($value, $array) {
    foreach ($array as $a) {
        if ($a['value'] == $value) {
            return $a['key'];
        }
    }
}

function multi_array_keys($array) {
	if(is_array($array)) {
		foreach($array as $a) {
			$temp[]=$a['key'];
		}
		return $temp;
	}else {
		return false;
	}
}

function convert_array($data) {
	if(!isset($data)) {
		return array();
	}else
	if(!is_array($data)) {
		return array($data);
	}
	return $data;
}

function short($number, $content)
{
	if (strlen($content)>$number) {
	$text = substr($content, 0, $number);
	$text = $text.'[...]';
	return $text;
	}else {return $content;}
}

function thumburl($link) {
	return preg_replace('/\/([^\/]*)$/i', '/thumbs/$1', $link);
}

function cleanURL($string)
{
    $url = str_replace("'", '', $string);
    $url = str_replace('%20', ' ', $url);
    $url = preg_replace('~[^\\pL0-9_]+~u', '-', $url); // substitutes anything but letters, numbers and '_' with separator
    $url = trim($url, "-");
    $url = iconv("utf-8", "us-ascii//TRANSLIT", $url);  // you may opt for your own custom character map for encoding.
    $url = strtolower($url);
    $url = preg_replace('~[^-a-z0-9_]+~', '', $url); // keep only letters, numbers, '_' and separator
    return $url;
}

function filewrite($file, $content, $overwrite = false, $type = 'w') {
$html = null;
$error = false;
if(!$overwrite && is_file($file)) {
$html.="There is already a file in '<b>$file</b>'.";
$error = true;
}else {
	if($handle = @fopen($file, $type)){
		if(is_writable($file)){
			if(@fwrite($handle, $content) === FALSE){
				$html.= "I create the file <strong>{$file}</strong>, but I couldn't write my codes in this file. Please you copy paste the following codes in $file.<br>";
				$error = true;
			}
			$html.= "The file $file was created and written successfully!";
			return $html;
			fclose($handle);
		}
		else{
			$html.= "I create the file <strong>{$file}</strong>, but I couldn't write my codes in this file. Please you copy paste the following codes in $file.<br>";
			$error = true;
	}
	}else{
		$path = explode('/', rtrim($file, '/'));
		array_pop($path);
		$cursor = null;
		foreach($path as $p) {
			$cursor.= $p.'/';
			if(!is_dir($cursor)) {
				if(@!mkdir($cursor)) {
				$html.= "There is no directory called '<b>{$cursor}</b>', and the parent directory is not writeable.";
				$error = true;
				}
			}
			
		}
		if($handle = @fopen($file, $type)){
			if(@fwrite($handle, $content)){
				$html.= "The file <b>$file</b> was created and written successfully!";
			}else {
			$html.="Error occured, couldn't write '{$file}'.";
			$error = true;
			}
		}
	}
	}
	if($error) {
		$html.="<textarea id='code' name='constant'>$content</textarea>";
	}
	return $html;
}