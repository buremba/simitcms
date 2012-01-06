<form method="POST" action="" class="validate">
<?php
foreach ($statics as $row) {
	if(in_array($row['type'], $types)) {
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