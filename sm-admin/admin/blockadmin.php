<?php
include("../../config.php");
/*-----Config Settings-----*/

$message[1]='Yeni Blok Başarıyla Oluşturuldu.';
$message[2]='Girdi Başarıyla Değiştirildi';
$message[3]='Girdi Başarıyla Silindi';
$message[4]='Ayarlar Başarıyla Düzenlendi';

/*-----Config Settings-----*/

$not = notification($message);
if(!isset($_GET["newblock"]) && empty($_GET["editblock"]) && !isset($_GET["setting"]) && !isset($_POST["order"])  && !isset($_POST["catid"])){

$ic.="<a class='button orange' style='padding:5px 16px 6px 16px;' href='?newblock'>New Block</a>";

$ask = mysql_query("SELECT block_id, block_title, block_show, setting_name, setting_value, setting_id FROM `blocks` RIGHT JOIN settings ON blocks.block_cat = settings.setting_id WHERE settings.setting_owner = 'block_category' ORDER BY setting_value ASC, block_order ASC");
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


$noncat = mysql_result(mysql_query("SELECT count(*) FROM `blocks` WHERE block_cat = 0"), 0);
if ($noncat!=0) {
$ask = mysql_query("SELECT block_id, block_title, block_content, block_show FROM `blocks` WHERE block_cat = 0 ORDER BY block_order ASC");
$ic.='</div>';
$ic.='<div id="block-cat">';
$ic.='
<h2 class="listcat">
<span class="cat" id="0"><span>Kategorilenmemiş</span></span>
<span class="toggle" style="float:right; margin-left:15px; cursor:pointer;"><img title="Bu sekmeyi aç-kapa" src="images/bullet_arrow_down.png"></span>
<span class="catcode" style="float:right;  margin-left:5px; cursor:pointer;"><a id="catcode" href="#data"><img title="Bu kategorideki block\'ları listeleyin" src="images/list.png"></a></span>
</h2>';
$ic.='<div class="blocks" id="0">';
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
	
$code = highlight_string('<?php
define("CAT_LIST", "%");
$list_set= array("before" => "<li>", "after" => "</li>");
?>', TRUE);

$code = explode('%', $code);
$code = $code[0]."<span id='catvar'></span>".$code[1];

$ic.='
<div style="display:none">
<div id="data">'.$code.'</div></div>';


}

if(isset($_GET["deleteblock"])) {
$del = (int) $_GET["deleteblock"];
ob_start();
mysql_query('DELETE FROM blocks WHERE block_id="'.$del.'"') or die(header('Location: ?message=7&error='.urlencode(mysql_error())));
header('Location: ?message=3');
}

if(isset($_GET["newblock"])) {
if(isset($_POST["blockname"])) {
$blockname = $_POST["blockname"];
$blockcontent = $_POST["blockcontent"];
if (isset($_POST["blockshow"])) {$blockshow = (int) $_POST["blockshow"];} else {$blockshow = 1;}
$cat = (int) $_POST["cat"];
mysql_query("INSERT INTO blocks (block_title, block_content, block_show, block_cat) VALUES ('$blockname','$blockcontent','$blockshow','$cat')");
ob_start();
header('Location: blockadmin.php?message=1');
}
else{
$textarea=TRUE;
$ic.='
<form method="POST" value="">
<input type="text" name="blockname" class="normal">
<textarea class="ckeditor" cols="80" id="editor1" name="blockcontent"></textarea>
';

$ic.='
<div id="show" style="float:right;">
<input type="radio" name="blockshow" value="1" id="yes"><label for="yes">Yayınlansın</label>
<input type="radio" name="blockshow" value="0" id="no"><label for="no">Yayınlanmasın</label></div>';

$sql = mysql_query("SELECT * FROM settings WHERE setting_owner = 'block_category'");
$ic.='<select name="cat">';
$ic.='<option value="0">Kategori Seçilmedi</option>';
while($cat = mysql_fetch_assoc($sql)) {
$ic.='<option value="'.$cat["setting_id"].'">'.$cat["setting_name"].'</option>';
}
$ic.='</select>';

$ic.='
<input type="submit" value="Gönder">
</form>
';
}
}

if(isset($_GET["editblock"])) {
if(isset($_POST["edittitle"])) {
$blockid = $_POST["blockid"];
$edittitle = $_POST["edittitle"];
$editcontent = $_POST["editcontent"];
$editshow = $_POST["editshow"];
mysql_query("UPDATE blocks SET block_title='$edittitle', block_content='$editcontent', block_show='$editshow' WHERE block_id='$blockid'") or die("Can't create new entry");
ob_start();
header('Location: blockadmin.php?message=2');
}
else {
$textarea=TRUE;
$blockid = $_GET["editblock"];
$ask = mysql_query("SELECT * FROM blocks WHERE block_id='$blockid'");
while ($edit = mysql_fetch_assoc($ask)) {
$ic.=<<<html
<form method="POST" value="">
<input type="hidden" name="blockid" value="{$blockid}">
<input type="text" style="width:400px; font-size:1.4em; padding:6px 8px;" name="edittitle" value="{$edit["block_title"]}">
<a href="?deleteblock={$blockid}" onclick="return confirm('Bu block geri dönüşümsüz biçimde silinecek, emin misiniz?')" style="float:right; margin:5px;"><img src="images/note_delete.png"></a>
<textarea class="ckeditor" cols="80" id="editor1" name="editcontent">{$edit["block_content"]}</textarea>
<div id="show" style="float:right;"><input type="radio" name="editshow"
html;
if($edit["block_show"]==1){$ic.='checked="checked"';} $ic.=' value="1" id="yes"><label for="yes">Yayınlansın</label><input type="radio" name="editshow" ';
if($edit["block_show"]==0){$ic.='checked="checked"';} $ic.=' value="0" id="no"><label for="no">Yayınlanmasın</label></div>
<input class="awesome" type="submit" value="Gönder">
</form>';
$ic.="<a href='#data' id='code' style='float:right; margin:5px; height:16px; background:url(images/bug.png) right center no-repeat; padding-right:18px;'>Code</a>";

$code= highlight_string("<?php
define('BLOCK', $blockid);
?>", TRUE);

$ic.='
<div style="display:none">
<div id="data">'.$code.'</div></div>';
}
}
}

if(isset($_GET["catdelete"])){
$id = $_GET["catdelete"];
$sql = mysql_query("DELETE FROM settings WHERE setting_id = '$id' and setting_owner='block_category'");
$sqli = mysql_query("UPDATE blocks SET `block_cat` = '0' WHERE block_cat = '$id' ;");
if($sql && $sqli) {$not.= "$id numaralı kategori silindi.";}else {$not.= "Mysql Error".mysql_error();}
}
if (isset($_GET["createcat"])){
$name = $_GET["createcat"];
$sql = mysql_query("INSERT INTO settings (`setting_id`, `setting_name`, `setting_value`, `setting_owner`) VALUES (NULL, '$name', '0', 'block_category');");
if($sql) {$not.="Yeni Kategori '<b>$name</b>' başarıyla oluşturuldu!";} else {$not.="Mysql Sorgusu Çalıştırılamadı:";}
}

if (isset($_GET["setting"]) || isset($_GET["ajax"])) {
$head.=<<<html

<script type="text/javascript" >
$(function(){
	$( ".block > h2" ).hover(function() {
	$(this).addClass('collapse');
		}, function(){
			$(this).removeClass('collapse');
		});
	
	$( "span.toggle" ).click(function() {
		$(this).parent().parent().find('.block').toggle();
		});

	$('.blocks').sortable({
		connectWith: '.blocks',
		handle: 'img',
		cursor: 'move',
		placeholder: 'placeholder',
		forcePlaceholderSize: true,
		opacity: 0.4,
		stop: function(event, ui){
			$(ui.item).find('img').live('click', function() {});
			var sortorder='';
			$('.blocks').each(function(){
				var itemorder=$(this).sortable('toArray');
				var columnId=$(this).attr('id');
				sortorder+=columnId+'='+itemorder.toString()+'#';
			});
			$.ajax({
					type: 'POST',
					url: 'blockadmin.php',
					data: 'order='+sortorder,
					error: function(ajaxCevap) {
						$(".box").addClass("fail");
						$('.box').html('Hata Oluştu');
					},
					success: function(ajaxCevap) {
						$('.box').addClass('success');
						$('.box').html(ajaxCevap);
					}
	});
		}
	})
	.disableSelection();
});
</script>
html;

$ic.='
<div id="dialog" style="display:none;" title="Kategoriyi Listeleyin">

define("CAT_LIST", "<span id="catname"></span>");<br>
$list_set= array("before" => "&#60;li&#62;", "after" => "&#60;/li&#62;");<br>
</div>
';

$ic.=<<<html
<div id="createcat">
<h2 style="background:#CFCFCF; padding:5px;">Yeni Kategori Ekle</h2>
<input type="text"><input type="submit" style="float:right; margin-top:0;" onclick="createcat($(this).parent().find('input').val());" value="Oluştur">
</div>
html;

$ask = mysql_query("SELECT block_id, block_title, block_content, block_show FROM `blocks` WHERE block_cat = 0 ORDER BY block_order ASC");
$ic.='<div class="column" style="width:719px;">
<h2 class="edit">
<span class="cat" onclick="ilk($(this))">Kategorilendirilmemiş</span>
<span class="toggle" style="float:right; margin-left:15px; cursor:pointer;"><img title="Bu sekmeyi aç-kapa" src="images/bullet_arrow_down.png" ></span>
</h2>
<div class="blocks" id="0" style="min-height:62px">
';
while($blck = mysql_fetch_assoc($ask)) {
$ic.=<<<html
<div class="dragbox block" id="{$blck['block_id']}">
	<img src="images/note.png"><h2>{$blck['block_title']}</h2>
</div>
html;
}
$ic.="</div></div>";

$ask = mysql_query("SELECT block_id, block_title, block_content, block_show, setting_name, setting_value, setting_id FROM `blocks` RIGHT JOIN settings ON blocks.block_cat = settings.setting_id WHERE settings.setting_owner = 'block_category' ORDER BY setting_id ASC, block_order ASC");
$son=NULL;
$bid=0;
while($blck = mysql_fetch_assoc($ask)) {
if($blck["setting_name"]!==$son) {
if($bid!==0) {$ic.="</div></div>";}
$ic.='<div class="column">
<h2 class="edit">
<span class="cat" id="'.$blck["setting_id"].'" onclick="ilk($(this))"><span>'.$blck["setting_name"].'</span></span>
<span class="toggle" style="float:right; margin-left:15px; cursor:pointer;"><img title="Bu sekmeyi aç-kapa" src="images/bullet_arrow_down.png"></span>
<span class="delete" style="float:right;  margin-left:5px; cursor:pointer;"><a  onclick="catdelete('.$blck["setting_id"].')"><img title="Bu kategoriyi sil, block\'ları kategorisiz sekmesine taşı" src="images/delete.png"></a></span>
<span class="catcode" style="float:right;  margin-left:5px; cursor:pointer;"><img title="Bu kategorideki block\'ları listeleyin" src="images/list.png"></span>
</h2>
<div class="blocks" id="'.$blck["setting_id"].'">
';
}
if($blck["block_id"]!==NULL) {
$ic.=<<<html
<div class="dragbox block" id="{$blck['block_id']}">
	<img src="images/note.png"><h2>{$blck['block_title']}</h2>
</div>
html;
}
$son=$blck["setting_name"];
$bid++;
}
$ic.="</div></div>";
}

if(isset($_POST["order"])) {
$cats= explode("#", $_POST["order"]);
	for($i=0; $i<(count($cats)-1); $i++) {
		$both = explode("=", $cats[$i]);
		$cat = $both[0];
		$blocks = explode(",", $both[1]);
		for($z=0; $z<count($blocks); $z++) {
		if($blocks[$z]!=="") {$sql = mysql_query("UPDATE blocks SET block_cat='$cat', block_order='$z' WHERE block_id='$blocks[$z]';") or die("Mysql Error:".mysql_error());}
		}
	}
	echo "Başarıyla Düzenlendi!";
}
elseif (isset($_POST["catname"])){
$catname = $_POST["catname"];
$catid = $_POST["catid"];
$sql= mysql_query("UPDATE settings SET setting_name='$catname' WHERE setting_id='$catid' AND setting_owner='block_category';");
if($sql) {echo "Name Edited to '<b>$catname</b>'";}else {echo "Mysql Error".mysql_error();}
}
else{
include("../template.php");
}
?>