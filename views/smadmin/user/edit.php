<form method="POST" action="?edituser=<?php echo intval($_GET['edituser']); ?>">
<div class='element'>
	<label>Username</label>
	<input type="text" id="username" type="text" value="<?php echo $inf[$user->fields['login']]; ?>" name="username">
	<span class="desc"></span>
</div>
<div class='element'>
	<label>E-mail</label>
	<input type="text" id="mail" type="text" value="<?php echo $inf[$user->fields['mail']]; ?>" name="mail">
	<span class="desc"></span>
</div>
<div class='element'>
	<label>User Group</label>
	<select name="usergroup" id="usergroup">
	<?php foreach($usergroups as $group) : ?>
		<option <?php if($inf['user_group']== $group['setting_id']) echo "selected=true"; ?> value="<?php echo $group['setting_id']; ?>"><?php echo $group['setting_value']; ?></option>
	<?php endforeach; ?>
	</select>
</div>
<div class='element'>
	<label>New Password</label>
	<input type="text" id="password1" type="password" name="password1">
	<span class="desc"></span>
</div>

<input type="submit" value="Update User" class="clear margin btn black">
</form>