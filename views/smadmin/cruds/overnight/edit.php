<?php
if(count(get_included_files()) ==1) exit("Direct access not permitted.");

	$id = (int) $_GET["id"];
	
	if(isset($_POST["var0"])) {
		$dbset["lang"] = $_POST["var0"];
		$dbset["name"] = $_POST["var1"];
		$dbset["price"] = $_POST["var2"];
		$dbset["de_discount"] = $_POST["var3"];
		$dbset["cl_discount"] = $_POST["var4"];

		if(isset($sm_crud_dbsave)) array_merge($dbset, $sm_crud_dbsave);
		if(!db::update($table, $dbset, array("id" => $id))) {send($message[6], "error", "crud.php?c=overnight");} else {send($message[2], "success", "crud.php?c=overnight".(isset($_POST["saveandedit"]) ? "&p=edit&id={$id}": ""));}
	}
	
$row = db::fetchone("SELECT lang, name, price, de_discount, cl_discount FROM $table WHERE id={$id}");
?>
<form method="POST" action="<?php if (isset($_GET["createnew"])) {echo "?c=overnight&p=new";}else {echo "?c=overnight&p=edit&id={$id}";} ?>" class="validate">
<div class="tabs">
	<div class="eachtab" title="General" id="general"> 
		<div class='element'>
			<label>Ekstra Gecelemenin Gözükeceği Dil</label>
			<select  name='var0' id='var0' class='validate[required,]'>
			<?php
			foreach(sm_dynamicarea('Languages') as $lang) {
				echo "<option ".(($row['lang']==$lang["key"]) ? "selected=true": "")." value='{$lang["key"]}'>{$lang["value"]}</option>";
			}
			?>
			</select>
		</div>

	<div class='element'>
		<label>Ekstra Gecelemenin İsmi</label>
		<input type='text' class='text validate[required,]' value='<?php echo $row['name']; ?>' name='var1' id='var1'>
	</div>
	
	</div>
	
	<div class="eachtab" title="Fiyat" id="price"> 
		<div class='element'>
			<label>Ekstra Gecelemenin Ücreti</label>
			<input type='text' class='text validate[required,custom[onlyNumberSp]]' value='<?php echo $row['price']; ?>' name='var2' id='var2'>
		</div>

		<div class='element'>
			<label>Kullanıcı İndirim Oranı</label>
			<input type='text' class='text validate[custom[onlyNumberSp]]' value='<?php echo $row['de_discount']; ?>' name='var3' id='var3'>
		</div>

		<div class='element'>
			<label>Bayi İndirim Oranı</label>
			<input type='text' class='text validate[custom[onlyNumberSp]]' value='<?php echo $row['cl_discount']; ?>' name='var4' id='var4'>
		</div>
	</div>
</div>
<div class="actions">
	<input type="submit" name="saveandedit" class="margin topmargin btn black" value="Save and edit again">
	<input type="submit" name="save" class="margin topmargin btn black" value="Save">
</div>
</form>
