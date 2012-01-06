<script type="text/javascript">
$(function() {
	$("div#uploader").pluploadQueue({
		runtimes : 'html5,gears,flash,silverlight,browserplus',
		url : '<?php echo SM_SCRIPTPATH.'admin/media/upload' ?>',
		max_file_size : '15mb',
		filters : [
			{title : "Image files", extensions : "jpg,gif,png"},
			{title : "Zip files", extensions : "zip"}
		],

		flash_swf_url : '<?php echo SM_SCRIPTPATH ?>media/js/plupload/js/plupload.flash.swf',
		silverlight_xap_url : '<?php echo SM_SCRIPTPATH ?>media/js/plupload/js/plupload.silverlight.xap'
	});

	$('form#uploader').submit(function(e) {
        var uploader = $('#uploader').plupload('getUploader');

        if (uploader.files.length > 0) {
            uploader.bind('StateChanged', function() {
                if (uploader.files.length === (uploader.total.uploaded + uploader.total.failed)) {
                    $('form')[0].submit();
                }
            });
                
            uploader.start();
        } else
            alert('You must at least upload one file.');
        return false;
    });
});
</script>
<div class="mediaupload">Upload</div>
<script type="text/javascript" charset="utf-8">
	$(document).ready(function() {
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
			"sAjaxSource": "<?php echo SM_SCRIPTPATH.'admin/cruds/ajax/'; ?>",
			"fnServerData": function ( sSource, aoData, fnCallback ) {
			aoData.push( { "name": "c", "value": "media" } );
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
				{"sClass": "files",  "sTitle": "Medya", "sWidth": "150px", "bSearchable": true, "fnRender": function (imgurl) {if(imgurl.aData[2]=='JPG' || imgurl.aData[2]=='PNG' || imgurl.aData[2]=='JPEG' || imgurl.aData[2]=='GIF') {return "<a class='uigallery' href='<?php echo SITE_ADDRESS.'/uploads/' ?>"+imgurl.aData[this.iDataSort]+"'><img class=\'largethumb image\' src='"+thumburl('<?php echo SITE_ADDRESS.'/uploads/' ?>'+imgurl.aData[this.iDataSort])+"'></a>";}else {return '<a class="'+imgurl.aData[2].toLowerCase()+'" href="<?php echo SITE_ADDRESS.'/uploads/' ?>'+imgurl.aData[this.iDataSort]+'">';}}},
				{"sClass": "option order1",  "sTitle": "Mimetype", "sType": "string", "fnRender": function (url) {var urla = url.aData[1].split('/'); urla = urla[urla.length-2].replace("'><", ''); return urla+'<br>'+url.aData[this.iDataSort]}},
				{"sClass": "date order2",  "sTitle": "Eklendiği Bölüm", "sType": "html"},
				{"sClass": "date order2",  "sTitle": "Kullanıcı", "sType": "html"},
				{"sClass": "date order2",  "sTitle": "Yüklenen Tarih", "fnRender": function (timestamp) {return $.formatdate("d F Y / H:i", new Date(parseInt(timestamp.aData[this.iDataSort])*1000));}},
				{"mDataProp": null, "sClass": "control center", "fnRender": function (events) {return '<a onclick="if(!confirm(\'Satır geri dönüşümsüz bir şekilde silinecek, devam etmek istiyor musunuz?\')) {return false;}" href="media/delete/'+events.aData[0]+'"><span class="ui-icon ui-icon-trash"></span></a>';}, "bSortable": false}
			]
		} );
		$('.batchopt').each(function(index) {
			var aPos = oTable.fnGetPosition( this );
			var aData = oTable.fnGetData( this.parentNode );
		} );
	} );
</script>
<form id="uploader">
	<div id="uploader">
		<p>You browser doesn't have Flash, Silverlight, Gears, BrowserPlus or HTML5 support.</p>
	</div>
</form>

<form method="POST" id="batchprocess" class="media" action="media.php">
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