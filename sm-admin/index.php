<?php
$nonaccess=TRUE;
include_once('../config.php');
$login=TRUE;
$dir=FALSE;
if($user->is_loaded()) {
header('Location: admin/blockadmin.php');
}else {
	$ic.=<<<html
	<div id="nav" style="margin-top:10em; width:220px;">Login</div>
	<div id="content" style="width:225px; min-height:150px; padding-left:5px;">
	<form method="post" action="index.php" />
	<label>kullanıcı adı:</label>
	<input type="text" name="loguser" style="width:190px;" />
	<label>şifre:</label>
	<input type="password" name="logpass" style="width:190px;" />
	<label>beni hatırla?</label>
	<input type="checkbox" name="remember" value="1" />
	<input type="submit" value="giriş yap" />
	</form>
	</div>
html;
	if(isset($_POST["loguser"])) {
	if (isset($_POST['remember']) && $_POST['remember']==1) {$remember=TRUE;} else {$remember=FALSE;}
	$login = $user->login($_POST['loguser'],$_POST['logpass'], $remember);
	if($login==FALSE) {$ic.= 'Yanlış kullanıcı adı veya şifre.';}
	else {header('Location: admin/blockadmin.php');}
	}
}
include('template.php');
?>