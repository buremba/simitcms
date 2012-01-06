<a class="btn black left" href="?c=events&p=new">Add</a>
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
						aoData.push( { "name": "c", "value": "c_events" } );
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
				{"sClass": "option order0",  "sTitle": "Dil", "sType": "string"},
				{"sClass": "textbox order1",  "sTitle": "Etkinlik İsmi", "sType": "string"},
				{"sClass": "date order2",  "sTitle": "Tarih Aralığı", "sType": "html", "sWidth": "100px", "fnRender": function (timestamp) { return $.formatdate("d F Y / H:i", new Date(parseInt(timestamp.aData[this.iDataSort])*1000));}},
				{"sClass": "textarea order3", "bVisible": false, "sTitle": "Etkinlik Açıklaması", "bSearchable": false, "sType": "html"},
				{"sClass": "option order4", "bVisible": false, "sTitle": "Galerisi", "sType": "string"},
				{"sClass": "textbox order5",  "sTitle": "Meta Anahtarları", "sType": "string"},
				{"sClass": "option order6", "bVisible": false, "sTitle": "Meta Açıklaması", "sType": "string"},
				{"mDataProp": null, "sClass": "control center", "fnRender": function (events) {return '<a href="?c=events&p=edit&id='+events.aData[0]+'&createnew"><span class="ui-icon ui-icon-newwin"></span></a><a href="?c=events&p=edit&id='+events.aData[0]+'&viewonly"><span class="ui-icon ui-icon-search"></span></a><a href="?c=events&p=edit&id='+events.aData[0]+'"><span class="ui-icon ui-icon-pencil"></span></a><a confirm="You have selected 1 entry for permanent deletion. This action cannot be undone." href="?c=events&del='+events.aData[0]+'"><span class="ui-icon ui-icon-trash"></span></a>';}, "bSortable": false}
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