<?php if(count(get_included_files()) ==1) exit("Direct access not permitted.");

$write = NULL;

$cols = null;
foreach ($columns['cols'] as $key => $column ) {
	if($_POST["type-{$key}"]=='selectbox' && $_POST["selectamount-{$key}"]=='multiple') {
		$cols.= "		".'$dbset["'.$column["name"].'"] = json_encode($_POST["var'.$key.'"]);'."\n";
	}else
	if($_POST["type-{$key}"]=='date' && $_POST["datetype-$key"] == 'update') {
		$cols.= "		".'$dbset["'.$column["name"].'"] = time();'."\n";
	}
	else {
		$cols.= "		".'$dbset["'.$column["name"].'"] = $_POST["var'.$key.'"];'."\n";
	}
	$colarray[] = $column["name"];
}

$write='<?php
	$id = (int) $_GET["id"];
	
	if(isset($_POST["var0"])) {
'.$cols.'
		if(isset($sm_crud_dbsave)) array_merge($dbset, $sm_crud_dbsave);
		if(!db::update($table, $dbset, array("id" => $id))) {send($message[6], "error", "crud.php?c='.$name.'");} else {send($message[2], "success", "crud.php?c='.$name.'".(isset($_POST["saveandedit"]) ? "&p=edit&id={$id}": ""));}
	}
	
';

$write.='$row = db::fetchone("SELECT '.implode(', ', $colarray).' FROM $table WHERE '.$columns['ai'].'={$id}");
?>
';

$write.='<form method="POST" action="<?php if (isset($_GET["createnew"])) {echo "?c='.$name.'&p=new";}else {echo "?c='.$name.'&p=edit&id={$id}";} ?>" class="validate">';

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
		$soptions.= '			<option <?php if ($row["'.$colarray[$i].'"]=="'.$row['key'].'") echo "selected=true"; ?> value="'.$key.'">'.$row['value'].'</option>'."\n";
	}
	$soptions = rtrim($soptions, "\n");
}else
if($_POST["selecttype-{$i}"]=='dynamic') {
	$write.= "\n".'<?php $options = db::fetchquery("SELECT * FROM '.$_POST["select-fetchtable{$i}"].'"); ?>';
	$soptions ='	<?php
	foreach($options as $option) {
		echo "<option ".(($row["'.$colarray[$i].'"]==$option["id"]) ? "selected=true": "")." value=\'{$option["id"]}\'>{$option["'.$_POST["select-fetchtable-column{$i}"].'"]}</option>";
	}
	?>';
}
}

if    ($_POST["type-{$i}"]=='date' && $_POST["datetype-$i"] == 'date')	 	{$write.='
<div class="element">
	<label>'.$_POST["name-$i"].'</label>
	<input type="text" class="date validate['.$attribute.'custom[date]]" value="<?php echo $row["'.$colarray[$i].'"]; ?>" name="var'.$i.'" id="var'.$i.'">
</div>
';
}
elseif($_POST["type-{$i}"]=='image')	{$write.= '
<?php $imgupload=TRUE; ?> '."
<div class='element'>
	<label>{$_POST["name-$i"]}</label>
	<input type='text' name='var{$i}' id='var{$i}' class='image validate[{$attribute}custom[url]]' value='".'<?php echo $row["'.$colarray[$i].'"]; ?>'."'>"."
	<span class='button grey rightmargin pointer' id='customimgbrowse' alt='var{$i}'>Browse from library</span>
	<span class='uploadimg'>Upload an image</span>
</div>
";}
elseif($_POST["type-{$i}"]=='file')	{$write.= '
<?php $imgupload=TRUE; ?> '."
<div class='element'>
	<label>{$_POST["name-$i"]}</label>
	<input type='text' name='var{$i}' id='var{$i}' class='file validate[{$attribute}custom[url]]' value='".'<?php echo $row["'.$colarray[$i].'"]; ?>'."'>"."
	<span class='button black rightmargin pointer' id='customimgbrowse' alt='var{$i}'>Browse from library</span>
	<span class='button black uploadfile'>Upload a file</span>
</div>
";}
elseif($_POST["type-{$i}"]=='textarea') {$write.= '
<?php $textarea=TRUE; ?> '."
<div class='element'>
	<label>{$_POST["name-$i"]}</label>
	<textarea name='var{$i}' id='var{$i}' class='ckeditor validate[{$attribute}]'>".'<?php echo $row["'.$colarray[$i].'"]; ?>'."</textarea>
</div>
";}
elseif($_POST["type-{$i}"]=='textbox')  {$write.= "
<div class='element'>
	<label>{$_POST["name-$i"]}</label>
	<input type='text' class='text validate[{$attribute}".(($classes!=='') ? "custom[{$classes}]" : "")."]' value='".'<?php echo $row["'.$colarray[$i].'"]; ?>'."' name='var{$i}' id='var{$i}'>
</div>
";}
elseif($_POST["type-{$i}"]=='selectbox'){$write.= "
<div class='element'>
	<label>{$_POST["name-$i"]}</label>
	<select ".($_POST["selectamount-{$i}"]=='multiple' ? 'multiple': '')." name='var{$i}' id='var{$i}' class='validate[{$attribute}]'>
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