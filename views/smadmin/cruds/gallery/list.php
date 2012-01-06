<?php
if(count(get_included_files()) ==1) exit("Direct access not permitted.");

if(isset($_GET["del"])) {
	if(!isset($_SERVER["HTTP_REFERER"])) send("Wrong request.", "error");
	$address = db::fetchquery("SELECT address FROM media WHERE `from` = 'gallery-{$_GET["del"]}'");
	foreach($address as $a) {
		$action = db::delete("media", array("address" => $a));
		if(!is_file(UPLOADDIR.DIRECTORY_SEPARATOR.$address)) {
			send("Wrong Request.", "error");
		}else {
		if(file_exists(UPLOADDIR.DIRECTORY_SEPARATOR.thumburl($address))){
			$unlink = unlink(UPLOADDIR.DIRECTORY_SEPARATOR.thumburl($address));
		}
		$unlink2 = unlink(UPLOADDIR.DIRECTORY_SEPARATOR.$address);
		}
	}
	if(@$action && @$unlink2) {
	send("Successfully Deleted", "success", "crud.php?c=gallery");
	}else {
	send("The item couldn't deleted.", "error");
	}
}
?>
<form method="GET" action="crud.php">
	<input type="hidden" name="c" value="gallery">
	<input type="hidden" name="p" value="new">
	<input type="text" placeholder="oluşturmak istediğiniz galerinin ismi" name="name">
	<input type="submit" class="btn black" value="Oluştur" href="?c=gallery&p=new">
</form>
<?php $datatables=TRUE; ?>
<script type="text/javascript" charset="utf-8">
	$(document).ready(function() {
		oTable  = $('#crud').dataTable( {
			"bProcessing": true,
			"bServerSide": true,
			"bJQueryUI": true,
			"fnInitComplete": function(oSettings, json) {
				$(this).parents('.dataTables_wrapper').find('.fg-toolbar:first').prepend('<div class="batchprocess hide"><span class="ui-icon ui-icon-arrowreturn-1-s"></span><input type="submit" confirm="Do you really want to delete $(\'.batchopt:checked\').length" class="btn grey" style="padding:3px" value="Delete Selected Entries" name="batchdel"></div>');
				$(this).parents('.dataTables_wrapper').find('.fg-toolbar:last').prepend('<div class="batchprocess hide"><span class="ui-icon ui-icon-arrowreturn-1-e"></span><input type="submit" confirm="Do you really want to delete $(\'.batchopt:checked\').length" class="btn grey" style="padding:3px" value="Delete Selected Entries" name="batchdel"></div>');
			},
			"oLanguage": oLanguage_tr,
			"sAjaxSource": "cruds/default/default.php",
			"fnServerData": function ( sSource, aoData, fnCallback ) {
			aoData.push( { "name": 'sSelect', 'value': 'id, from, count(*)'});
			aoData.push( { "name": 'sGroupby', 'value': 'media.from'});
			aoData.push( { "name": "c", "value": "media" } );
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
				{"sClass": "option order0",  "sTitle": "Galeri Adı", "sType": "string", "bUseRendered": false, "fnRender": function (name) {return '<a href="?c=gallery&p=edit&name='+name.aData[1].replace('gallery-', '')+'">'+name.aData[1].replace('gallery-', '')+'</a>'}},
				{"sClass": "textbox order1",  "sTitle": "Galerideki dosya sayısı", "sType": "string"},
				{"mDataProp": null, "sClass": "control center", "fnRender": function (events) {return '<a href="?c=gallery&p=edit&name='+events.aData[1].replace('gallery-', '')+'"><span class="ui-icon ui-icon-pencil"></span></a><a confirm="You have selected 1 entry for permanent deletion. This action cannot be undone." href="?c=gallery&del='+events.aData[1].replace('gallery-', '')+'"><span class="ui-icon ui-icon-trash"></span></a>';}, "bSortable": false}
			]
		} );
		$('.batchopt').each(function(index) {
			var aPos = oTable.fnGetPosition( this );
			var aData = oTable.fnGetData( this.parentNode );
		} );
	} );
</script>

<form method="POST" id="batchprocess">
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