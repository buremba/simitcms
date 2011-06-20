<?php
/*
bunu benden bir hediye kabul et, olur mu?
*/
$mysql_host = 'localhost';
$mysql_user = 'root';
$mysql_user_pass = '';
$mysql_dbname= 'cms';
/* */
mysql_connect($mysql_host, $mysql_user, $mysql_user_pass) or die("MySQL Hata: " . mysql_error());
mysql_select_db($mysql_dbname);
@mysql_query("SET NAMES 'utf8'");

class Load {
function cur_theme() {
return mysql_result(mysql_query('SELECT setting_value FROM settings WHERE setting_owner="theme_information" AND setting_name="current_theme" LIMIT 1'), 0);
}
	function view( $file_name, $data = null ) {
      if( is_array($data) ) {extract($data);}
      include 'templates/'.self::cur_theme().'/'.$file_name;
	}
}
$path = 'templates/'.Load::cur_theme().'/';

function smt_form ($id) {
$id = (int) $id;
$var=json_decode(mysql_result(mysql_query("SELECT setting_value FROM settings WHERE setting_owner='form_action' and setting_name='form$id'"), 0));
$form= new form('POST','form.php','normalclass', $var->name);

/*
$selectId = $form->addSelect('select test','açıkla', TRUE);
$form->addSelect_item($selectId,'item1val','bir');
$form->addSelect_item($selectId,'item2val','iki');
*/

foreach ($var->vars as $id => $var) {
	if($var->type=='text')	{$form->addTextBox('text_test','tx_value',$var->name);}
elseif($var->type=='mail') 	{$form->addTextBox('text_test','tx_value',$var->name);}
elseif($var->type=='textarea'){$form->addTextArea('ta_name','ta_value',$var->name, TRUE);}
elseif($var->type=='checkbox'){$form->addCheckBox('cb_name','cb_value','açıkla');}
elseif($var->type=='radio')	{$form->addRadioButton('rb_name','rb_value','açıkla');}
}
$form->addSubmitButton('name','Gönder');
echo $form->render();

if(isset($_POST['id'])) {
if(isset($_POST['id'])) {$id = (int) $_POST['id'];}
$var=json_decode(mysql_result(mysql_query("SELECT setting_value FROM settings WHERE setting_owner='form_action' and setting_name='form1'"), 0));
var_dump($var);
if($var->mail) {
$c=$var->name."'dan gelen geri bildiriminiz:";
foreach ($var->vars as $ids => $v) {$c.=$v->name.': '.htmlentities($_POST[$ids])."\n--------------\n";}
require_once('phpmailer.class.php');
$mail = new PHPMailer();
$mail->SetFrom("noreply@localhost.com", smt_siteinfo('name', FALSE));
$mail->AddAddress($var->send, 'Name');
$mail->Subject    = smt_siteinfo('name', FALSE)." '' formundan gelen geri bildirim";
$mail->AltBody    = "E-posta bildirimini admin panelinizden ayarlayabilirsiniz.";
$mail->MsgHTML($c);
if($mail->Send()) {$result=TRUE;}
}
elseif($var->db) {
$c=NULL;
foreach ($var->vars as $ids => $v) {$c.="'".mysql_real_escape_string(htmlentities($_POST[$ids]))."',";}
$sql=sprintf("INSERT INTO form$id VALUES (DEFAULT, %s DEFAULT)", $c);
if(@mysql_query($sql)) {$result=TRUE;}
}
if($result) {echo $var->result->ok;}else {$var->result->fail;}
}
}

function smt_siteinfo($what, $echo=TRUE) {
$c=NULL;
switch ($what) {
	case 'title':
		$c= mysql_result(mysql_query('SELECT setting_value FROM settings WHERE setting_owner="siteinfo" AND setting_name="SITE_TITLE" LIMIT 1'), 0);
		break;
	case 'name':
		$c= mysql_result(mysql_query('SELECT setting_value FROM settings WHERE setting_owner="siteinfo" AND setting_name="SITE_NAME" LIMIT 1'), 0);
		break;
	case 'description':
		$c= mysql_result(mysql_query('SELECT setting_value FROM settings WHERE setting_owner="siteinfo" AND setting_name="SITE_DESCRIPTION" LIMIT 1'), 0);
		break;
	case 'version':
		$c= mysql_result(mysql_query('SELECT setting_value FROM settings WHERE setting_owner="siteinfo" AND setting_name="VERSION" LIMIT 1'), 0);
		break;
	}
if($echo) {echo $c;}else {return $c;}
}

function smt_theme($what) {
global $path;
switch ($what) {
	case 'stylesheet_url':
		echo $path.'style.css';
		break;
	case 'template_url':
		echo $path;
		break;
	}
}

function smt_get($what) {
global $path;
	if($what=='header') {include($path.'header.php');}
elseif($what=='footer') {include($path.'footer.php');}
else  {include($path.$what.'.php');}
}

