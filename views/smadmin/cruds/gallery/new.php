<?php if(count(get_included_files()) ==1) exit("Direct access not permitted."); $datatables=TRUE; $imgupload = TRUE; ?>
<?php $name = $_GET['name']; ?>
<script>
$(function () {
	$('ul.submenu li:eq(1) a').attr('href','?c=gallery&p=new&name=<?php echo $name; ?>');
	$('.mediaupload').each(function(index) {           
		var uploader = new qq.FileUploader({
			element: $('.mediaupload:eq('+index+')')[0],
			multiple: true,
			action: 'media.php',
			buttonText: $(this).text(),
			debug: false,
			params: {uploadtype: 'all', from: 'gallery-<?php echo $name; ?>'},
			showMessage: function (message) {sm_message(message, "error");},
			onProgress: function(id, fileName, loaded, total){
				if($('.mediaupload:eq('+index+')').find('ul.qq-upload-list li:eq('+id+') .percentage').length>0) {
					$('.mediaupload:eq('+index+')').find('ul.qq-upload-list li:eq('+id+') .percentage').css('width', Math.round(loaded / total * 100)+'%');
				}else {
					$('.mediaupload:eq('+index+')').find('ul.qq-upload-list li:eq('+id+')').prepend('<span class="percentage"></span>');
				}
			},
			onComplete: function(id, fileName, responseJSON){
				$('.mediaupload:eq('+index+')').find('ul.qq-upload-list li:eq('+id+') .percentage').remove();
				$('.mediaupload:eq('+index+')').find('ul.qq-upload-list li:eq('+id+')').html('<a href="'+responseJSON.filename+'">'+fileName+'</a> uploaded successfully!');
				if ($('.mediaupload li:not(.qq-upload-success)').length==0) {
					sm_message('Bütün yüklemeler tamamlandı.', 'success');
					$('.mediaupload').after('<a class="btn success clear topmargin left" href="?c=gallery&p=edit&name=<?php echo $name; ?>">&larr; geri dön</a>');
				} 
			},
		});
		//var file_input = false;
		//uploader._button._options.onChange = function(input) { file_input = input; console.log(input.files);  return false; }
		//$(this).next('.send').click(function(){   uploader._onInputChange(file_input);});
	});
});
</script>
<div class="mediaupload">Upload New Images</div>