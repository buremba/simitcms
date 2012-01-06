<?php if(count(get_included_files()) ==1) exit("Direct access not permitted.");

	$id = (int) $_GET["id"];
	
	if(isset($_POST["var0"])) {
		$dbset["name"] = $_POST["var0"];
		$dbset["authority"] = $_POST["var1"];
		$dbset["email"] = $_POST["var2"];
		$dbset["password"] = $user->encodepass($_POST["var3"]);
		$dbset["country"] = $_POST["var4"];
		$dbset["city"] = $_POST["var5"];
		$dbset["webpage"] = $_POST["var6"];
		$dbset["hotel_mail"] = $_POST["var7"];
		$dbset["phone"] = $_POST["var8"];
		$dbset["commision"] = $_POST["var9"];

		if(isset($sm_crud_dbsave)) array_merge($dbset, $sm_crud_dbsave);
		if(!db::update($table, $dbset, array("id" => $id))) {send($message[6], "error", "crud.php?c=clients");} else {send($message[2], "success", "crud.php?c=clients".(isset($_POST["saveandedit"]) ? "&p=edit&id={$id}": ""));}
	}
	
$row = db::fetchone("SELECT name, authority, email, password, country, city, webpage, hotel_mail, phone, commision FROM $table WHERE id={$id}");
?>
<form method="POST" action="<?php if (isset($_GET["createnew"])) {echo "?c=clients&p=new";}else {echo "?c=clients&p=edit&id={$id}";} ?>" class="validate">
<div class="tabs">
	<div class="eachtab" title="Genel Bilgiler" id="general">
		<div class='element'>
			<label>Bayi ismi</label>
			<input type='text' class='text validate[required,]' value='<?php echo $row['name']; ?>' name='var0' id='var0'>
		</div>

		<div class='element'>
			<label>Bayi yetkilisi</label>
			<input type='text' class='text validate[required,]' value='<?php echo $row['authority']; ?>' name='var1' id='var1'>
		</div>

		<div class='element'>
			<label>Yetkili e-posta adresi</label>
			<input type='text' class='text validate[required,custom[email]]' value='<?php echo $row['email']; ?>' name='var2' id='var2'>
		</div>

		<div class='element'>
			<label>Yetkili şifresi</label>
			<input type='text' class='text validate[required,custom[onlyLetterNumber]]' name='var3' id='var3'>
		</div>

		<div class='element'>
			<label>Bayinin bulunduğu ülke</label>
			<input type='text' class='text validate[required,]' value='<?php echo $row['country']; ?>' name='var4' id='var4'>
		</div>

		<div class='element'>
			<label>Bayinin bulunduğu şehir</label>
			<input type='text' class='text validate[]' value='<?php echo $row['city']; ?>' name='var5' id='var5'>
		</div>

		<div class='element'>
			<label>Bayinin web adresi</label>
			<input type='text' class='text validate[custom[url]]' value='<?php echo $row['webpage']; ?>' name='var6' id='var6'>
		</div>

		<div class='element'>
			<label>Otelin e-posta adresi</label>
			<input type='text' class='text validate[custom[email]]' value='<?php echo $row['hotel_mail']; ?>' name='var7' id='var7'>
		</div>

		<div class='element'>
			<label>Otelin Telefon Numarası</label>
			<input type='text' class='text validate[]' value='<?php echo $row['phone']; ?>' name='var8' id='var8'>
		</div>

		<div class='element'>
			<label>Komisyon Değeri</label>
			<input type='text' class='text validate[required,custom[onlyNumberSp]]' value='<?php echo $row['commision']; ?>' name='var9' id='var9'>
		</div>
	</div>
</div>

<div class="actions">
	<input type="submit" name="saveandedit" class="margin topmargin btn black" value="Save and edit again">
	<input type="submit" name="save" class="margin topmargin btn black" value="Save">
</div>
</form>
