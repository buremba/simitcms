<?php if(isset($_GET['new'])) : ?>
<form method="POST" action="" class="validate">
<div class='element'>
	<label>Title</label>
	<input type='text' class="validate[required]" name='title' id="title">
</div>
<div class='element'>
	<label>Type</label>
	<select  name='type' class="validate[required]" id="type">
		<?php
		$types = get_class_methods('smt_static');
		array_shift($types);
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

<?php elseif(isset($_GET['edit'])) : ?>

<?php $id = (int) $_GET['edit']; $static = db::fetchone("SELECT * FROM static WHERE id={$id}");?>
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
	<a href="?delete=<?php echo intval($_GET['edit']); ?>" class="btn error" confirm="Do you really want to delete this field?">Delete this field</a>
</div>
</form>

<?php else: ?>

<form method="POST" action="static.php?list=<?php if(isset($_GET['list'])) echo intval($_GET['list']); ?>" class="validate">
<?php
$static = new smt_static('adminpanel'); 
foreach ($statics as $row) {
	if(in_array($row['type'], get_class_methods($static))) {
		echo $static->$row['type']($row['id'], $row['title'], unserialize($row['content']), $row['description']);
	}else {
		echo<<<html
	<div class='element configurable' id="{$row['id']}">
		Undefined type "{$row['type']}" for "{$row['title']}"!
	</div>
html;
	}

}
?>
<input type="submit" value="Save" class="margin btn black" name="savestatics">
</form>
<?php endif; ?>