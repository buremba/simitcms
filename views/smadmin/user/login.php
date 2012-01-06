 <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
<title>RGB CMS</title>
<link rel="stylesheet" type="text/css" href="<?php echo getadminurl('views/main.css'); ?>" />
</head>

<body>
	<div id="loginform" style="width:300px; margin:0 auto; margin-top:8em; border-radius:3px; background:#fff; box-shadow:0 0 6px 0 #C6C6C6;">
	<div id="nav" style="width:100%; background:black; color:#fff; border-radius:3px 3px 0 0; padding:7px 0; font-size:1.1em; text-indent:10px; box-shadow:0 0 6px 0 #C6C6C6;">Login</div>
	<div style="padding:5px;">
	<form method="post" action="index.php" />
		<div class='elements'>
			<label>kullanıcı adı</label>
			<input type='text' class='validate[required,]' name='loguser' id='loguser'>
		</div>
		<div class='elements'>
			<label>şifre</label>
			<input type='password' class='validate[required,]' name='logpass' id='logpass'>
		</div>
		<label>beni hatırla?</label>
		<input type="checkbox" name="remember" value="1" />
		<input type="submit" class="clear margin btn darkblue" value="giriş yap" />
	</form>
	</div>
	<?php echo @$errors ?>
	</div>
	<div style="clear:both;"></div>
</body>
</html>