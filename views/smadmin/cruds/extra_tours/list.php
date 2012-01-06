<?php if(count(get_included_files()) ==1) exit("Direct access not permitted.");

if(isset($_GET["del"]) && is_numeric ($_GET["del"])) {
	if(!isset($_SERVER["HTTP_REFERER"])) die("don't do that");
	$action = db::delete("c_extra_tours", array("id" => $_GET["del"]));
	if($action) {
	send("Successfully Deleted", "success", "crud.php?c=extra_tours");
	}else {
	send("The item couldn't deleted.", "error");
	}
}

if(isset($_POST["activestatus"]) && isset($_POST['rowid'])) {
	$action = db::update('c_extra_tours', array('is_active' => $_POST['activestatus']), array('id' => $_POST['rowid']));
	if($action) {
		die('true');
	}else {
		die('false');
	}
}

if(isset($_POST["tableitems"])) {
	if(!isset($_SERVER["HTTP_REFERER"])) die("don't do that");
	foreach($_POST["tableitems"] as $id) {
		$action = db::delete("c_hotels", array("id" => $id));
		$action = true;
		if(!$action) {
			send("The item couldn't deleted.", "error");
		}
	}
	send("Successfully Deleted", "success", "crud.php?c=tours");
}
?>
<a class="btn black left" href="?c=extra_tours&p=new">Add</a>
<?php $datatables=TRUE; ?>
<script type="text/javascript" charset="utf-8">
	$(document).ready(function() {
		$('#active').live("change", function(){
			$.post('?c=extra_tours', { activestatus: (($(this).is(':checked')) ? '1' : '0'), rowid: $(this).attr('name') }, function(data) {
				if(data.match(/true$/)) {
					//sm_message('Tur başarıyla '+(($(this).is(':checked')) ? 'aktif' : 'pasif')+' konuma getirildi.', 'success');
				}else {
					//sm_message('Hata Oluştu', 'error');
				}
			});
		});
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
				aoData.push( { 'name': 'relation', 'value': 'lang = static.id => static.title' } );
				aoData.push( { 'name': 'relation', 'value': 'images = media.id => media.from' } );
				aoData.push( { "name": "c", "value": "c_extra_tours" } );
				aoData.push( { "name": "sSelect", "value": "id, is_active, lang, name, price" } );
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
				{"sClass": "option order1",  "sTitle": "Aktiflik durumu", "fnRender": function (id) {return '<div class="onoff"><input id="active" name="'+id.aData[0]+'" '+((id.aData[1] == '1') ? 'checked="checked"' : '')+' type="checkbox"><label class="check" for="active"></label></div>';}},
				{"sClass": "option order0",  "sTitle": "Opsiyonel turun aktif olduğu dil", "sType": "string"},
				{"sClass": "textbox order1",  "sTitle": "Optiyonel turun ismi", "sType": "string"},
				{"sClass": "textbox order3",  "sTitle": "Opsiyonel turun fiyatı", "sType": "string"},
				{"mDataProp": null, "sClass": "control center", "fnRender": function (events) {return '<a href="?c=extra_tours&p=edit&id='+events.aData[0]+'&createnew"><span class="ui-icon ui-icon-newwin"></span></a><a href="?c=extra_tours&p=edit&id='+events.aData[0]+'&viewonly"><span class="ui-icon ui-icon-search"></span></a><a href="?c=extra_tours&p=edit&id='+events.aData[0]+'"><span class="ui-icon ui-icon-pencil"></span></a><a confirm="You have selected 1 entry for permanent deletion. This action cannot be undone." href="?c=extra_tours&del='+events.aData[0]+'"><span class="ui-icon ui-icon-trash"></span></a>';}, "bSortable": false}
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