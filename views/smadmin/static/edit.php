<form method="POST" action="" class="validate">
<div class='element'>
	<label>Title</label>
	<input type="text" class="validate[required]" name="title" value="<?php echo $static['title']; ?>">
</div>
<div class='element'>
	<label>Type</label>
	<select name='type' class="validate[required]" id="type" disabled>
			<option selected><?php echo $static['type']; ?></option>
	</select>
</div>
<div class='element'>
	<label>Category</label>
	<input type="text" class="" name="category" id="category" value="<?php echo $static['category']; ?>">
</div>
<div class='element'>
	<label>Description</label>
	<textarea name="description"><?php echo $static['description']; ?></textarea>
</div>
<div class="actions">
	<input type="submit" class="btn black" name="save" value="Save">
	<input type="submit" class="btn black" name="save" value="Save and edit again">
	<a href="?delete=<?php echo $id ?>" class="btn error" confirm="Do you really want to delete this field?">Delete this field</a>
</div>
</form>