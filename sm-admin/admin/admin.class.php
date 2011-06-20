<?php

/*
CUSTOM FUNCTIONS
LAST EDITED: 02/06/2011
*/

function customlist($temp = '<li>%s</li>') {
	$html=NULL;
	$temp = explode('%s', $temp);
	$sql=mysql_query("SHOW TABLE STATUS LIKE 'custom%'");
	while($b = mysql_fetch_assoc($sql)) {
	$id=str_replace('custom', '', $b['Name']);
	$html.=$temp[0].'<a href="?c='.$id.'">'.$b['Comment'].'</a> '.$b['Rows'].$temp[1];
	}
	return $html;
}

function getcolumns($table) {
	$sql=mysql_query("SHOW FULL COLUMNS FROM $table WHERE Extra!='auto_increment'");
	$field=NULL;
	$i=0;
	while($b = mysql_fetch_assoc($sql)) {
		$field[$i]['name']=$b["Field"];
		if ($b["Comment"]=="") {
			$field[$i]['comment']=$b["Field"];
		}
		else {
			$field[$i]['comment']=$b["Comment"];
		}
		$i++;
	}
	return $field;
	//$column = count($field);
}

function getai($table) { // auto increment column
	return $ai=mysql_result(mysql_query("SHOW FULL COLUMNS FROM $table WHERE Extra='auto_increment'"), 0);
}

?>