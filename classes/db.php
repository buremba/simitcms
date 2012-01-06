<?php

if(!defined('SM_DEBUG')) {define('SM_DEBUG', (Kohana::DEVELOPMENT === Kohana::$environment));}

/*
if(!defined('SM_DEBUG')) {define('SM_DEBUG', (Kohana::DEVELOPMENT === Kohana::$environment));}
define('MYSQL_HOST', '');
define('MYSQL_USER', '');
define('MYSQL_PASS', '');
define('MYSQL_DBNAME', '');
*/
$options = Kohana::$config->load('database.default.connection');

mysql_connect($options['hostname'], $options['username'], $options['password']) or die("MySQL Error: " . mysql_error());
mysql_select_db($options['database']) or die ("Couldn't choose db ->". mysql_error());
mysql_query("SET NAMES 'utf8'");

class db {

public static function sendquery($query, $messageid = 0) {
	$sql = @mysql_query($query);
	if(SM_DEBUG) {
		$debug = debug_backtrace();
		if($sql == false) {
			setcookie('e_query', $query);
			setcookie('e_error', mysql_error());
			setcookie('e_debug', $debug[0]['file'].' -> '.$debug[0]['line'] );
		}else {
			setcookie('lastsql', $query.' -> '.basename($debug[0]['file']).' in '.$debug[0]['line']);
			return $sql;
		}
	}else {
		return $sql;
	}
}

public static function fetchquery($query) {
	$sql = @mysql_query($query);
	if(SM_DEBUG) {
		$debug = debug_backtrace();
		if($sql == false) {
			//var_dump($query);
			setcookie('e_query', $query);
			setcookie('e_error', mysql_error());
			setcookie('e_debug', $debug[0]['file'].' -> '.$debug[0]['line'] );
		}else {
			setcookie('lastsql', $query.' -> '.basename($debug[0]['file']).' in '.$debug[0]['line']);
			while($row = mysql_fetch_assoc($sql)) {$array[] = $row;}
			if (isset($array)) {return $array;}
			else {return array();}
		}
	}else {
		while($row = mysql_fetch_assoc($sql)) {$array[] = $row;}
		if (isset($array)) {return $array;}
		else {return array();}
	}
}

public static function fetchone($query) {
	$sql = @mysql_query($query);
	if(SM_DEBUG) {
		$debug = debug_backtrace();
		if($sql == false) {
			setcookie('e_query', $query);
			setcookie('e_error', mysql_error());
			setcookie('e_debug', $debug[0]['file'].' -> '.$debug[0]['line'] );
		}else {
			setcookie('lastsql', $query.' -> '.basename($debug[0]['file']).' in '.$debug[0]['line']);
			$row = mysql_fetch_assoc($sql);
			return $row;
		}
	}else {
		$row = mysql_fetch_assoc($sql);
		$row = $row[0];
		return $row;
	}
}

public static function getvalue($query) {
	$sql = @mysql_query($query);
	if(SM_DEBUG) {
		$debug = debug_backtrace();
		if($sql == false) {
			setcookie('e_query', $query);
			setcookie('e_error', mysql_error());
			setcookie('e_debug', $debug[0]['file'].' -> '.$debug[0]['line'] );
		}else {
			setcookie('lastsql', $query.' -> '.basename($debug[0]['file']).' in '.$debug[0]['line']);
			$row = mysql_fetch_row($sql);
			$row = $row[0];
			return $row;
		}
	}else {
		$row = mysql_fetch_row($sql);
		$row = $row[0];
		return $row;
	}
}

public static function insert($table, $array) { // $array = array('column' => 'value');
	$query = null;
	$query.= "INSERT INTO {$table} ";
	foreach ($array as $k => $a) {$columns[] = '`'.$k.'`'; $values[] = '"'.mysql_real_escape_string($a).'"';}
	$query.= ' ('.implode(', ', $columns).') ';
	$query.= 'VALUES ('.implode(', ', $values).')';
	return self::sendquery($query);
}

public static function update($table, $array, $where) {
	$query = null;
	$query.= "UPDATE {$table} ";
	foreach ($array as $k => $a) {$keyvalues[] = "`{$k}` = '".mysql_real_escape_string($a)."'";}
	$query.= 'SET '.implode(', ', $keyvalues).' WHERE ';
	foreach ($where as $z => $b) {$wheres[] = "`{$z}` = '".mysql_real_escape_string($b)."'";}
	$query.= implode(' AND ', $wheres);
	return self::sendquery($query);
}

public static function delete($table, $array, $batchdel = false) {
	$query = null;
	$query.= "DELETE FROM {$table} ";
	foreach ($array as $k => $a) {
		if(is_array($a)) {
			foreach ($a as $b) {$temp[] = "`{$k}` = '".mysql_real_escape_string($b)."'";}
			$keyvalues[] = implode('OR', $temp);
			$batchdel = true;
		}else {
			$keyvalues[] = "`{$k}` = '".mysql_real_escape_string($a)."'";
		}
	}
	$query.= 'WHERE '.implode('AND', $keyvalues).($batchdel ? '' : 'LIMIT 1');
	$action = self::sendquery($query);
	if($action && mysql_affected_rows()>0) {return true;}else {return false;}
}

public static function getcolumns($table) {
	$columns = self::fetchquery("SHOW FULL COLUMNS FROM $table");
	
	if (!is_array($columns)) return array();
	foreach($columns as $key => $column) {
		if($column['Extra']=='auto_increment') {$array['ai'] = $column['Field'];} else {
		$array['cols'][$key-1]['name'] = $column['Field'];
		$array['cols'][$key-1]['comment'] = $column['Comment'];
		$array['cols'][$key-1]['type'] = $column['Type'];
		}
	}
	$array['colnum'] = count($array['cols']);
	return $array;
}

public static function findfreetable($name) {
$formnumber=0;
$exist = 1;
	while($exist > 0) {
		$formnumber++;
		$table = $name.$formnumber;
		$sql = $this->sendquery("show tables like '".$table."';");
		$exist = mysql_num_rows($sql);
	}
	return $table;
}

function createtable ($table, $count, $comments) {
$columns = NULL;
for ($i = 1; $i <= $count; $i++) {
	$columns.= "`$i` text NOT NULL COMMENT '".mysql_real_escape_string($comments[$i])."',";
}
self::sendquery("
CREATE TABLE `{$table}` (
 `id` int(10) NOT NULL AUTO_INCREMENT,
 {$columns}
 `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 PRIMARY KEY (`id`),
 UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8
");
}

}
?>