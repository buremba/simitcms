<?php

Route::set('smcruds', 'sm-admin/cruds(/<controller>(/<action>(/<id>)))')
	->defaults(array(
		'directory'	 => 'smadmin/cruds',
		'action'     => 'index',
	));

function sm_crudarray($id) {
$sql = db::sendquery("SELECT * FROM custom$id ORDER BY id ASC LIMIT 0,100");
while($c = mysql_fetch_assoc($sql)) {
$crud[] = $c;
}
return $crud;
}