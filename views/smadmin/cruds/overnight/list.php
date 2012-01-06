<?php
if(count(get_included_files()) ==1) exit("Direct access not permitted.");

if(isset($_GET["del"]) && is_numeric ($_GET["del"])) {
	if(!isset($_SERVER["HTTP_REFERER"])) die("don't do that");
	$action = db::delete("c_overnight", array("id" => $_GET["del"]));
	if($action) {
	send("Successfully Deleted", "success", "crud.php?c=overnight");
	}else {
	send("The item couldn't deleted.", "error");
	}
}

if(isset($_POST["tableitems"])) {
	if(!isset($_SERVER["HTTP_REFERER"])) die("don't do that");
	foreach($_POST["tableitems"] as $id) {
		$action = db::delete("c_overnight", array("id" => $id));
		$action = true;
		if(!$action) {
			send("The item couldn't deleted.", "error");
		}
	}
	send("Successfully Deleted", "success", "crud.php?c=tours");
}
?>
<a class="btn black left" href="?c=overnight&p=new">Add</a>
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
						aoData.push( { 'name': 'relation', 'value': 'lang = static.id => static.title' } );			aoData.push( { "name": "c", "value": "c_overnight" } );
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
				{"sClass": "option order0",  "sTitle": "Ekstra Gecelemenin Gözükeceği Dil", "sType": "string"},
				{"sClass": "textbox order1",  "sTitle": "Ekstra Gecelemenin İsmi", "sType": "string"},
				{"sClass": "textbox order2",  "sTitle": "Ekstra Gecelemenin Ücreti", "sType": "string"},
				{"sClass": "textbox order3",  "sTitle": "the name value for 'de_discount'", "sType": "string"},
				{"sClass": "textbox order4", "bVisible": false, "sTitle": "the name value for 'cl_discount'", "sType": "string"},
				{"mDataProp": null, "sClass": "control center", "fnRender": function (events) {return '<a href="?c=overnight&p=edit&id='+events.aData[0]+'&createnew"><span class="ui-icon ui-icon-newwin"></span></a><a href="?c=overnight&p=edit&id='+events.aData[0]+'&viewonly"><span class="ui-icon ui-icon-search"></span></a><a href="?c=overnight&p=edit&id='+events.aData[0]+'"><span class="ui-icon ui-icon-pencil"></span></a><a confirm="You have selected 1 entry for permanent deletion. This action cannot be undone." href="?c=overnight&del='+events.aData[0]+'"><span class="ui-icon ui-icon-trash"></span></a>';}, "bSortable": false}
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