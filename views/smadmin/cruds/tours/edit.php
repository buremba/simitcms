<?php
if(count(get_included_files()) ==1) exit("Direct access not permitted.");

	$id = (int) $_GET["id"];
	
	if(isset($_POST["var0"])) {
	
		foreach($_POST["extraprice"] as $key => $case) {
			$_POST["extraprice"][$key]['start'] = strtotime($_POST["extraprice"][$key]['start']);
			$_POST["extraprice"][$key]['finish'] = strtotime($_POST["extraprice"][$key]['finish']);
		}
		
		foreach($_POST["specialdates"] as $key => $case) {
			$_POST["specialdates"][$key]['start'] = strtotime($_POST["specialdates"][$key]['start']);
			$_POST["specialdates"][$key]['finish'] = strtotime($_POST["specialdates"][$key]['finish']);
		}
		
		$dbset["lang"] = $_POST["var0"];
		$dbset["is_active"] = $_POST["var1"];
		$dbset["is_flight"] = $_POST["is_flight"];
		$dbset["code_no"] = $_POST["var2"];
		$dbset["showroom"] = $_POST["showroom"];
		$dbset["name"] = $_POST["var3"];
		$dbset["description"] = $_POST["var4"];
		$dbset["in_tour"] = $_POST["var5"];
		$dbset["out_tour"] = $_POST["var6"];
		$dbset["extra_notes"] = $_POST["var7"];
		$dbset["images"] = $_POST["var8"];
		$dbset["category"] = $_POST["var9"];
		$dbset["country"] = serialize($_POST["var10"]);
		$dbset["files"] = serialize(array('doc' => $_POST["docfile"], 'pdf' => $_POST["pdffile"]));
		$dbset["meta_key"] = $_POST["var12"];
		$dbset["meta_desc"] = $_POST["var13"];
		$dbset["cl_discount"] = $_POST["var14"];
		$dbset["total_days"] = $_POST["var15"];
		$dbset["first_day"] = strtotime($_POST["var16"]);
		$dbset["last_day"] = strtotime($_POST["var17"]);
		$dbset["remove_before"] = $_POST["var18"];
		$dbset["default_price"] = $_POST["var19"];
		$dbset["min_hotel"] = $_POST["hotel"]['tourist']['double'];
		$dbset["min_people"] = $_POST["var20"];
		$dbset["hotels"] = serialize($_POST["hotel"]);
		$dbset["overnight"] = serialize($_POST["overnight"]);
		$dbset["extra_tours"] = serialize($_POST["extratour"]);
		$dbset["extra_prices"] = serialize($_POST["extraprice"]);
		$dbset["flights"] = serialize($_POST["flights"]);
		$dbset["active_days"] = serialize($_POST["active_days"]);
		$dbset["extra_dates"] = serialize($_POST["specialdates"]);

		if(isset($sm_crud_dbsave)) array_merge($dbset, $sm_crud_dbsave);
		if(!db::update($table, $dbset, array("id" => $id))) {send($message[5], "error", "crud.php?c=tours");} else {send('düzenlendi', "success", "crud.php?c=tours".(isset($_POST["saveandedit"]) ? "&p=edit&id=".$id: ""));}
	}
	
	$row = db::fetchone("SELECT * FROM $table WHERE id={$id}");
?>
<form method='POST' action='<?php if (isset($_GET["createnew"])) {echo "?c=tours&p=new";}else {echo "?c=tours&p=edit&id={$id}";} ?>' class='nice tabbed validate'>

