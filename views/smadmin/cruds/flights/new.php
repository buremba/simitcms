<?php
if(count(get_included_files()) ==1) exit("Direct access not permitted.");

	if(isset($_POST["var0"])) {
		$dbset["lang"] = $_POST["var0"];
		$dbset["name"] = $_POST["var1"];
		$dbset["price"] = $_POST["var2"];
		$dbset["de_discount"] = $_POST["var3"];
		$dbset["cl_discount"] = $_POST["var4"];

		if(isset($sm_crud_dbsave)) array_merge($dbset, $sm_crud_dbsave);
		if(!db::insert($table, $dbset)) {send($message[5], "error", "crud.php?c=flights");} else {send($message[1], "success", "crud.php?c=flights".(isset($_POST["saveandedit"]) ? "&p=edit&id=".mysql_insert_id(): ""));}
	}
?>
<form method='POST' action='?c=flights&p=new' class=' validate'>
<div class="tabs">
	<div class="eachtab" title="Genel Bilgiler" id="general">
		<div class='element'>
			<label for="var0">Uçuş Dili</label>
			<select  name='var0' class='validate[required,]' id='var0'>
			<?php foreach(sm_dynamicarea('Languages') as $option) { echo "<option value='{$option["key"]}'>{$option["value"]}</option>"; } ?>
			</select>
		</div>

		<div class='element'>
			<label for="var1">Uçuş İsmi</label>
			<input type='text' class='text validate[required,]' name='var1' id='var1'>
		</div>
	</div>

	<div class="eachtab" title="Fiyat" id="price"> 
		<div class='element'>
			<label for="var2">Uçuş Fiyatı</label>
			<input type='text' class='text validate[required,custom[onlyNumberSp]]' name='var2' id='var2'>
		</div>

		<div class='element'>
			<label for="var3">Uçuş Kullanıcı Promosyon Yüzdesi</label>
			<input type='text' class='text validate[custom[onlyNumberSp]]' name='var3' id='var3'>
		</div>

		<div class='element'>
			<label for="var4">Uçuş Bayi Promosyon Yüzdesi</label>
			<input type='text' class='text validate[custom[onlyNumberSp]]' name='var4' id='var4'>
		</div>
	</div>
</div>

<div class="actions">
	<input type="submit" name="saveandedit" class="margin topmargin btn black" value="Save and edit again">
	<input type="submit" name="save" class="margin topmargin btn black" value="Save">
</div>

</form>
