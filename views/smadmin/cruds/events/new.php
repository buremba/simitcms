<script src="<?php echo URL::site('sm-media/plugins/elrte/js/elrte.min.js') ?>" type="text/javascript" charset="utf-8"></script>
<link rel="stylesheet" href="<?php echo URL::site('sm-media/plugins/elrte/css/elrte.min.css') ?>" type="text/css" media="screen" charset="utf-8">
<script src="<?php echo URL::site('sm-media/plugins/elrte/js/i18n/elrte.tr.js') ?>" type="text/javascript" charset="utf-8"></script>

<form method='POST' action='?c=events&p=new' class='nice tabbed validate'>
<div class="tabs">
	<div class="eachtab" title="Genel Bilgiler" id="general">
		<div class='element'>
			<label>Dil</label>
			<select  name='var0' class='validate[required,]' id='var0'>
				<?php
				foreach(sm_static('Languages') as $lang) {
					echo "<option  value='{$lang["key"]}'>{$lang["value"]}</option>";
				}
				?>
			</select>
		</div>

		<div class='element'>
			<label>Etkinlik İsmi</label>
			<input type='text' class='text validate[required,]' name='var1' id='var1'>
		</div>

		<div class="element">
			<label>Tarih Aralığı</label>
			<div class="betweendates">
				<input type="text" class="date" name="var2"> -
				<input type="text" class="date" name="var22">
			</div>
		</div>
		
		<div class='element'>
			<label>Etkinlik Açıklaması</label>
			<textarea name='var3' class='ckeditor validate[]' id='var3'></textarea>
		</div>

		<?php $options = db::fetchquery("SELECT REPLACE(media.from, 'gallery-','') as galleryname FROM media WHERE `from` LIKE 'gallery-%' GROUP BY `from`"); ?>
		<div class='element'>
			<label>Galerisi</label>
			<select  name='var4' class='validate[]' id='var4'>
			<?php
			foreach($options as $option) {
				echo "<option value='{$option["galleryname"]}'>{$option["galleryname"]}</option>";
			}
			?>
			</select>
		</div>
	</div>
	
	<div class="eachtab" title="Seo" id="seo">
		<div class='element'>
			<label>Meta Anahtarları</label>
			<input type='text' class='text validate[required,]' name='var5' id='var5'>
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
