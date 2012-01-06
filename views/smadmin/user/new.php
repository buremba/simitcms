<form method="POST" action="?newuser">
<div class='element'>
	<label>Username</label>
	<input type="text" id="username" type="text" name="username">
	<span class="desc"></span>
</div>
<div class='element'>
	<label>E-mail</label>
	<input type="text" id="mail" type="text" name="mail">
	<span class="desc"></span>
</div>
<div class='element'>
	<label>Password</label>
	<input type="text" id="password1" type="password" name="password1">
	<span class="desc"></span>
</div>
<div class='element'>
	<label>Password (again)</label>
	<input type="text" id="password2" type="password" name="password2">
	<span class="desc"></span>
</div>
<div class='element'>
	<label>User Group</label>
	<select name="usergroup" id="usergroup">
	<?php foreach($usergroups as $group) : ?>
		<option value="<?php echo $group['setting_id']; ?>"><?php echo $group['setting_value']; ?></option>
	<?php endforeach; ?>
	</select>
</div>

<input type="submit" value="Create User" class="clear margin btn black">
</form>