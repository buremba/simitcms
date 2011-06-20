<?php
include_once('../../config.php');

if(empty($_GET)) {
$sql=mysql_query("SELECT * FROM settings WHERE setting_owner='page'");
while($p = mysql_fetch_assoc($sql)) {
$ic.='
<h2 class="listcat"><span><a href="?p='.$p['setting_id'].'">'.$p['setting_name'].'</a></span></h2>
';
}
}

if(isset($_GET['p'])) {
$id = (int) $_GET['p'];
$customs = unserialize(mysql_result(mysql_query("SELECT setting_value FROM `settings` WHERE setting_owner = 'page' and setting_id='$id' ORDER BY setting_id ASC"), 0));
foreach ($customs as $custom) {
$ic.=$custom;
}
}
if(isset($_GET['pg'])) {
$id = (int) $_GET['pg'];

$ic.="<a class='awesome orange' style='padding:5px 16px 6px 16px;' href='?newblock'>New Block</a>";

$ask = mysql_query("SELECT block_id, block_title, block_show, setting_name, setting_value, setting_id FROM `blocks` RIGHT JOIN settings ON blocks.block_cat = settings.setting_id WHERE settings.setting_owner = 'block_category' and setting_extra='$id' ORDER BY setting_value ASC, block_order ASC");
$lastcat=NULL;
$anacat=NULL;
$bid=0;
while($blck = mysql_fetch_assoc($ask)) {
$curanacat=explode(',', $blck["setting_value"]);
$curanacat = $curanacat[0];
if($blck["setting_id"]!==$lastcat) {
if($bid!==0) {$ic.='</div>';}
if(in_array($anacat, explode(',', $blck["setting_value"]))) { // is this subcat?
for($i=0; $i<($lastcatcount)-(count(explode(',', $blck["setting_value"]))-1); $i++) {$ic.='</div>';}
$ic.='<div class="altcat">';
}
else{
if($bid!==0) {for ($i=0; $i<=substr_count($lastvalue, ','); $i++) {$ic.='</div>';}}
$ic.='<div id="block-cat">';
}
$ic.='
<h2 class="listcat">
<span class="cat" id="'.$blck["setting_id"].'"><span>'.$blck["setting_name"].'</span></span>
<span class="toggle" style="float:right; margin-left:15px; cursor:pointer;"><img title="Bu sekmeyi aç-kapa" src="images/bullet_arrow_down.png"></span>
<span class="catcode" style="float:right;  margin-left:5px; cursor:pointer;"><a id="catcode" href="#data"><img title="Bu kategorideki block\'ları listeleyin" src="images/list.png"></a></span>
</h2>';
$ic.='<div class="blocks" id="'.$blck["setting_id"].'">';
}

if($blck["block_id"]!==NULL) {
if($blck['block_show']==1) {$img='note';} else {$img='note_not';}
$ic.=<<<html
<div class="block" id="{$blck['block_id']}">
	<a href="?editblock={$blck['block_id']}">
		<img src="images/{$img}.png"><h2>{$blck['block_title']}</h2>
		<span style="float:right;">Delete</span>
	</a>
</div>
html;
}
$anacat=explode(',', $blck["setting_value"]);
$anacat = $anacat[0];
//$anacat=$blck["setting_value"];
$lastvalue=$blck["setting_value"];
$lastcat=$blck["setting_id"];
$lastcatcount= count(explode(',', $blck["setting_value"]));
$bid++;
}
$ic.="</div></div>";

$noncat = mysql_result(mysql_query("SELECT count(*) FROM `blocks` WHERE block_cat = 0"), 0);
if ($noncat!=0) {
$ask = mysql_query("SELECT block_id, block_title, block_content, block_show FROM `blocks` WHERE block_cat = 0 ORDER BY block_order ASC");
$ic.=<<<html
<div id="block-cat">
<h2 class="edit">
<span class="cat">Kategorilendirilmemiş</span>
<span class="toggle" style="float:right; margin-left:15px; cursor:pointer;"><img title="Bu sekmeyi aç-kapa" src="images/bullet_arrow_down.png"></span>
</h2>
<div class="blocks" id="0">
html;
while($blck = mysql_fetch_assoc($ask)) {
$ic.=<<<html
<a href="?editblock={$blck['block_id']}">
<div class="block" id="{$blck['block_id']}">
<img src="images/note.png"><h2>{$blck['block_title']}</h2>
</div>
</a>
html;
}
}

$ic.="<div style='clear:both;'></div></div></div>";

}

include("../template.php");
?>