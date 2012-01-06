	<a class="btn orange" href="?newuser">Create new user</a>
	<a class="btn purple right leftmargin" href="?usergroups">User Groups</a>
	<a class="btn orange right" href="?profile">Edit my profile</a>
	<script type="text/javascript" charset="utf-8">
	$(document).ready(function() {
		oTable  = $('#crud').dataTable( {
			"bProcessing": true,
			"bServerSide": true,
			"bJQueryUI": true,
			"fnInitComplete": function(oSettings, json) {
				$(this).parents('.dataTables_wrapper').find('.fg-toolbar:first').prepend('<div class="batchprocess hide"><span class="ui-icon ui-icon-arrowreturn-1-s"></span><input type="submit" confirm="Do you really want to delete $(\'.batchopt:checked\').length" class="btn grey" style="padding:3px" value="Seçilileri Sil" name="batchdel"></div>');
				$(this).parents('.dataTables_wrapper').find('.fg-toolbar:last').prepend('<div class="batchprocess hide"><span class="ui-icon ui-icon-arrowreturn-1-e"></span><input type="submit" confirm="Do you really want to delete $(\'.batchopt:checked\').length" class="btn grey" style="padding:3px" value="Seçilileri Sil" name="batchdel"></div>');
			},
			"oLanguage": oLanguage_tr,
			"sAjaxSource": "cruds/default/default.php",
			"fnServerData": function ( sSource, aoData, fnCallback ) {
			aoData.push( { "name": "c", "value": "users" } );
			aoData.push( { "name": "relation", "value": "user_group = settings.setting_id => settings.setting_value" } );
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
				{"sClass": "text order0",  "sTitle": "Username", "sType": "string"},
				{"sClass": "text order0",  "sTitle": "Password Hash", "sType": "string", "bVisible" :false},
				{"sClass": "text order0",  "sTitle": "Email", "sType": "string"},
				{"sClass": "text order0",  "sTitle": "User Group", "sType": "string"},
				{"sClass": "date order0",  "sTitle": "Last Login", "sType": "timestamp", "fnRender": function (timestamp) {if(timestamp.aData[this.iDataSort]=="0") {return "Not login yet";} else {return $.formatdate("d F Y / H:i", new Date(parseInt(timestamp.aData[this.iDataSort])*1000));}}},
				{"sClass": "date order0",  "sTitle": "Created in", "sType": "timestamp", "fnRender": function (timestamp) {return $.formatdate("d F Y / H:i", new Date(parseInt(timestamp.aData[this.iDataSort])*1000));}},
				{"mDataProp": null, "sClass": "control center", "fnRender": function (events) {return '<a href="?edituser='+events.aData[0]+'&viewonly"><span class="ui-icon ui-icon-search"></span></a><a href="?edituser='+events.aData[0]+'"><span class="ui-icon ui-icon-pencil"></span></a><a confirm="Satýr geri dönüþümsüz bir þekilde silinecek, devam etmek istiyor musunuz?" {return false;}" href="?userdel='+events.aData[0]+'"><span class="ui-icon ui-icon-trash"></span></a>';}, "bSortable": false}
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