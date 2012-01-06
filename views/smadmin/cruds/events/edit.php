<form method="POST" action="<?php if (isset($_GET["createnew"])) {echo "?c=events&p=new";}else {echo "?c=events&p=edit&id={$id}";} ?>" class="validate">
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
			<label>Etkinlik İsmi</label>
			<input type='text' class='text validate[required,]' value='<?php echo $row["name"]; ?>' name='var1' id='var1'>
		</div>

		<div class="element">
			<label>Tarih Aralığı</label>
			<div class="betweendate">
			<input type="text" class="date validate[required,custom[date]]" value="<?php echo $row["start_date"]; ?>" name="var2" id="var2">
			<input type="text" class="date validate[required,custom[date]]" value="<?php echo $row["finish_date"]; ?>" name="var22" id="var22">
			</div>
		</div>

		<?php $textarea=TRUE; ?> 
		<div class='element'>
			<label>Etkinlik Açıklaması</label>
			<textarea name='var3' id='var3' class='ckeditor validate[]'><?php echo $row["description"]; ?></textarea>
		</div>
		
		<?php $options = db::fetchquery("SELECT REPLACE(media.from, 'gallery-','') as galleryname FROM media WHERE `from` LIKE 'gallery-%' GROUP BY `from`"); ?>
		<div class='element'>
			<label>Galerisi</label>
			<select  name='var4' id='var4' class='validate[]'>
			<?php
			foreach($options as $option) {
				echo "<option ".(($row["images"]==$option["galleryname"]) ? "selected=true": "")." value='{$option["galleryname"]}'>{$option["galleryname"]}</option>";
			}
			?>
			</select>
		</div>
	</div>
	
	<div class="eachtab" title="Seo" id="seo">
		<div class='element'>
			<label>Meta Anahtarları</label>
			<input type='text' class='text validate[required,]' value='<?php echo $row["meta_keys"]; ?>' name='var5' id='var5'>
		</div>

		<div class='element'>
			<label>Meta Açıklaması</label>
			<textarea  name='var6' id='var6'></textarea>
		</div>
	</div>
</div>

	<div class="actions">
		<input type="submit" name="saveandedit" class="margin topmargin btn black" value="Save and edit again">
		<input type="submit" name="save" class="margin topmargin btn black" value="Save">
	</div>
</form>
