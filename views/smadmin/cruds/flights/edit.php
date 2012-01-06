<?php if(count(get_included_files()) ==1) exit("Direct access not permitted.");

	$id = (int) $_GET["id"];
	
	if(isset($_POST["var0"])) {
		$dbset["lang"] = $_POST["var0"];
		$dbset["name"] = $_POST["var1"];
		$dbset["price"] = $_POST["var2"];
		$dbset["de_discount"] = $_POST["var3"];
		$dbset["cl_discount"] = $_POST["var4"];

		if(isset($sm_crud_dbsave)) array_merge($dbset, $sm_crud_dbsave);
		if(!db::update($table, $dbset, array('id' => $id))) {send($message[6], "error", "crud.php?c=flights");} else {send($message[2], "success", "crud.php?c=flights".(isset($_POST["saveandedit"]) ? "&p=edit&id={$id}": ""));}
	}
	
$row = db::fetchone("SELECT * FROM $table WHERE id={$id}");
?>
<form method="POST" action="<?php if (isset($_GET["createnew"])) {echo "?c=flights&p=new";}else {echo "?c=flights&p=edit&id={$id}";} ?>" class="validate">
<div class="tabs">
	<div class="eachtab" title="Genel Bilgiler" id="general">
		<div class='element'>
			<label>Uçuş Dili</label>
			<select name='var0' id='var0' class='validate[required,]'>
			<?php
			foreach(sm_dynamicarea('Languages') as $option) {
				echo "<option ".(($row['lang']==$option["key"]) ? "selected=true": "")." value='{$option["key"]}'>{$option["value"]}</option>";
			}
			?>
			</select>
		</div>

		<div class='element'>
			<label>Uçuş İsmi</label>
			<input type='text' class='text validate[]' value='<?php echo $row['name']; ?>' name='var1' id="var1">
		</div>
	</div>

	<div class="eachtab" title="Fiyat" id="price"> 
		<div class='element'>
			<label>Uçuş Fiyatı</label>
			<input type='text' class='text validate[custom[onlyNumberSp]]' value='<?php echo $row['price']; ?>' name='var2' id="var2">
		</div>

		<div class='element'>
			<label>Uçuş Kullanıcı Promosyon Yüzdesi</label>
			<input type='text' class='text validate[custom[onlyNumberSp]]' value='<?php echo $row['de_discount']; ?>' name='var3' id="var3">
		</div>

		<div class='element'>
			<label>Uçuş Bayi Promosyon Yüzdesi</label>
			<input type='text' class='text validate[custom[onlyNumberSp]]' value='<?php echo $row['cl_discount']; ?>' name='var4' id="var4">
		</div>
	</div>
</div>

<div class="actions">
	<input type="submit" name="saveandedit" class="margin topmargin btn black" value="Save and edit again">
	<input type="submit" name="save" class="margin topmargin btn black" value="Save">
</div>
</form>
