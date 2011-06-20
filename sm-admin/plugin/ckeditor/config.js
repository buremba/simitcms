CKEDITOR.editorConfig = function( config )
{
config.toolbar = 'Default';
config.filebrowserBrowseUrl = '../admin/gallery.php';
config.filebrowserImageBrowseUrl = '../admin/gallery.php';
config.filebrowserUploadUrl = '../admin/gallery.php';
config.scayt_autoStartup = false;

config.toolbar_Default =
[
    ['Source'],
	['Maximize'],
    ['Paste','PasteText','PasteFromWord','-','Templates'],
    ['Undo','Redo','-','RemoveFormat'],
    ['BidiLtr', 'BidiRtl'],
    ['Outdent','Indent','Blockquote'],
    ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
    ['Link','Unlink'],
    ['Image',,'Table','HorizontalRule'],
    '/',
	['Bold','Italic','Underline','Strike'],
	['NumberedList','BulletedList'],
    ['Styles','Format','Font','FontSize'],
    ['TextColor','BGColor'],
];

config.toolbar_Basic =
[
    ['Bold', 'Italic', '-', 'NumberedList', 'BulletedList', '-', 'Link', 'Unlink']
];


};

