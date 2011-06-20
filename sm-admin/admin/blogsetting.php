<?php
include_once('../../config.php');

$head=<<<html
<script src="../codeedit.js" type="text/javascript"></script>
<script src="me.js" type="text/javascript"></script>
<style type="text/css" media="screen">
div#container { width:20%; height:100px; border:1px solid black; }
span.placeholder { border: 1px solid red }
</style>
html;

if(!$_GET) {
$ask = mysql_query("SELECT post_id, post_date, post_title FROM blog");
while($blck = mysql_fetch_assoc($ask)) {
}

$ic.='
<div style="float:left; height:400px; width:75%;">
<textarea style="width:100%; height:400px; float:left;" name="codeedit" class="codeedit html linenumbers" wrap="off" id="main_text">';

$ic.= htmlentities(('
<?php
define("CAT_LIST", "<span id="catname">{{dsa}}</span>");<br>
$list_set= array("before" => "&#60;li&#62;", "after" => "&#60;/li&#62;");
?>'), ENT_QUOTES, "UTF-8");

$ic.='
</textarea>
</div>
<div id="sf" style="float:right; width:20%; height:100px;"><input type="button" value="blog content" onclick="blogcontent();"></div>
';
}

include("../template.php");
?>