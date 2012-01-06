<?php
if(count(get_included_files()) ==1) exit("Direct access not permitted.");

	$id = (int) $_GET["id"];
	
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
		if(!db::update($table, $dbset, array("id" => $id))) {send($message[6], "error", "crud.php?c=hotels");} else {send($message[2], "success", "crud.php?c=hotels".(isset($_POST["saveandedit"]) ? "&p=edit&id={$id}": ""));}
	}
	
$row = db::fetchone("SELECT country, city, category, star, name, images, email, website, phone, gmap FROM $table WHERE id={$id}");
?>
<form method="POST" action="<?php if (isset($_GET["createnew"])) {echo "?c=hotels&p=new";}else {echo "?c=hotels&p=edit&id={$id}";} ?>" class="validate">
<div class="tabs">
	<div class="eachtab"  title="Genel Bilgiler" id="state">
		<div class='element'>
			<label>Otelin Bulunduğu Ülke</label>
			<input type='text' class='text validate[required]' value='<?php echo $row['country']; ?>' name='var0' id='var0'>
		</div>

		<div class='element'>
			<label>Otelin Bulunduğu Şehir</label>
			<input type='text' class='text validate[required]' value='<?php echo $row['city']; ?>' name='var1' id='var1'>
		</div>

		<div class='element'>
			<label>Otelin Kategorisi</label>
			<select  name='var2' id='var2' class='validate[required,]'>
					<option <?php if ($row['category']=="superior") echo "selected=true"; ?> value="9">Superior</option>
					<option <?php if ($row['category']=="lujo") echo "selected=true"; ?> value="9">Lujo</option>
					<option <?php if ($row['category']=="delux") echo "selected=true"; ?> value="9">Delux</option>
			</select>
		</div>

		<div class='element'>
			<label>Otelin Yıldız Sayısı</label>
			<select  name='var3' id='var3' class='validate[required,]'>
					<option <?php if ($row['star']=="3") echo "selected=true"; ?> value="9">3***</option>
					<option <?php if ($row['star']=="4") echo "selected=true"; ?> value="9">4****</option>
					<option <?php if ($row['star']=="4+") echo "selected=true"; ?> value="9">4****+</option>
					<option <?php if ($row['star']=="5") echo "selected=true"; ?> value="9">5*****</option>
					<option <?php if ($row['star']=="5+") echo "selected=true"; ?> value="9">5*****+</option>
					<option <?php if ($row['star']=="crucero") echo "selected=true"; ?> value="9">Crucero</option>
					<option <?php if ($row['star']=="butik") echo "selected=true"; ?> value="9">Butik</option>
			</select>
		</div>
		
		<div class='element'>
			<label>Otelin Adı</label>
			<input type='text' class='text validate[required]' value='<?php echo $row['name']; ?>' name='var4' id='var4'>
		</div>
		<?php $options = db::fetchquery("SELECT * FROM media"); ?>
		<div class='element'>
			<label>Otelin Fotoğraflarının Bulunduğu Galeri</label>
			<select  name='var5' id='var5' class='validate[]'>
			<?php
			foreach($options as $option) {
				echo "<option ".(($row['images']==$option["id"]) ? "selected=true": "")." value='{$option["id"]}'>{$option["from"]}</option>";
			}
			?>
			</select>
		</div>

		<div class='element'>
			<label>Otelin E-posta Adresi</label>
			<input type='text' class='text validate[custom[email]]' value='<?php echo $row['email']; ?>' name='var6' id='var6'>
		</div>

		<div class='element'>
			<label>Otelin Web Adresi</label>
			<input type='text' class='text validate[custom[url]]' value='<?php echo $row['website']; ?>' name='var7' id='var7'>
		</div>

		<div class='element'>
			<label>Otelin Telefon Numarası</label>
			<input type='text' class='text validate[]' value='<?php echo $row['phone']; ?>' name='var8' id='var8'>
		</div>

		<div class='element'>
			<label>Otelin Gmap Adresi</label>
			<textarea class='text validate[]' name='var9' id='var9'><?php echo $row['gmap']; ?></textarea>
		</div>
	</div>
</div>

<div class="actions">
	<input type="submit" name="saveandedit" class="margin topmargin btn black" value="Save and edit again">
	<input type="submit" name="save" class="margin topmargin btn black" value="Save">
</div>
</form>
