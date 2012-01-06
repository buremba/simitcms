<?php if(count(get_included_files()) ==1) exit("Direct access not permitted.");

$write = NULL;
$write='
<?php
if(isset($_GET["del"]) && is_numeric ($_GET["del"])) {
	if(!isset($_SERVER["HTTP_REFERER"])) die("don\'t do that");
	$action = db::delete("c_'.$name.'", array("id" => $_GET["del"]));
	if($action) {
	send("Successfully Deleted", "success", "crud.php?c='.$name.'");
	}else {
	send("The item couldn\'t deleted.", "error");
	}
}
?>
';
$write.=<<<html
<a class="btn black left" href="?c={$name}&p=new">Add</a>
<?php \$datatables=TRUE; ?>
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
			
html;
foreach($columns['cols'] as $i => $column) {
	if($_POST["type-$i"] == 'selectbox' && $_POST["selecttype-{$i}"]=='dynamic') {
	$write.= "\t\t\t"."aoData.push( { 'name': 'relation', 'value': '{$column['name']} = {$_POST["select-fetchtable$i"]}.id => {$_POST["select-fetchtable$i"]}.{$_POST["select-fetchtable-column$i"]}' } );";
	}
}
$write.=<<<html
			aoData.push( { "name": "c", "value": "c_{$name}" } );
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

html;
					foreach($columns['cols'] as $i => $column) {
					$options=null;
					if(!isset($_POST["showlist{$i}"])) {$options.='"bVisible": false,';}
					if($_POST["type-$i"] == 'image')	{$write.= "\t\t\t\t".'{"sClass": "image order'.$i.'", '.$options.' "sTitle": "'.$_POST["name-{$i}"].'", "sType": "html", "sWidth": "100px", "fnRender": function (imgurl) {return \'<a class="uigallery" href="\'+imgurl.aData[this.iDataSort]+\'"><img class="thumb" src="\'+imgurl.aData[this.iDataSort]+\'"></a>\';}},';}
					if($_POST["type-$i"] == 'textarea')	{$write.= "\t\t\t\t".'{"sClass": "textarea order'.$i.'", '.$options.' "sTitle": "'.$_POST["name-{$i}"].'", "bSearchable": false, "sType": "html"},'."\n";}
					if($_POST["type-$i"] == 'selectbox'){$write.= "\t\t\t\t".'{"sClass": "option order'.$i.'", '.$options.' "sTitle": "'.$_POST["name-{$i}"].'", "sType": "string"},'."\n";}
					if($_POST["type-$i"] == 'file')		{$write.= "\t\t\t\t".'{"sClass": "files order'.$i.'", '.$options.'   "sTitle": "'.$_POST["name-{$i}"].'", "sWidth": "100px", "fnRender": function (file) {return "<a href=\'"+imgurl.aData[this.iDataSort]+"\'>";}},'."\n";}
					if($_POST["type-$i"] == 'date')		{$write.= "\t\t\t\t".'{"sClass": "date order'.$i.'", '.$options.' "sTitle": "'.$_POST["name-{$i}"].'", "sType": "html", "sWidth": "100px", "fnRender": function (timestamp) { return $.formatdate("d F Y / H:i", new Date(parseInt(timestamp.aData[this.iDataSort])*1000));}},'."\n";}
					if($_POST["type-$i"] == 'textbox')	{$write.= "\t\t\t\t".'{"sClass": "textbox order'.$i.'", '.$options.' "sTitle": "'.$_POST["name-{$i}"].'", "sType": "string"},'."\n";}
					}
					
$write.=<<<html
				{"mDataProp": null, "sClass": "control center", "fnRender": function (events) {return '<a href="?c={$name}&p=edit&id='+events.aData[0]+'&createnew"><span class="ui-icon ui-icon-newwin"></span></a><a href="?c={$name}&p=edit&id='+events.aData[0]+'&viewonly"><span class="ui-icon ui-icon-search"></span></a><a href="?c={$name}&p=edit&id='+events.aData[0]+'"><span class="ui-icon ui-icon-pencil"></span></a><a confirm="You have selected 1 entry for permanent deletion. This action cannot be undone." href="?c={$name}&del='+events.aData[0]+'"><span class="ui-icon ui-icon-trash"></span></a>';}, "bSortable": false}
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
html;

?>