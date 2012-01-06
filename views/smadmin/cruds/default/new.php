<?php if(count(get_included_files()) ==1) exit("Direct access not permitted.");

$write = NULL;

$cols = null;
foreach ($columns['cols'] as $key => $column ) {
	if($_POST["type-{$key}"]=='selectbox' && $_POST["selectamount-{$key}"]=='multiple') {
		$cols.= "		".'$dbset["'.$column["name"].'"] = json_encode($_POST["var'.$key.'"]);'."\n";
	}else
	if($_POST["type-{$key}"]=='date' && $_POST["datetype-$key"] == 'create') {
		$cols.= "		".'$dbset["'.$column["name"].'"] = time();'."\n";
	}
	else {
		$cols.= "		".'$dbset["'.$column["name"].'"] = $_POST["var'.$key.'"];'."\n";
	}
}

$write='<?php
	if(isset($_POST["var0"])) {
'.$cols.'
		if(isset($sm_crud_dbsave)) array_merge($dbset, $sm_crud_dbsave);
		if(!db::insert($table, $dbset)) {send($message[5], "error", "crud.php?c='.$name.'");} else {send($message[1], "success", "crud.php?c='.$name.'".(isset($_POST["saveandedit"]) ? "&p=edit&id=".mysql_insert_id(): ""));}
	}
?>
';

$write.="<form method='POST' action='?c={$name}&p=new' class='nice tabbed validate'>";

for($i=0; $i<count($columns['cols']); $i++) {
$attribute = null;
//if(isset($_GET["minvalue{$i}"])) { $attribute.= "min='{$_GET['minvalue{$i}']}' "; }
//if(isset($_GET["maxvalue{$i}"])) { $attribute.= "max='{$_GET['maxvalue{$i}']}' "; }
//if(isset($_POST["defaultvalue-{$i}"])) { $attribute.= "value='{$_POST["defaultvalue-$i"]}' "; }
if($_POST["type-$i"]=='textbox') {$classes= $_POST["texttype-$i"]; }
if(isset($_POST["required$i"])) {$attribute= "required,"; }else {$attribute = null;}
if($_POST["type-$i"]=='selectbox') {
$soptions = null;
if($_POST["selecttype-{$i}"]=='static') {
	foreach($_POST["selectstatic-{$i}"] as $row) {
		$soptions.= '			<option value="'.$row['key'].'">'.$row['value'].'</option>'."\n";
	}
	$soptions = rtrim($soptions, "\n");
}else
if($_POST["selecttype-$i"]=='dynamic') {
	$write.= "\n".'<?php $options = db::fetchquery("SELECT * FROM '.$_POST["select-fetchtable{$i}"].'"); ?>';
	$soptions ='	<?php
	foreach($options as $option) {
		echo "<option value=\'{$option["id"]}\'>{$option["'.$_POST["select-fetchtable-column{$i}"].'"]}</option>";
	}
	?>';
}
}
if    ($_POST["type-{$i}"]=='date' && $_POST["datetype-$i"] == 'date')	 	{$write.='
<div class="element">
	<label>'.$_POST["name-$i"].'</label>
	<input type="text" class="date validate['.$attribute.'custom[date]]" name="var'.$i.'">
</div>
';
}
elseif($_POST["type-{$i}"]=='image')	{$write.= '
<?php $imgupload=TRUE; ?> '."
<div class='element'>
	<label>{$_POST["name-$i"]}</label>
	<input type='text' name='var{$i}' id='var{$i}' class='image validate[{$attribute}custom[url]]'>
	<span class='button grey rightmargin pointer customimgbrowse' alt='var{$i}'>Browse from library</span>
	<span class='uploadimg'>Upload an image</span>
</div>
";}
elseif($_POST["type-{$i}"]=='file')	{$write.= '
<?php $imgupload=TRUE; ?> '."
<div class='element'>
	<label>{$_POST["name-$i"]}</label>
	<input type='text' name='var{$i}' id='var{$i}' class='file validate[{$attribute}custom[url]]' value='".'<?php echo $row['.$i.']; ?>'."'>"."
	<span class='button black rightmargin pointer customimgbrowse' alt='var{$i}'>Browse from library</span>
	<span class='button black uploadfile'>Upload a file</span>
</div>
";}
elseif($_POST["type-{$i}"]=='textarea') {$write.= '
<?php $textarea=TRUE; ?> '."
<div class='element'>
	<label>{$_POST["name-$i"]}</label>
	<textarea name='var{$i}' class='ckeditor validate[{$attribute}]' id='var{$i}'></textarea>
</div>
";}
elseif($_POST["type-{$i}"]=='textbox')  {$write.= "
<div class='element'>
	<label>{$_POST["name-$i"]}</label>
	<input type='text' class='text validate[{$attribute}".(($classes!=='') ? "custom[{$classes}]" : "")."]' name='var{$i}' id='var{$i}'>
</div>
";}
elseif($_POST["type-{$i}"]=='selectbox'){$write.= "
<div class='element'>
	<label>{$_POST["name-$i"]}</label>
	<select ".($_POST["selectamount-{$i}"]=='multiple' ? 'multiple': '')." name='var{$i}' class='validate[{$attribute}]' id='var{$i}'>
{$soptions}
	</select>
</div>
";}
}

$write.='
<input type="submit" name="saveandedit" class="margin topmargin btn black" value="Save and edit again">
<input type="submit" name="save" class="margin topmargin btn black" value="Save">
</form>
';
?>