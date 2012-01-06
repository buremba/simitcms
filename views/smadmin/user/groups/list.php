<a class="btn orange" href="?usergroups&new">Create new user group</a>
	<table class="nice" cellspacing="0">
	<tr>
		<th>Groups Name</th>
		<th>Count of Members</th>
		<th></th>
	</tr>
	<?php foreach($usergroups as $u) : ?>
		<tr id="<?php echo $u['setting_id']; ?>">
		<td><?php echo $u['setting_value']; ?></td>
		<td><?php echo $u['count']; ?></td>
		<td>
		<a href="user.php?usergroups&edit=<?php echo $u['setting_id']; ?>">
			<span class="ui-icon ui-icon-pencil"></span>
		</a>
		<a href="user.php?usergroups&del=<?php echo $u['setting_id']; ?>" onclick="if(!confirm('Satýr geri dönüþümsüz bir þekilde silinecek, devam etmek istiyor musunuz?')) {return false;}">
			<span class="ui-icon ui-icon-trash"></span>
		</a>
		</td>
		</tr>
	<?php endforeach; ?>