<form method="POST" action="?usergroups&edit=<?php echo intval($_GET['edit']); ?>">
<div class='element'>
	<label>Group Name</label>
	<input type="text" id="groupname" type="text" value="<?php echo $groupname; ?>" name="groupname">
	<span class="desc"></span>
</div>
<input type="submit" value="Update User Group" class="clear margin btn black">
</form>