function addable_area($what) {
	if($what=='header') { return FALSE; }
elseif($what=='footer') { return FALSE; }
}

function smt_dynamicpage() {
if (isset($_GET['p'])) {
$id = (int) $_GET['p'];
$element = unserialize(mysql_result(mysql_query("SELECT setting_value FROM settings WHERE setting_owner='page' AND setting_id='$id'"), 0));
if		($element['type']=='custom'){ smt_custom($element['id']); }
elseif  ($element['type']=='form')	{ smt_form($element['id']); }
elseif  ($element['type']=='block') { smt_block_content($element['id']); }
}else {echo 'Burada birşey yok.';}
}

function smt_page_title() {
if (isset($_GET['p'])) {
$id = (int) $_GET['p'];
echo @mysql_result(mysql_query("SELECT setting_name FROM settings WHERE setting_owner='page' AND setting_id='$id'"), 0);
}
}

function smt_page_list($var) {
$c = NULL;
$var = explode('%s', $var);
$sql = mysql_query('SELECT * FROM settings WHERE setting_owner="page"');
	while($page = mysql_fetch_assoc($sql)) {
	$c.=$var[0].$page['setting_name'].$var[1];
	}
echo $c;
}



function smt_block_content($which, $header=FALSE, $substr=FALSE) {
	$html=NULL;
	if(is_numeric($which)) {
	$sor = mysql_query("SELECT * FROM blocks WHERE block_id='$which'");
	}else {
	$sor = mysql_query("SELECT * FROM blocks WHERE block_title='$which'");
	}
	while ($veri = mysql_fetch_assoc($sor)) {
	if($veri["block_show"]==0) {echo  "Yetkiniz bulunmamaktadır";}
	else{
			if ($header) 			{$html.= '<h2>'.$veri["block_title"].'</h2>';}
			if (is_numeric($substr)){$html.=substr($veri["block_content"], 0, $substr);} else {$html.=$veri["block_content"];}
			echo $html;
		}
	}
}

function smt_custom($id) {
$temp = unserialize(mysql_result(mysql_query("SELECT setting_value FROM settings WHERE setting_owner='custom_layout' AND setting_name='custom$id'"), 0));
$varcount = count($temp);
$sor = mysql_query("SELECT * FROM custom$id");
$html = NULL;
	while ($veri = mysql_fetch_assoc($sor)) {
	for($i=0; $i<($varcount)-1; $i++) {
	$html.= $temp[$i];
	$html.= $veri[$i+1];
	}
	$html.= $temp[$varcount-1];
	}
echo $html;
}

function smt_block_list($catid, $maincat = '<ul>%s</ul>', $subcat = '<div style="margin-left:10px;">%s</div>', $header = '<h3 style="margin:2px 0;">%s</h3>', $subheader = '<h5 style="margin:2px 0;">%s</h5>', $block = '<li style="margin-left:0;">%s</li>', $blockheader=true) {
if(isset($_GET['s'])) {$id = (int) $_GET['s']; return smt_block_content($id, $blockheader);}
$html = NULL;
$maincat = explode('%s', $maincat);
$subcat = explode('%s', $subcat);
$header = explode('%s', $header);
$subheader = explode('%s', $subheader);
$block = explode('%s', $block);
$catid = (int) $catid;

$ask = mysql_query("SELECT block_id, block_title, block_show, setting_name, setting_value, setting_id FROM `blocks` RIGHT JOIN settings ON blocks.block_cat = settings.setting_id WHERE settings.setting_owner = 'block_category' AND (setting_value = '$catid' OR setting_value LIKE '$catid,%') AND block_show=1 ORDER BY setting_value ASC, block_order ASC");
$lastcat=NULL;
$anacat=NULL;
$bid=0;
while($blck = mysql_fetch_assoc($ask)) {
$curanacat=explode(',', $blck["setting_value"]); $curanacat = $curanacat[0];
if($blck["setting_id"]!==$lastcat) {
if(in_array($anacat, explode(',', $blck["setting_value"]))) { // is this subcat?
for($i=0; $i<($lastcatcount)-(count(explode(',', $blck["setting_value"]))-1); $i++) {$html.=$subcat[1];}
$html.=$subcat[0];
}
else{
if($bid!==0) {for ($i=0; $i<=substr_count($lastvalue, ','); $i++) {$html.=$main[1];;}}
}
$html.=$subheader[0].$blck["setting_name"].$subheader[1];
$html.=$maincat[0];
}

if($blck["block_id"]!==NULL) {
	$html.= $block[0];
	$html.= '<a href="?s='.$blck["block_id"].'">'.$blck["block_title"].'</a>';
	$html.= $block[1];
}

$anacat=explode(',', $blck["setting_value"]);
$anacat = $anacat[0];
$lastvalue=$blck["setting_value"];
$lastcat=$blck["setting_id"];
$lastcatcount= count(explode(',', $blck["setting_value"]));
$bid++;
}
echo $html;
}
?>