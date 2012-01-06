<?php

return array(
	'directory' => DOCROOT.'uploads',
	'url' => URL::base().'/uploads/',

	'types' => array(
	'all' => array("jpeg", "bmp", "jpg", "gif", "png", "pdf", "doc", "docx", "xls", "xslx", "zip", "rar", "tar", "tar.gz", "gzip", "ppt", "pptx", "rtf", "pps", "ppsx", "txt", "csv"),
	'img' => array("jpeg", "bmp", "jpg", "gif", "png"),
	'doc' => array("pdf", "doc", "docx", "xls", "xslx", "ppt", "pptx", "rtf", "pps", "ppsx", "txt", "csv"),
	'file' => array("zip", "rar", "tar", "tar.gz", "gzip")
	)
);