<div class="tabs">
	<div class="eachtab" title="Genel Bilgiler" id="general">
		<div class='element'>
			<label>Turun dili</label>
			<select name='var0' class='validate[required,]' id='var0'>
			<?php
			foreach(sm_dynamicarea('Languages') as $lang) {
				echo "<option ".(($row['lang']==$lang["key"]) ? "selected=true": "")." value='{$lang["key"]}'>{$lang["value"]}</option>";
			}
			?>
			</select>
		</div>

		<div class='element'>
			<label>Turun satış kodu</label>
			<input type='text' class='text validate[required]' name='var2' id='var2' value="<?php echo $row['code_no']; ?>">
		</div>

		<div class='element'>
			<label>Turun adı</label>
			<input type='text' class='text validate[required,]' name='var3' id='var3' value="<?php echo $row['name']; ?>">
		</div>
		
		<div class='element'>
			<label>Anasayfa ve Banner</label>
			<select name="showroom">
				<option value="0" <?php if($row['showroom']=='0') echo "selected='selected'"; ?>>Anasayfada veya Bannerda Gösterme</option>
				<option value="1" <?php if($row['showroom']=='1') echo "selected='selected'"; ?>>Anasayfada Göster</option>
				<option value="2" <?php if($row['showroom']=='2') echo "selected='selected'"; ?>>Bannerda Göster</option>
			</select>
		</div>

		<?php $textarea=TRUE; ?> 
		<div class='element'>
			<label>Turun açıklaması</label>
			<textarea name='var4' class='ckeditor validate[]' id='var4'><?php echo $row['description']; ?></textarea>
		</div>

		<?php $textarea=TRUE; ?> 
		<div class='element'>
			<label>Tura dahil aktiviteler</label>
			<textarea name='var5' class='ckeditor validate[]' id='var5'><?php echo $row['in_tour']; ?></textarea>
		</div>

		<?php $textarea=TRUE; ?> 
		<div class='element'>
			<label>Tura hariç aktiviteler</label>
			<textarea name='var6' class='ckeditor validate[]' id='var6'><?php echo $row['out_tour']; ?></textarea>
		</div>

		<?php $textarea=TRUE; ?> 
		<div class='element'>
			<label>Ekstra bilgiler</label>
			<textarea name='var7' class='ckeditor validate[]' id='var7'><?php echo $row['extra_notes']; ?></textarea>
		</div>
	</div>
	<div class="eachtab" title="Özellikler" id="features">
		<?php $options = db::fetchquery("SELECT REPLACE(media.from, 'gallery-','') as galleryname FROM media WHERE `from` LIKE 'gallery-%' GROUP BY `from`"); ?>
		<div class='element'>
			<label>Turun galerisi</label>
			<select  name='var8' class='validate[]' id='var8'>
			<?php
			foreach($options as $option) {
				echo "<option ".(($row["images"]==$option["galleryname"]) ? "selected=true": "")." value='{$option["galleryname"]}'>{$option["galleryname"]}</option>";
			}
			?>
			</select>
		</div>

		<div class='element'>
			<label>Turun kategorisi</label>
			<select  name='var9' class='validate[required,]' id='var9'>
			<?php foreach(sm_dynamicarea(28) as $cat) : ?>
					<option <?php if ($row["category"]==$cat) echo "selected=true"; ?> value="<?php echo $cat; ?>"><?php echo $cat; ?></option>
			<?php endforeach; ?>
			</select>
		</div>

		<div class='element'>
			<label>Turun Ülkesi</label>
			<select multiple name='var10[]' class='validate[required,]' id='var10'>
			<?php
			$counties = array_unserialize($row["country"]);
			foreach(sm_dynamicarea(11) as $option) {
				echo "<option ".((in_array($option, $counties)) ? "selected=true": "")." value='{$option}'>{$option}</option>";
			}
			?>
			</select>
		</div>
	</div>
	<div class="eachtab" title="Dosya Yükleme" id="files">
		<?php $imgupload=TRUE; $files = array_unserialize($row['files']); ?> 
		<div class='element'>
			<label>Turun tanıtım dosyaları (doc)</label>
			<input type='text' name='docfile' id='var191' class='image validate[custom[url]]' value="<?php echo $files['doc']; ?>">
			<span class='btn pointer customimgbrowse' alt='var11'>Browse from library</span>
			<div class='uploaddoc'>Upload a file</div>
		</div> 
		<div class='element'>
			<label>Turun tanıtım dosyaları (pdf)</label>
			<input type='text' name='pdffile' id='var1961' class='image validate[custom[url]]' value="<?php echo $files['pdf']; ?>">
			<span class='btn pointer customimgbrowse' alt='var11'>Browse from library</span>
			<div class='uploaddoc'>Upload a file</div>
		</div>
	</div>
	<div class="eachtab" title="Seo" id="seo">
		<div class='element'>
			<label>Turun sayfasının meta etiketleri</label>
			<input type='text' class='text validate[maxSize[150]]' name='var12' id='var12' value="<?php echo $row['meta_key']; ?>">
		</div>

		<?php $textarea=TRUE; ?> 
		<div class='element'>
			<label>Turun sayfasının meta açıklaması</label>
			<textarea name='var13' class='validate[]' id='var13'><?php echo $row['meta_desc']; ?></textarea>
		</div>
	</div>
	<div class="eachtab" title="Satış bilgileri" id="sell">
	
		<div class='element'>
			<label>Turun aktiflik durumu</label>
			<select  name='var1' class='validate[required,]' id='var1'>
					<option <?php if ($row["is_active"]=="1") echo "selected=true"; ?> value="1">Aktif</option>
					<option <?php if ($row["is_active"]=="0") echo "selected=true"; ?> value="0">Aktif Değil</option>
			</select>
		</div>
		<div class='element'>
			<label>Turun acentelere indirimi</label>
			<select name="var14" id='var14'>
				<option <?php echo (($row['cl_discount']=='1') ? "selected='selected'" : ''); ?> value="1">Uygula</option>
				<option <?php echo (($row['cl_discount']=='0') ? "selected='selected'" : ''); ?> value="0">Uygulama</option>
			</select>
		</div>

		<div class='element'>
			<label>Toplam tur günü</label>
			<input type='text' class='text validate[required,custom[onlyNumberSp]]' name='var15' id='var15' value="<?php echo $row['total_days']; ?>">
		</div>

		<script>
			$(function() {
				var dates = $( "#var16, #var17" ).datepicker({
					defaultDate: "+1w",
					numberOfMonths: 3,
					onSelect: function( selectedDate ) {
						var option = this.id == "var16" ? "minDate" : "maxDate",
							instance = $( this ).data( "datepicker" ),
							date = $.datepicker.parseDate(
								instance.settings.dateFormat ||
								$.datepicker._defaults.dateFormat,
								selectedDate, instance.settings );
						dates.not( this ).datepicker( "option", option, date );
					}
				});
			});
		</script>

		<div class="element">
			<label>Satış aralığı</label>
			<input type="text" name="var16" id="var16" value="<?php echo $row['first_day']; ?>"  class="date"> -
			<input type="text" name="var17" id="var17" value="<?php echo $row['last_day']; ?>"  class="date">
		</div>
		
		<div class='element'>
			<label>Son satış tarihinden önce gün?</label>
			<input type='text' class='text validate[custom[onlyNumberSp]]' name='var18' id='var18' value="<?php echo $row['remove_before']; ?>">
		</div>
		<div class='element'>
			<label>Turun taban fiyatı</label>
			<input type='text' class='text validate[required,custom[onlyNumberSp]]' name='var19' id='var19' value="<?php echo $row['default_price']; ?>">
		</div>

		<div class='element'>
			<label>Tura katılabilmek için en az kişi sayısı</label>
			<input type='text' class='text validate[custom[onlyNumberSp]]' name='var20' id='var20' value="<?php echo $row['min_people']; ?>">
		</div>
	</div>
	<?php $hotel = array_unserialize($row['hotels']); ?>
	<div class="eachtab" title="Otel Bilgileri" id="hotel">
		<div class='element'>
			<label>Turist Sınıfı</label>
			<div class="clear" style="margin-left:50px; margin-top:28px;">
				<label>Single</label>
				<input type='text' class='text validate[custom[onlyNumberSp]]' name='hotel[tourist][single]' id='var140' value="<?php echo $hotel['tourist']['single']; ?>">
			</div>
			<div class="clear" style="margin-left:50px;">
				<label>Double</label>
				<input type='text' class='text validate[custom[onlyNumberSp]]' name='hotel[tourist][double]' id='var141' value="<?php echo $hotel['tourist']['double']; ?>">
			</div>
			<div class="clear" style="margin-left:50px;">
				<label>Triple</label>
				<input type='text' class='text validate[custom[onlyNumberSp]]' name='hotel[tourist][triple]' id='var142' value="<?php echo $hotel['tourist']['triple']; ?>">
			</div>
		</div>
		<div class='element'>
			<label>Süper Kategori</label>
			<div class="clear" style="margin-left:50px; margin-top:28px;">
				<label>Single</label>
				<input type='text' class='text validate[custom[onlyNumberSp]]' name='hotel[super][single]' id='var143' value="<?php echo @$hotel['super']['single']; ?>">
			</div>
			<div class="clear" style="margin-left:50px;">
				<label>Double</label>
				<input type='text' class='text validate[custom[onlyNumberSp]]' name='hotel[super][double]' id='var144' value="<?php echo @$hotel['super']['double']; ?>">
			</div>
			<div class="clear" style="margin-left:50px;">
				<label>Triple</label>
				<input type='text' class='text validate[custom[onlyNumberSp]]' name='hotel[super][triple]' id='var145' value="<?php echo @$hotel['super']['triple']; ?>">
			</div>
		</div>
		<div class='element'>
			<label>Lüks Kategori</label>
			<div class="clear" style="margin-left:50px; margin-top:28px;">
				<label>Single</label>
				<input type='text' class='text validate[custom[onlyNumberSp]]' name='hotel[luks][single]' id='var146' value="<?php echo @$hotel['luks']['single']; ?>">
			</div>
			<div class="clear" style="margin-left:50px;">
				<label>Double</label>
				<input type='text' class='text validate[custom[onlyNumberSp]]' name='hotel[luks][double]' id='var147' value="<?php echo @$hotel['luks']['double']; ?>">
			</div>
			<div class="clear" style="margin-left:50px;">
				<label>Triple</label>
				<input type='text' class='text validate[custom[onlyNumberSp]]' name='hotel[luks][triple]' id='var148' value="<?php echo @$hotel['luks']['triple']; ?>">
			</div>
		</div>
		<?php $hotels = db::fetchquery("SELECT id, name FROM c_hotels"); ?>
		<div class="inputgroup" name="hotel[hotels]">
		<p class="add">Yeni otel ekle</p>
		<example style="display:none">
			<div class="element extrahotels">
			<label>Otelin Adı</label>
			<select name="%s[%n][name]" id="name" class="validate[required]">
			<?php foreach($hotels as $dbhotel) {echo "<option value='{$dbhotel['id']}'>{$dbhotel['name']}</option>";} ?>
			</select>
			<remove onclick="$(this).parent().remove();">-</remove>
			<div class="clear">
				<label>Single</label>
				<input type='text' class='text validate[custom[onlyNumberSp]]' name='%s[%n][single]' id='single'>
			</div>
			<div class="clear">
				<label>Double</label>
				<input type='text' class='text validate[custom[onlyNumberSp]]' name='%s[%n][double]' id='double'>
			</div>
			<div class="clear">
				<label>Triple</label>
				<input type='text' class='text validate[custom[onlyNumberSp]]' name='%s[%n][triple]' id='triple'>
			</div>
			</div>
		</example>
		<?php $i=0; foreach(((isset($hotel['hotels'])) ? $hotel['hotels'] : array()) as $ehotel) : ?>
			<div class="element extrahotels">
			<label>Otelin Adı</label>
			<select name="hotel[hotels][<?php echo $i; ?>][name]" id="name" class="validate[required]">
			<?php foreach($hotels as $dbhotel) {echo "<option ".(($dbhotel['id']==$ehotel['name']) ? 'selected="selected"' : "")." value='{$dbhotel['id']}'>{$dbhotel['name']}</option>";} ?>
			</select>
			<remove onclick="$(this).parent().remove();">-</remove>
			<div class="clear">
				<label>Single</label>
				<input type='text' class='text validate[custom[onlyNumberSp]]' name='hotel[hotels][<?php echo $i; ?>][single]' id='hotel[hotels][<?php echo $i; ?>][single]' value="<?php echo $ehotel['single']; ?>">
			</div>
			<div class="clear">
				<label>Double</label>
				<input type='text' class='text validate[custom[onlyNumberSp]]' name='hotel[hotels][<?php echo $i; ?>][double]' id='hotel[hotels][<?php echo $i; ?>][double]' value="<?php echo $ehotel['double']; ?>">
			</div>
			<div class="clear">
				<label>Triple</label>
				<input type='text' class='text validate[custom[onlyNumberSp]]' name='hotel[hotels][<?php echo $i; ?>][triple]' id='hotel[hotels][<?php echo $i; ?>][triple]' value="<?php echo $ehotel['triple']; ?>">
			</div>
			</div>
		<?php $i++; endforeach; ?>
		</div>
	</div>
	<div class="eachtab" title="Ekstra Geceleme" id="overnight">
		<?php $options = db::fetchquery("SELECT id, name FROM c_overnight"); $selected = array_unserialize($row['overnight']); ?>
		<div class='element'>
			<label>Bu tura Dahil olabilecek ekstra turları seçiniz</label>
			<select multiple name='overnight[]' class='validate[]' id='overnights' style="width:100%">
				<?php foreach($options as $night) {echo "<option ".((in_array($night['id'], $selected)) ? "selected=true": "")." value='{$night['id']}'>{$night['name']}</option>";} ?>
			</select>
		</div>
	</div>
	<div class="eachtab" title="Opsiyoneller" id="extratour">
		<?php $options = db::fetchquery("SELECT id, name FROM c_extra_tours"); $selected = array_unserialize($row['extra_tours']); ?>
		<div class='element'>
			<label>Bu tura dahil olabilecek opsiyonelleri seçiniz</label>
			<select multiple name='extratour[]' class='validate[]' id='extratours' style="width:100%">
				<?php foreach($options as $option) {echo "<option ".((in_array($option['id'], $selected)) ? "selected=true": "")." value='{$option['id']}'>{$option['name']}</option>";} ?>
			</select>
		</div>
	</div>
	<div class="eachtab" title="Uçuşlar" id="flight">
		<?php $options = db::fetchquery("SELECT id, name FROM c_flights"); $selected = array_unserialize($row['flights']); ?>
		<div class='element'>
			<label>Uçuş seçilmesi gerekliliği</label>
			<select name="is_flight">
			<option <?php echo (($row['is_flight']=='1') ? "selected='selected'" : ''); ?> value="1">Zorunlu</option>
			<option <?php echo (($row['is_flight']=='0') ? "selected='selected'" : ''); ?> value="0">Zorunlu değil</option>
			</select>
		</div>
		<div class='element'>
			<label>Bu turdaki uçuş seçenekleri</label>
			<select multiple name='flights[]' class='validate[]' id='flights' style="width:100%">
				<?php foreach($options as $option) {echo "<option ".((in_array($option['id'], $selected)) ? "selected=true": "")." value='{$option['id']}'>{$option['name']}</option>";} ?>
			</select>
		</div>
	</div>
	<div class="eachtab" title="Ek Fiyatlandırma" id="price">
		<script>
		$(function () {
			$('.inputgroup.extraprice p.add').click(function() {
				var index = $(this).parent().attr('count');
				$( ".extraprice .betweendate-"+index+" input:eq(0), .extraprice .betweendate-"+index+" input:eq(1)").datepicker({
				defaultDate: "+1w",
				numberOfMonths: 3,
				onSelect: function( selectedDate ) {
					var option = $(this).is(".extraprice .betweendate-"+index+" input:eq(0)") ? "minDate" : "maxDate",
						instance = $( this ).data( "datepicker" ),
						date = $.datepicker.parseDate(
							instance.settings.dateFormat ||
							$.datepicker._defaults.dateFormat,
							selectedDate, instance.settings );
					$(".extraprice .betweendate-"+index+" input:eq(1)").not( this ).datepicker( "option", option, date );
				}
				});
			});
			$('.extrahotels:not(example .extrahotels)').each(function(index) {
				var a = '<div class="element"><label>'+$(this).find('select option[value="'+$(this).find('select').val()+'"]').text()+'</label><input type="text" class="validate[required] clear topmargin" name="%s[%n][hotels]['+index+'][price]" id="extraprice'+index+' placeholder="toplam fiyata eklenilecek rakam"></div>';
				$('example .extrahotels_aim').append(a);
			});
			$("select.extrapricetype").live("change", function(){
				if($(this).val()=='1') {
					$(this).parents('.eachgroup').find('#days').attr('disabled', true);
				}else
				if($(this).val()=='2') {
					$(this).parents('.eachgroup').find('#days').removeAttr('disabled');
				}
			});
		});
		</script>
		<div class="inputgroup extraprice" name="extraprice">
			<p class="add">Yeni kural ekle</p>
			<example style="display:none">
				<div class="eachgroup">
					<div class="element">
						<label>#%n > Tarih Aralığı seçin</label>
						<div class="betweendate-%n">
							<input type="text" class="validate[required,custom[date]]" name="%s[%n][start]" id="extraprice0-%n"> -
							<input type="text" class="validate[required,custom[date]]" name="%s[%n][finish]" id="extraprice1-%n">
							<remove onclick="$(this).parents('.eachgroup').remove();">-</remove>
						</div>
						<div class="element">
						<label>Gün sayısı</label>
						<input type="text" class="validate[required]" disabled name="%s[%n][days]" id="days" placeholder="toplam fiyata eklenilecek rakam">
						</div>
						<div class="element">
						<label>Tourist</label>
						<input type="text" class="validate[required]" name="%s[%n][price][tourist]" id="extraprice3" placeholder="toplam fiyata eklenilecek rakam">
						</div>
						<div class="element">
						<label>Super</label>
						<input type="text" class="validate[required]" name="%s[%n][price][super]" id="extraprice3" placeholder="toplam fiyata eklenilecek rakam">
						</div>
						<div class="element">
						<label>Luks</label>
						<input type="text" class="validate[required]" name="%s[%n][price][luks]" id="extraprice3" placeholder="toplam fiyata eklenilecek rakam">
						</div>
						<div class="extrahotels_aim"></div>
						<div class="element">
						<label>Türü</label>
						<select class="validate[required] clear topmargin extrapricetype" name="%s[%n][type]" id="extraprice4">
							<option value="1">Alta Temporada</option>
							<option value="2">Temporada de Fiestas</option>
						</select>
						</div>
					</div>
				</div>
			</example>
			<?php $i=0; foreach(array_unserialize($row['extra_prices']) as $extra) : ?>
				<div class="eachgroup">
					<div class="element">
						<label>#<?php echo $i; ?> Tarih Aralığı seçin</label>
							<div class="betweendates">
								<input type="text" class="cdate validate[required,custom[date]]" name="extraprice[<?php echo $i; ?>][start]" id="extraprice0-<?php echo $i; ?>" value="<?php echo $extra['start']; ?>"  class="date"> -
								<input type="text" class="cdate validate[required,custom[date]]" name="extraprice[<?php echo $i; ?>][finish]" id="extraprice1-<?php echo $i; ?>" value="<?php echo $extra['finish']; ?>"  class="date">
								<remove onclick="$(this).parents('.eachgroup').remove();">-</remove>
							</div>
						</div>
						<div class="element">
							<label>Gün sayısı</label>
							<input type="text" class="validate[required] clear topmargin" <?php echo ((!isset($extra['days'])) ? "disabled": ''); ?> id="days" name="extraprice[<?php echo $i; ?>][days]" value="<?php echo @$extra['days']; ?>" id="extraprice3" placeholder="toplam fiyata eklenilecek rakam">
						</div>
						<div class="element">
							<label>Tourist</label>
							<input type="text" class="validate[required] clear topmargin" name="extraprice[<?php echo $i; ?>][price][tourist]" id="extraprice3" value="<?php echo $extra['price']['tourist']; ?>" placeholder="toplam fiyata eklenilecek rakam">
						</div>
						<div class="element">
							<label>Super</label>
							<input type="text" class="validate[required] clear topmargin" name="extraprice[<?php echo $i; ?>][price][super]" id="extraprice3" value="<?php echo $extra['price']['super']; ?>" placeholder="toplam fiyata eklenilecek rakam">
						</div>
						<div class="element">
							<label>Luks</label>
							<input type="text" class="validate[required] clear topmargin" name="extraprice[<?php echo $i; ?>][price][luks]" id="extraprice3" value="<?php echo $extra['price']['luks']; ?>" placeholder="toplam fiyata eklenilecek rakam">
						</div>
						<div class="extrahotels_aim">
						<?php foreach($hotel['hotels'] as $h) {$ha[] = $h['name'];} ?>
						
						<?php foreach($extra['hotels'] as $id => $value) : ?>
						<div class="element">
						<label><?php echo db::getvalue("SELECT name FROM c_hotels WHERE id = ".intval($ha[$id])); ?></label>
						<input type="text" class="validate[required] clear topmargin" value="<?php echo $value['price']; ?>" name="extraprice[<?php echo $i; ?>][hotels][<?php echo $id; ?>][price]" id="extraprice<?php echo $id; ?>" placeholder="toplam fiyata eklenilecek rakam">
						</div>
						<?php endforeach; ?>
						</div>
						<div class="element">
						<label>Türü</label>
						<select class="validate[required] clear topmargin extrapricetype" name="extraprice[<?php echo $i; ?>][type]" id="extraprice4">
							<option <?php echo (($extra['type']=='1') ? "selected='selected'": ''); ?> value="1">Alta Temporada</option>
							<option <?php echo (($extra['type']=='2') ? "selected='selected'": ''); ?> value="2">Temporada de Fiestas</option>
						</select>
						</div>
				</div>
			<?php $i++; endforeach; ?>
		</div>
	</div>
	<div class="eachtab" title="Tur Hareket Günleri" id="activeday">
		<?php $days = array_unserialize($row['active_days']); ?>
		<div class='element'>
			<label>Hangi günlerde tur harekete geçiyor</label>
			<select multiple name="active_days[]" id="active_days">
				<option <?php if(in_array('1', $days)) echo 'selected="selected"'; ?> value="1">Pazartesi</option>
				<option <?php if(in_array('2', $days)) echo 'selected="selected"'; ?> value="2">Salı</option>
				<option <?php if(in_array('3', $days)) echo 'selected="selected"'; ?> value="3">Çarşamba</option>
				<option <?php if(in_array('4', $days)) echo 'selected="selected"'; ?> value="4">Perşembe</option>
				<option <?php if(in_array('5', $days)) echo 'selected="selected"'; ?> value="5">Cuma</option>
				<option <?php if(in_array('6', $days)) echo 'selected="selected"'; ?> value="6">Cumartesi</option>
				<option <?php if(in_array('7', $days)) echo 'selected="selected"'; ?> value="7">Pazar</option>
			</select>
		</div>
		<script>
		$(function () {
			$('.inputgroup.specialdates p.add').click(function() {
				var index = $(this).parent().attr('count');
				$( ".specialdates .betweendate-"+index+" input:eq(0), .specialdates .betweendate-"+index+" input:eq(1)").datepicker({
				defaultDate: "+1w",
				numberOfMonths: 3,
				onSelect: function( selectedDate ) {
					var option = $(this).is(".specialdates .betweendate-"+index+" input:eq(0)") ? "minDate" : "maxDate",
						instance = $( this ).data( "datepicker" ),
						date = $.datepicker.parseDate(
							instance.settings.dateFormat ||
							$.datepicker._defaults.dateFormat,
							selectedDate, instance.settings );
					$(".specialdates .betweendate-"+index+" input:eq(1)").not( this ).datepicker( "option", option, date );
				}
				});
			});
		});
		</script>
		<div class='element'>
			<label>Tarih aralıkları</label>
			<div class="inputgroup specialdates" name="specialdates">
				<p class="add">Yeni kural ekle</p>
				<example style="display:none">
					<div class="eachgroup">
						<div class="element">
							<label>Kural #%n</label>
							<div class="betweendate-%n">
								<input type="text" class="validate[required,custom[date]]" name="%s[%n][start]" id="%s0-%n"> -
								<input type="text" class="validate[required,custom[date]]" name="%s[%n][finish]" id="%s1-%n">
								<remove onclick="$(this).parents('.eachgroup').remove();">-</remove>
							</div>
						</div>
					</div>
				</example>
				<?php $i=0; foreach(array_unserialize($row['extra_dates']) as $extra) : ?>
					<div class="eachgroup">
						<div class="element">
							<label>Kural #<?php echo $i; ?></label>
							<div class="betweendate-<?php echo $i; ?>">
								<input type="text" class="cdate validate[required,custom[date]]" name="specialdates[<?php echo $i; ?>][start]" id="specialdates0-<?php echo $i; ?>" value="<?php echo $extra['start']; ?>"> -
								<input type="text" class="cdate validate[required,custom[date]]" name="specialdates[<?php echo $i; ?>][finish]" id="specialdates1-<?php echo $i; ?>" value="<?php echo $extra['finish']; ?>">
								<remove onclick="$(this).parents('.eachgroup').remove();">-</remove>
							</div>
						</div>
					</div>
				<?php $i++; endforeach; ?>
			</div>
		</div>
	</div>
</div>
<div class="actions">
	<input type="submit" name="saveandedit" class="margin topmargin btn black" value="Save and edit again">
	<input type="submit" name="save" class="margin topmargin btn black" value="Save">
</div>

</form>
