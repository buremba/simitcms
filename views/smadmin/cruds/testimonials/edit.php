<?php
if(count(get_included_files()) ==1) exit("Direct access not permitted.");

	$id = (int) $_GET["id"];
	
	if(isset($_POST["var0"])) {
		$dbset["lang"] = $_POST["var0"];
		$dbset["person"] = $_POST["var1"];
		$dbset["date"] = $_POST["var2"];
		$dbset["description"] = $_POST["var3"];
		$dbset["meta_key"] = $_POST["var4"];
		$dbset["meta_desc"] = $_POST["var5"];

		if(isset($sm_crud_dbsave)) array_merge($dbset, $sm_crud_dbsave);
		if(!db::update($table, $dbset, array("id" => $id))) {send($message[6], "error", "crud.php?c=testimonials");} else {send($message[2], "success", "crud.php?c=testimonials".(isset($_POST["saveandedit"]) ? "&p=edit&id={$id}": ""));}
	}
	
$row = db::fetchone("SELECT lang, person, date, description, meta_key, meta_desc FROM $table WHERE id={$id}");
?>
<form method="POST" action="<?php if (isset($_GET["createnew"])) {echo "?c=testimonials&p=new";}else {echo "?c=testimonials&p=edit&id={$id}";} ?>" class="validate">
<div class="tabs">
	<div class="eachtab" title="Genel Bilgiler" id="general">
		<div class='element'>
			<label>Dil</label>
			<select  name='var0' id='var0' class='validate[required,]'>
			<?php
			foreach(sm_dynamicarea('Languages') as $lang) {
				echo "<option ".(($row['lang']==$lang["key"]) ? "selected=true": "")." value='{$lang["key"]}'>{$lang["value"]}</option>";
			}
			?>
			</select>
		</div>

		<div class='element'>
			<label>Müşterinin Adı-Soyadı</label>
			<input type='text' class='text validate[required,]' value='<?php echo $row["person"]; ?>' name='var1' id='var1'>
		</div>

		<div class="element">
			<label>Tarih</label>
			<input type="text" class="date validate[required,custom[date]]" value="<?php echo $row["date"]; ?>" name="var2" id="var2">
		</div>

		<?php $textarea=TRUE; ?> 
		<div class='element'>
			<label>Açıklama</label>
			<textarea name='var3' id='var3' class='ckeditor validate[]'><?php echo $row["description"]; ?></textarea>
		</div>
	</div>
	
	<div class="eachtab" title="Seo" id="seo">
		<div class='element'>
			<label>Meta Anahtarları</label>
			<input type='text' class='text validate[]' value='<?php echo $row["meta_key"]; ?>' name='var4' id='var4'>
		</div>

		<?php $textarea=TRUE; ?> 
		<div class='element'>
			<label>Meta Açıklaması</label>
			<textarea name='var5' id='var5' class='ckeditor validate[]'><?php echo $row["meta_desc"]; ?></textarea>
		</div>
	</div>
</div>

<div class="actions">
	<input type="submit" name="saveandedit" class="margin topmargin btn black" value="Save and edit again">
	<input type="submit" name="save" class="margin topmargin btn black" value="Save">
</div>
</form>
