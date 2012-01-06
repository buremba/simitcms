<form method="POST" action="" class="validate">
<div class='element'>
	<label>Title</label>
	<input type='text' class="validate[required]" name='title' id="title">
</div>
<div class='element'>
	<label>Type</label>
	<select  name='type' class="validate[required]" id="type">
		<?php
		foreach ($types as $type) {
			echo "<option value='{$type}'>{$type}</option>";
		}
		?>
	</select>
</div>
<div class='element'>
	<label>Category</label>
	<input type='text' class='' name='category'>
</div>
<div class='element'>
	<label>Description</label>
	<textarea name='description' id="description"></textarea>
</div>
<div class="actions">
	<input type="submit" class="btn black" name="save" value="Save">
	<input type="submit" class="btn black" name="save" value="Save and edit again">
</div>
</form>