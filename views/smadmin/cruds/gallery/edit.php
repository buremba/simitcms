<?php if(count(get_included_files()) ==1) exit("Direct access not permitted."); $datatables=TRUE; $imgupload = TRUE; ?>
<?php $name = $_GET['name']; ?>
<?php
if(isset($_GET["del"]) && is_numeric ($_GET["del"])) {
	if(!isset($_SERVER["HTTP_REFERER"])) send("Wrong request.", "error");
	$address = db::getvalue("SELECT address FROM media WHERE id = {$_GET["del"]} ");
	$action = db::delete("media", array("id" => $_GET["del"]));
	if(!is_file(UPLOADDIR.DIRECTORY_SEPARATOR.$address)) {
		send("Wrong Request.", "error");
	}else {
	if(file_exists(UPLOADDIR.DIRECTORY_SEPARATOR.thumburl($address))){
		$unlink = unlink(UPLOADDIR.DIRECTORY_SEPARATOR.thumburl($address));
	}
	$unlink2 = unlink(UPLOADDIR.DIRECTORY_SEPARATOR.$address);
	}
	if(@$action && @$unlink2) {
	send("Successfully Deleted", "success", 'crud.php?c=gallery&p=edit&name='.$name);
	}else {
	send("The item couldn't deleted.", "error");
	}
}
?>
<a class="btn right" style="margin-top:20px;" href="?c=gallery&p=new&name=<?php echo $name; ?>">Upload Images</a>
<h4>'<?php echo $name; ?>' Galerisine Ait İmajlar:</h4>
<script type="text/javascript" charset="utf-8">
	$(document).ready(function() {
		$('ul.submenu li:eq(1) a').attr('href','?c=gallery&p=edit&name=<?php echo $name; ?>');
		oTable  = $('#crud').dataTable( {
			"bProcessing": true,
			"bServerSide": true,
			"bJQueryUI": true,
			"fnInitComplete": function(oSettings, json) {
				$(this).parents('.dataTables_wrapper').find('.fg-toolbar:first').prepend('<div class="batchprocess hide"><span class="ui-icon ui-icon-arrowreturn-1-s"></span><input type="submit" confirm="Do you really want to delete selected items" class="btn grey" style="padding:3px" value="Seçilileri Sil" name="batchdel"></div>');
				$(this).parents('.dataTables_wrapper').find('.fg-toolbar:last').prepend('<div class="batchprocess hide"><span class="ui-icon ui-icon-arrowreturn-1-e"></span><input type="submit" confirm="Do you really want to delete selected items" class="btn grey" style="padding:3px" value="Seçilileri Sil" name="batchdel"></div>');
			},
			"aaSorting": [[ 5, "desc" ]],
			"oLanguage": oLanguage_tr,
			"sAjaxSource": "cruds/default/default.php",
			"fnServerData": function ( sSource, aoData, fnCallback ) {
			aoData.push( { "name": "c", "value": "media" } );
			aoData.push( { "name": "special_where", "value": "`from` = 'gallery-<?php echo $name; ?>'" } );
			aoData.push( { "name": "relation", "value": "author = users.id => users.username" } );
				$.ajax( {
					"dataType": 'json', 
					"type": "GET", 
					"url": sSource, 
					"data": aoData,
					"success": fnCallback
				} );
				},
			"bStateSave": false,
			"sPaginationType": "full_numbers",
			"aoColumns": [
				{"mDataProp": null, "sClass": "control center", "sWidth": "25px", "bSortable": false, "fnRender": function (id) {return '<input value='+id.aData[0]+' class="batchopt" name="tableitems[]" type="checkbox">';}, "sTitle" : '<input class="checkoptall" type="checkbox">'},
				{"sClass": "files",  "sTitle": "Medya", "bSearchable": true, "sWidth": "150px", "fnRender": function (imgurl) {if(imgurl.aData[2]=='JPG' || imgurl.aData[2]=='PNG' || imgurl.aData[2]=='JPEG' || imgurl.aData[2]=='GIF') {return "<a class='uigallery' href='<?php echo SITE_ADDRESS.'/uploads/' ?>"+imgurl.aData[this.iDataSort]+"'><img class=\'largethumb image\' src='"+thumburl('<?php echo SITE_ADDRESS.'/uploads/' ?>'+imgurl.aData[this.iDataSort])+"'></a>";}else {return '<a class="'+imgurl.aData[2].toLowerCase()+'" href="<?php echo SITE_ADDRESS.'/uploads/' ?>'+imgurl.aData[this.iDataSort]+'">';}}},
				{"sClass": "option order1",  "sTitle": "Mimetype", "sType": "string"},
				{"sClass": "date order2", "bVisible" : false},
				{"sClass": "date order2",  "sTitle": "Kullanıcı", "sType": "html"},
				{"sClass": "date order2",  "sTitle": "Yüklenen Tarih", "fnRender": function (timestamp) {return $.formatdate("d F Y / H:i", new Date(parseInt(timestamp.aData[this.iDataSort])*1000));}},
				{"mDataProp": null, "sClass": "control center", "fnRender": function (events) {return '<a onclick="if(!confirm(\'Satır geri dönüşümsüz bir şekilde silinecek, devam etmek istiyor musunuz?\')) {return false;}" href="?c=gallery&p=edit&name=<?php echo $name; ?>&del='+events.aData[0]+'"><span class="ui-icon ui-icon-trash"></span></a>';}, "bSortable": false}
			]
		} );
		$('.batchopt').each(function(index) {
			var aPos = oTable.fnGetPosition( this );
			var aData = oTable.fnGetData( this.parentNode );
		} );
	} );
</script>
<form method="POST" id="batchprocess" class="media" action="?c=gallery&p=edit&name=<?php echo $name; ?>">
<table cellpadding="0" cellspacing="0" border="0" class="nice editable" id="crud">
	<thead>
		<tr>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td class="dataTables_empty">Loading data from server</td>
		</tr>
	</tbody>
</table>
</form>