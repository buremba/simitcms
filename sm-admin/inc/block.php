<?php
$mysql_host = 'localhost';
$mysql_user = 'root';
$mysql_user_pass = '';
$mysql_dbname= 'plazma';
/*  */
mysql_connect($mysql_host, $mysql_user, $mysql_user_pass) or die("MySQL Hata: " . mysql_error());
mysql_select_db($mysql_dbname);
@mysql_query("SET NAMES 'utf8'");

$directory = 'http://localhost/plazma';
/*
List BLocks Which Have This Cat Id.

here is an example settings:
define("CAT_LIST", "1");
$cms_blocks= array('before' => "<li>", 'after' => '</li>'); */

// listelenen block'lara tıklanıldığında açılacak sayfa
if(isset($_GET["sayfa"]) && !isset($full)) {
$id = $_GET["sayfa"];
echo '<div style="padding:5px;>"';
$sor = mysql_query("SELECT * FROM blocks WHERE block_id='$id'");
while ($veri = mysql_fetch_assoc($sor)) {
	if ($veri["block_show"]==0) {echo  "yetkiniz bulunmamaktadır";}else {
			echo '<h2>'.$veri["block_title"].'</h2>';
			echo $veri["block_content"];
		}
	}
	echo '</div>';
$full=TRUE;
}
elseif(isset($cms_catlist) and !isset($full)) {
$cat=NULL;
$id = (int) $cms_catlist;
$blocks = mysql_query("SELECT block_id, block_title, block_show, setting_name, setting_value, setting_id FROM `blocks` RIGHT JOIN settings ON blocks.block_cat = settings.setting_id WHERE settings.setting_owner = 'block_category' AND (setting_value = '74' OR setting_value LIKE '74,%') AND block_show=1 ORDER BY setting_value ASC, block_order ASC");
if (isset($cms_blocks['list_b']) && isset($cms_blocks['list_a'])) {echo $cms_blocks['list_b']."\n";}
while ($veri = mysql_fetch_assoc($blocks)) {
if($veri['setting_id']!==$cat) {
if(in_array($cat, explode(',', $veri["setting_value"]))) {
if (isset($cms_blocks['subcat_b']) && isset($cms_blocks['subcat_a'])) {echo $cms_blocks['subcat_b'].$veri["setting_name"].$cms_blocks['subcat_a']."\n";}
}else {
if (isset($cms_blocks['header_b']) && isset($cms_blocks['header_b'])) {echo $cms_blocks['header_b'].$veri["setting_name"].$cms_blocks['header_a']."\n";}
}
}
	if (isset($cms_blocks['block_b']) && isset($cms_blocks['block_a'])) {echo $cms_blocks["block_b"];}
	echo  '<a href="?sayfa='.$veri["block_id"].'">'.$veri["block_title"].'</a>';
	if (isset($cms_blocks['block_b']) && isset($cms_blocks['block_a'])) {echo $cms_blocks["block_a"]."\n";}
	
$cat = $veri['setting_id'];
	}
if (isset($cms_blocks['list_b']) && isset($cms_blocks['list_a'])) {echo $cms_blocks['list_a'];}
}

/* tek başına block çalıştırıldığında Örnek: 
$cms_block = 3;
$cms_blockset = array('header_b' => '<li>', 'header_a' => '</li>', 'sort' => 200); 
*/
elseif(isset($cms_block)) {
$id = (int) $cms_block;
echo '<div style="padding:5px;>"';
$sor = mysql_query("SELECT * FROM blocks WHERE block_id='$id'");
while ($veri = mysql_fetch_assoc($sor)) {
if($veri["block_show"]==0) {echo  "yetkiniz bulunmamaktadır";}else{
			if (isset($cms_blockset["header_b"]) && isset($cms_blockset["header_a"])) {echo $cms_blockset["header_b"].$veri["block_title"].$cms_blockset["header_a"];}
			echo  $veri["block_content"];
		}
	}
	echo '</div>';
}

//else {echo "bu sayfa tek başına çağırılamaz.";}


?>