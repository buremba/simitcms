<form method="POST" action="?profile">
<div class='element'>
	<label>Username</label>
	<input type="text" id="username" type="text" name="username" value="<?php echo $username; ?>">
	<span class="desc"></span>
</div>
<div class='element'>
	<label>E-mail</label>
	<input type="text" id="mail" type="text" name="mail" value="<?php echo $email; ?>">
	<span class="desc"></span>
</div>
<div class='element'>
	<label>Old Password</label>
	<input type="text" id="oldpassword" type="password" name="oldpassword">
	<span class="desc"></span>
</div>
<div class='element'>
	<label>New Password</label>
	<input type="text" id="newpassword" type="password" name="newpassword">
	<span class="desc"></span>
</div>
<input type="submit" value="Update my profile" class="clear margin btn black">
</form>