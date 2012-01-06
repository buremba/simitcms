<?php if(count(get_included_files()) ==1) exit("Direct access not permitted.");

	$id = (int) $_GET["id"];
	
	if(isset($_POST["var0"])) {
		$dbset["lang"] = $_POST["var0"];
		$dbset["name"] = $_POST["var1"];
		$dbset["description"] = $_POST["var2"];
		$dbset["is_active"] = $_POST["active"];
		$dbset["price"] = $_POST["var3"];
		$dbset["de_discount"] = $_POST["var4"];
		$dbset["cl_discount"] = $_POST["var5"];
		$dbset["images"] = $_POST["var6"];
		$dbset["meta_key"] = $_POST["var7"];
		$dbset["meta_desc"] = $_POST["var8"];
		$dbset["country"] = $_POST["var9"];

		if(isset($sm_crud_dbsave)) array_merge($dbset, $sm_crud_dbsave);
		if(!db::update($table, $dbset, array("id" => $id))) {send($message[6], "error", "crud.php?c=extra_tours");} else {send($message[2], "success", "crud.php?c=extra_tours".(isset($_POST["saveandedit"]) ? "&p=edit&id={$id}": ""));}
	}
	
$row = db::fetchone("SELECT lang, is_active, country, name, description, price, de_discount, cl_discount, images, meta_key, meta_desc FROM $table WHERE id={$id}");
?>
<form method="POST" action="<?php if (isset($_GET["createnew"])) {echo "?c=extra_tours&p=new";}else {echo "?c=extra_tours&p=edit&id={$id}";} ?>" class="validate">
<div class="tabs">
	<div class="eachtab" title="Genel Bilgiler" id="general">
		<div class='element'>
			<label>Aktiflik Durumu</label>
			<select name='active' class='validate[required,]' id='active'>
				<option value="1" <?php if($row['is_active']=='1') echo "selected='selected'"; ?>>Aktif</option>
				<option value="0" <?php if($row['is_active']=='0') echo "selected='selected'"; ?>>Aktif Değil</option>
			</select>
		</div>
		<div class='element'>
			<label>Opsiyonel turun aktif olduğu dil</label>
			<select name='var0' id='var0' class='validate[required,]'>
			<?php
			foreach(sm_dynamicarea('Languages') as $lang) {
				echo "<option ".(($row['lang']==$lang["key"]) ? "selected=true": "")." value='{$lang["key"]}'>{$lang["value"]}</option>";
			}
			?>
			</select>
		</div>
		
		<div class='element'>
			<label>Opsiyonel turun ülkesi</label>
			<select name='var9' class='validate[required,]' id='var9'>
			<?php
			foreach(sm_dynamicarea(11) as $country) {
				echo "<option ".(($row['country']==$lang["key"]) ? "selected=true": "")." value='{$country}'>{$country}</option>";
			}
			?>
			</select>
		</div>

		<div class='element'>
			<label>Optiyonel turun ismi</label>
			<input type='text' class='text validate[required,]' value='<?php echo $row['name']; ?>' name='var1' id='var1'>
		</div>
		
		<?php $options = db::fetchquery("SELECT `from` FROM media WHERE `from` LIKE 'gallery-%'"); ?>
		<div class='element'>
			<label>Galeri</label>
			<select name='var6' id='var6' class='validate[]'>
			<?php
			foreach($options as $option) {
				$option['from'] = str_replace('gallery-', '', $option["from"]);
				echo "<option ".(($row['images']==$option["from"]) ? "selected=true": "")." value='{$option["from"]}'>{$option["from"]}</option>";
			}
			?>
			</select>
		</div>

		<?php $textarea=TRUE; ?> 
		<div class='element'>
			<label>Opsiyonel turun açıklaması</label>
			<textarea name='var2' id='var2' class='ckeditor validate[]'><?php echo $row['description']; ?></textarea>
		</div>
	</div>
	
	<div class="eachtab" title="Fiyat" id="price">
		<div class='element'>
			<label>Opsiyonel turun fiyatı</label>
			<input type='text' class='text validate[required,custom[onlyNumberSp]]' value='<?php echo $row['price']; ?>' name='var3' id='var3'>
		</div>

		<div class='element'>
			<label>Opsiyonel turun kullanıcılara olan indirim oranı</label>
			<input type='text' class='text validate[custom[onlyNumberSp]]' value='<?php echo $row['de_discount']; ?>' name='var4' id='var4'>
		</div>

		<div class='element'>
			<label>Opsiyonel turun bayilere olan indirim oranı</label>
			<input type='text' class='text validate[custom[onlyNumberSp]]' value='<?php echo $row['cl_discount']; ?>' name='var5' id='var5'>
		</div>
	</div>

	<div class="eachtab" title="Seo" id="seo">
		<div class='element'>
			<label>Opsiyonel tura ait meta etiketleri</label>
			<input type='text' class='text validate[]' value='<?php echo $row['meta_key']; ?>' name='var7' id='var7'>
		</div>

		<?php $textarea=TRUE; ?> 
		<div class='element'>
			<label>Opsiyonel turun meta açıklaması</label>
			<textarea name='var8' id='var8' class='validate[]'><?php echo $row['meta_desc']; ?></textarea>
		</div>
	</div>

<div class="actions">
	<input type="submit" name="saveandedit" class="margin topmargin btn black" value="Save and edit again">
	<input type="submit" name="save" class="margin topmargin btn black" value="Save">
</div>
</form>
