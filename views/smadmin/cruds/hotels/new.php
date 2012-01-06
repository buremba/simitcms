<?php
if(count(get_included_files()) ==1) exit("Direct access not permitted.");

	if(isset($_POST["var0"])) {
		$dbset["country"] = $_POST["var0"];
		$dbset["city"] = $_POST["var1"];
		$dbset["category"] = $_POST["var2"];
		$dbset["star"] = $_POST["var3"];
		$dbset["name"] = $_POST["var4"];
		$dbset["images"] = $_POST["var5"];
		$dbset["email"] = $_POST["var6"];
		$dbset["website"] = $_POST["var7"];
		$dbset["phone"] = $_POST["var8"];
		$dbset["gmap"] = $_POST["var9"];

		if(isset($sm_crud_dbsave)) array_merge($dbset, $sm_crud_dbsave);
		if(!db::insert($table, $dbset)) {send($message[5], "error", "crud.php?c=hotels");} else {send($message[1], "success", "crud.php?c=hotels".(isset($_POST["saveandedit"]) ? "&p=edit&id=".mysql_insert_id(): ""));}
	}
?>
<form method='POST' action='?c=hotels&p=new' class='nice tabbed validate'>
<div class="tabs">
	<div class="eachtab"  title="Genel Bilgiler" id="state">
		<div class='element'>
			<label>Otelin Bulunduğu Ülke</label>
			<input type='text' class='text validate[required,custom[onlyLetterNumber]]' name='var0' id='var0'>
		</div>

		<div class='element'>
			<label>Otelin Bulunduğu Şehir</label>
			<input type='text' class='text validate[required]' name='var1' id='var1'>
		</div>
		<div class='element'>
			<label>Otelin Kategorisi</label>
			<select  name='var2' class='validate[required,]' id='var2'>
					<option value="superior">Superior</option>
					<option value="lujo">Lujo</option>
					<option value="delux">Delux</option>
			</select>
		</div>
		
		<div class='element'>
			<label>Otelin Yıldız Sayısı</label>
			<select  name='var3' class='validate[required,]' id='var3'>
					<option value="3">3***</option>
					<option value="4">4****</option>
					<option value="4+">4****+</option>
					<option value="5">5*****</option>
					<option value="5+">5*****+</option>
					<option value="crucero">Crucero</option>
					<option value="butik">Butik</option>
			</select>
		</div>
		<div class='element'>
			<label>Otelin Adı</label>
			<input type='text' class='text validate[required,]' name='var4' id='var4'>
			<span class="desc">dasdsad</span>
		</div>
		
		<?php $options = db::fetchquery("SELECT * FROM media"); ?>
		<div class='element'>
			<label>Otelin Fotoğraflarının Bulunduğu Galeri</label>
			<select  name='var5' class='validate[]' id='var5'>
			<?php
			foreach($options as $option) {
				echo "<option value='{$option["id"]}'>{$option["from"]}</option>";
			}
			?>
			</select>
		</div>

		<div class='element'>
			<label>Otelin E-posta Adresi</label>
			<input type='text' class='text validate[custom[email]]' name='var6' id='var6'>
		</div>

		<div class='element'>
			<label>Otelin Web Adresi</label>
			<input type='text' class='text validate[custom[url]]' name='var7' id='var7'>
		</div>

		<div class='element'>
			<label>Otelin Telefon Numarası</label>
			<input type='text' class='text validate[]' name='var8' id='var8'>
		</div>
		<div class='element'>
			<label>Otelin Gmap Adresi</label>
			<textarea class='text validate[]' name='var9' id='var9'></textarea>
		</div>
	</div>
	</div>
</div>

<div class="actions">
	<input type="submit" name="saveandedit" class="margin topmargin btn black" value="Save and edit again">
	<input type="submit" name="save" class="margin topmargin btn black" value="Save">
</div>
</form>
