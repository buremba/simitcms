<a class="btn black left" href="?c=tours&p=new">Add</a>
<script type="text/javascript" charset="utf-8">
	$(document).ready(function() {
		$('#active').live("change", function(){
			$.post('?c=tours', { activestatus: (($(this).is(':checked')) ? '1' : '0'), rowid: $(this).attr('name') }, function(data) {
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
				$(this).parents('.dataTables_wrapper').find('.fg-toolbar:first').prepend('<div class="batchprocess hide"><span class="ui-icon ui-icon-arrowreturn-1-s"></span><input type="submit" confirm="Do you really want to delete selected items?" class="btn grey" style="padding:3px" value="Delete Selected Entries" name="batchdel"></div>');
				$(this).parents('.dataTables_wrapper').find('.fg-toolbar:last').prepend('<div class="batchprocess hide"><span class="ui-icon ui-icon-arrowreturn-1-e"></span><input type="submit" confirm="Do you really want to delete selected items?" class="btn grey" style="padding:3px" value="Delete Selected Entries" name="batchdel"></div>');
			},
			"oLanguage": oLanguage_tr,
			"sAjaxSource": "<?php echo SM_SCRIPTPATH.'admin/cruds/tours/ajax'; ?>",
			"fnServerData": function ( sSource, aoData, fnCallback ) {
				aoData.push( { 'name': 'relation', 'value': 'lang = static.id => static.column' } );
				aoData.push( { 'name': 'relation', 'value': 'images = media.id => media.from' } );
				aoData.push( { 'name': 'relation', 'value': 'country = static.id => static.content' } );
				aoData.push( { "name": "c", "value": "c_tours" } );
				aoData.push( { "name": "sSelect", "value": "id, lang, is_active, code_no, name, country, first_day, last_day" } );
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
				{"sClass": "option order0",  "sTitle": "Turun dili", "sType": "string"},
				{"sClass": "option order1",  "sTitle": "Aktiflik durumu", "fnRender": function (id) {return '<div class="onoff"><input id="active" name="'+id.aData[0]+'" '+((id.aData[2] == '1') ? 'checked="checked"' : '')+' type="checkbox"><label class="check" for="active"></label></div>';}},
				{"sClass": "textbox order2",  "sTitle": "Turun satış kodu", "sType": "string"},
				{"sClass": "textbox order3",  "sTitle": "Turun adı", "sType": "string"},
				{"sClass": "option order10",  "sTitle": "Turun Ülkesi", "sType": "string"},
				{"sClass": "date order16",  "sTitle": "Satış tarihi başlangıcı", "sType": "html", "sWidth": "100px", "fnRender": function (timestamp) { return $.formatdate("d F Y / H:i", new Date(parseInt(timestamp.aData[this.iDataSort])*1000));}},
				{"sClass": "date order17", "bVisible": false, "sTitle": "Satış tarihi bitişi", "sType": "html", "sWidth": "100px", "fnRender": function (timestamp) { return $.formatdate("d F Y / H:i", new Date(parseInt(timestamp.aData[this.iDataSort])*1000));}},
				{"mDataProp": null, "sClass": "control center", "fnRender": function (events) {return '<a href="?c=tours&p=edit&id='+events.aData[0]+'&createnew"><span class="ui-icon ui-icon-newwin"></span></a><a href="?c=tours&p=edit&id='+events.aData[0]+'&viewonly"><span class="ui-icon ui-icon-search"></span></a><a href="?c=tours&p=edit&id='+events.aData[0]+'"><span class="ui-icon ui-icon-pencil"></span></a><a confirm="You have selected 1 entry for permanent deletion. This action cannot be undone." href="?c=tours&del='+events.aData[0]+'"><span class="ui-icon ui-icon-trash"></span></a>';}, "bSortable": false}
			]
		} );
		$('.batchopt').each(function(index) {
			var aPos = oTable.fnGetPosition( this );
			var aData = oTable.fnGetData( this.parentNode );
		} );
	} );
</script>

<form method="POST" id="batchprocess" action="media/delete">
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