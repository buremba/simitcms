<?php
if(count(get_included_files()) ==1) exit("Direct access not permitted.");

	if(isset($_POST["var0"])) {
		$dbset["lang"] = $_POST["var0"];
		$dbset["person"] = $_POST["var1"];
		$dbset["date"] = $_POST["var2"];
		$dbset["description"] = $_POST["var3"];
		$dbset["meta_key"] = $_POST["var4"];
		$dbset["meta_desc"] = $_POST["var5"];

		if(isset($sm_crud_dbsave)) array_merge($dbset, $sm_crud_dbsave);
		if(!db::insert($table, $dbset)) {send($message[5], "error", "crud.php?c=testimonials");} else {send($message[1], "success", "crud.php?c=testimonials".(isset($_POST["saveandedit"]) ? "&p=edit&id=".mysql_insert_id(): ""));}
	}
?>
<form method='POST' action='?c=testimonials&p=new' class='nice tabbed validate'>
<div class="tabs">
	<div class="eachtab" title="Genel Bilgiler" id="general">
		<div class='element'>
			<label>Dil</label>
			<select  name='var0' class='validate[required,]' id='var0'>
			<?php
			foreach(sm_dynamicarea('Languages') as $lang) {
				echo "<option value='{$lang["key"]}'>{$lang["value"]}</option>";
			}
			?>
			</select>
		</div>

		<div class='element'>
			<label>Müşterinin Adı-Soyadı</label>
			<input type='text' class='text validate[required,]' name='var1' id='var1'>
		</div>

		<div class="element">
			<label>Tarih</label>
			<input type="text" class="date validate[required,custom[date]]" name="var2">
		</div>

		<?php $textarea=TRUE; ?> 
		<div class='element'>
			<label>Açıklama</label>
			<textarea name='var3' class='validate[]' id='var3'></textarea>
		</div>
	</div>

	<div class="eachtab" title="Seo" id="seo">
		<div class='element'>
			<label>Meta Anahtarları</label>
			<input type='text' class='text validate[]' name='var4' id='var4'>
		</div>

		<?php $textarea=TRUE; ?> 
		<div class='element'>
			<label>Meta Açıklaması</label>
			<textarea name='var5' class='ckeditor validate[]' id='var5'></textarea>
		</div>
	</div>
</div>

<div class="actions">
	<input type="submit" name="saveandedit" class="margin topmargin btn black" value="Save and edit again">
	<input type="submit" name="save" class="margin topmargin btn black" value="Save">
</div>
</form>
