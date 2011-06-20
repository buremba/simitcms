<?php

function short($number, $content)
{
	if (strlen($content)>$number) {
	$text = substr($content, 0, $number);
	$text = $text.'[...]';
	return $text;
	}else {return $content;}
}

function searchsql($fields) // Create search sql code
{
	if(!isset($_GET["search"])) {return $sq=NULL;}
	
	$search = $_GET["search"];
	$sq='WHERE CONCAT (';
	foreach ($fields as $field) {
	$sq.='`'.$field['name'].'`,';
	}
	$sq = rtrim($sq, ",");
	$sq.=") LIKE '%$search%'";
	return $sq;
}

function filedir($file) {
$file = explode("/", $file);
unset($file[count($file)-1]);
$file = implode("/", $file);
return $file;
}

function filewrite($file, $content) {
if($handle = fopen($file, 'a')){
if(is_writable($file)){
if(fwrite($handle, $content) === FALSE){
$ic.= "I create the file <b>$file</b>, but I couldn't write my codes in this file. Please you copy paste the following codes in $file.<br>";
$ic.="<textarea>$content</textarea>";
exit;
}
echo "The file $file was created and written successfully!";
fclose($handle);
}
else{
$ic.= "I create the file <b>$file</b>, but I couldn't write my codes in this file. Please you copy paste the following codes in $file.<br>";
$ic.="<textarea>$content</textarea>";
exit;}
}else{
$ic.= "I couldn't create the file <b>$file</b>, because the directory is unwriteable. Please you create file <b>$file</b> and copy paste the following codes in $file.<br>";
$ic.="<textarea>$content</textarea>";
exit;}
}

class form {
var $method;
var $action;
var $class;
var $elements;
var $getStatus;
var $header;
  
function form($method,$action,$class, $header=FALSE) {
 $this->method = $method;
 $this->action = $action;
 $this->class = $class;
 $this->header = $header;
 $elements 	= array();
 $this->getStatus	= 0;
}

function addElement( $element ) {
 $element['id'] = $this->nextElementID();
 $location = $element['id'];
 $this->elements[$location]  = $element;
 return $element['id'];
}

function updateElement( $updatedElement ) {
 $id = $updatedElement['id'];
 $this->elements[$id]= $updatedElement;
}

function nextElementID() { return count( $this->elements ); }
public function ElementID()	 { return count( $this->elements )-1; }
function getElement($id) { return $this->elements[$id]; }
function numElements()   { return count( $this->elements ); }

function getNext() {
 if( $this->getStatus == $this->numElements() ) {return false;}
 $element = $this->getElement( $this->getStatus );
 $this->getStatus++;
 return $element;
}

function addSelect($name, $desc, $req=FALSE) {
 $element['type']   = 'select';
 $element['name']   = $name;
 $element['selections']   = array();
 $element['desc']  = $desc;
 $element['req']  = $req;
 $element['beginCode']= "<select name='{$element['name']}'>";
 $element['endCode'] = "</select>";
 
 return $this->addElement($element);
}

function addSelect_item($id,$val, $desc) {
 $element = $this->getElement($id);
 if( $element['type']!= 'select' ) {return false;}
 $selections = $element['selections'];
 $location = count( $selections );
 $selections[$location]['value']   = $val;
 $selections[$location]['desc']   = $desc;
 $selections[$location]['code']  = "\n<option value='{$selections[$location]['value']}'>{$selections[$location]['desc']}</option>";
 $element['selections']  = $selections;
 $this->updateElement($element);
}

function getSelectItems( $element ) {
 $c=NULL;
 if( $element['type'] != 'select' ) { return false; }
 $selections = $element['selections'];
 for( $i = 0; $i < count( $selections ); $i++ ) {
 $c.=  $selections[$i]['code'];
 }
 return $c;
}


function addTextBox($name, $value, $desc, $req=FALSE) {
 $element['type']  = 'text';
 $element['name']  = $name;
 $element['value']  = $value;
 $element['desc'] = $desc;
 $element['req']  = $req;
 $element['id'] = count($this->elements);
 $element['code']  = "<input type='text' name='{$element['id']}' value='{$element['value']}'>";
 return $this->addElement($element);
}

function addCheckBox($name, $value, $desc, $req=FALSE) {
 $element['type']  = 'checkbox';
 $element['name']  = $name;
 $element['value']  = $value;
 $element['desc'] = $desc;
 $element['req']  = $req;
 $element['id'] = count($this->elements);
 $element['code']  = "<input type='checkbox' name='{$element['id']}' value='{$element['value']}'>";
 return $this->addElement($element);
}

function addRadioButton($name, $value, $desc, $req=FALSE) {
 $element['type']  = 'radio';
 $element['name']  = $name;
 $element['value']  = $value;
 $element['desc'] = $desc;
 $element['req']  = $req;
 $element['id'] = count($this->elements);
 $element['code']  = "<input type='radio' value='{$element['id']}' name='{$element['name']}'>";
 return $this->addElement($element);
}

function addTextArea($name, $value, $desc, $req=FALSE) {
 $element['type']  = 'textarea';
 $element['name']  = $name;
 $element['value']  = $value;
 $element['desc'] = $desc;
 $element['req']  = $req;
 $element['id'] = count($this->elements);
 $element['code']  = "<textarea name='{$element['id']}'>{$element['value']}</textarea>";
 return $this->addElement($element);
}

function addSubmitButton($name,$value) {
 $element['type'] = 'submit';
 $element['name'] = $name;
 $element['value'] = $value;
 $element['req'] = NULL;
 $element['code'] = "<input class='submit' type='submit' value='{$element['value']}'>";
 return $this->addElement($element);
}

function addHiddenField($name,$value, $req=FALSE) {
 $element['type']  = 'hidden';
 $element['name']  = $name;
 $element['value']  = $value;
 $element['req']  = $req;
 $element['code']  = "<input type='hidden' name='{$element['name']}' value='{$element['value']}'>";
 return $this->addElement($element);
}

function render() {
 $c=NULL;
 if($this->header) {$c.="<h2>$this->header</h2>";}
 $c.= "<form method='{$this->method}' action='' class='{$this->class}'>\n";
 $element = $this->getNext();
 while( $element ) {
 if($element['req']) {$req='<em>*</em>';}else {$req=NULL;}
 switch($element['type'])
 {
  case 'select':
  $selectItems= $this->getSelectItems( $element );
  $c.='<p><label>'.$element['desc'].'</label>'.$element['beginCode'].$selectItems.$element['endCode'].'</p>';
  break;
  case 'text':
  $c.= "<p><label>{$element['desc']}$req</label>{$element['code']}</p>";
  break;
  case 'checkbox':
  $c.= "<p><label>{$element['desc']}$req</label>{$element['code']}</p>";
  break;
  case 'radio':
  $c.= "<p><label>{$element['desc']}$req</label>{$element['code']}</p>";
  break;
  case 'textarea':
  $c.= "<p><label>{$element['desc']}$req</label>{$element['code']}</p>";
  break;
  case 'submit':
  $c.= "<p>{$element['code']}</p>";
  break;
 }
 $element = $this->getNext();
 $c.="\n";
 }
 $c.= "<input type='hidden' name='id' value='1'>";
 $c.= "</form>\n";
 return $c;
}

}

function notification($message)
{
if (isset($_GET['message']) && is_numeric($_GET['message'])) {
if($_GET['message']>4){return '<div class="box fail">'.$message[$_GET["message"]].'<br><span style="font-size:0.8em"><b>Mysql Error</b>: '.$_GET["error"].'</span></div>';}
else {return '<div class="box success">'.$message[$_GET["message"]].'</div>';}
}
}

	class Pagination {
	public $howmany;
	public $list;
	
	function __construct($howmany, $list) {
	$this->howmany=$howmany;
	$this->list=$list;
	}
	
	public function getpage() {
	if(isset($_GET["page"]) && is_numeric($_GET["page"])) {$this->page=$_GET["page"];} else {$this->page=1;}
	}
	
	function sqlcode() {return ' '.$this->list*($this->page-1).', '.$this->list;}
	
	function write() {
	if(empty($_GET["search"])) {$get=NULL; $search=FALSE;} else{$get = '&search='.$_GET["search"]; $search=TRUE; $searchterm = $_GET["search"];}
	$total = ceil($this->howmany/$this->list);
	$temp= '<div id="pagination">';
	if($this->page>1) {$temp.= '<a class="pagination pag-active" href="?page='.($this->page-1).$get.'">« Previous</a>';}
	if($this->page<=7) {for($i=1; $i<$this->page; $i++) {$temp.= "<a class='pagination' href='?page=$i$get'>$i</a>";}
	$temp.= "<a class='pagination pag-active' href='?page=$this->page'>$this->page</a>";
	}else {
	for($i=1; $i<=3; $i++) {$temp.= "<a class='pagination' href='?page=$i$get'>$i</a>";}
	$temp.= '&#8734; ';
	for($i=$this->page-3; $i<$this->page; $i++) {$temp.= "<a class='pagination' href='?page=$i$get'>$i</a>";}
	$temp.= "<a class='pagination pag-active' href='?page=$this->page$get'>$this->page</a>";
	}
	if($total-$this->page<=10) {
	for($i=($this->page+1); $i<=$total; $i++) {$temp.= "<a class='pagination' href='?page=$i$get'>$i</a>";}
	}else{
	for($i=($this->page+1); $i<=($this->page+3); $i++) {$temp.= "<a class='pagination' href='?page=$i$get'>$i</a>";}
	$temp.= '&#8734; ';
	for($i=$total-2; $i<=$total; $i++) {$temp.= "<a class='pagination' href='?page=$i$get'>$i</a>";}
	}
	if($this->page<$total) {$temp.= '<a class="pagination pag-active" href="?page='.($this->page+1).$get.'">Next »</a>';}
	if ((($this->page)*$this->list)>$this->howmany) {$showtotal=$this->howmany;}else {$showtotal=(($this->page)*$this->list);}
	if ($total==0) {$temp.= "<div style='float:right;'>Hiç kayıt bulunamadı.</div></div>";} else{ $temp.= '<div style="float:right;">'; if($search==TRUE) {$temp.= "'<b>$searchterm</b>' sorgusu için toplam ";} $temp.= "$this->howmany kayıt arasından ".((($this->page-1)*$this->list)+1)." - ".$showtotal." arasındakiler gösteriliyor.</div></div>";}
	return $temp;
	}
}


function uploadjs($idname, $directory)
{
	for($i=0; $i<$column; $i++) {if($vartype[$i]=='image') {$up[]=$i;}}
	for($i=0; $i<count($up); $i++) {
	$uplo='
	<script type="text/javascript">
	$(function(){var b=$("#upload'.$up[$i].'");var a=$("#status");new AjaxUpload(b,{action:"uploads.php",name:"uploadfile",onSubmit:function(c,d){if(!(d&&/^(jpg|png|jpeg|gif)$/.test(d))){a.text("Only JPG, PNG or GIF files are allowed");return false}a.html("<|>")},onComplete:function(d,c){a.text("");if(c==="error"){$("<li></li>").appendTo(\'input[name="var'.$up[$i].'"]\').text(c).addClass("error")}else{$(\'input[name="var'.$up[$i].'"]\').val("'.$directory.'/uploads/"+[c]), ImgError($("input#var0").val());}}})});
	</script>
	';
	$head=$uplo;
}
}

function thumbnail($imgSrc,$thumbnail_width,$thumbnail_height) {
    list($width_orig, $height_orig) = getimagesize($imgSrc);  
    $myImage = imagecreatefromjpeg($imgSrc);
    $ratio_orig = $width_orig/$height_orig;
   
    if ($thumbnail_width/$thumbnail_height > $ratio_orig) {
       $new_height = $thumbnail_width/$ratio_orig;
       $new_width = $thumbnail_width;
    } else {
       $new_width = $thumbnail_height*$ratio_orig;
       $new_height = $thumbnail_height;
    }
   
    $x_mid = $new_width/2;
    $y_mid = $new_height/2;
   
    $process = imagecreatetruecolor(round($new_width), round($new_height));
   
    imagecopyresampled($process, $myImage, 0, 0, 0, 0, $new_width, $new_height, $width_orig, $height_orig);
    $thumb = imagecreatetruecolor($thumbnail_width, $thumbnail_height);
    imagecopyresampled($thumb, $process, 0, 0, ($x_mid-($thumbnail_width/2)), ($y_mid-($thumbnail_height/2)), $thumbnail_width, $thumbnail_height, $thumbnail_width, $thumbnail_height);

    imagedestroy($process);
    imagedestroy($myImage);
    return $thumb;
}

class flexibleAccess{
/* Config Start */
  var $dbTable  = 'users';
  var $sessionVariable = 'userSessionValue';
  var $tbFields = array(
  	'userID'=> 'userID', 
  	'login' => 'username',
  	'pass'  => 'password',
  	'email' => 'email'
  );
  var $remember = 2592000;//One month
  var $remCookieName = 'auth';
  var $remCookieDomain = 'localhost';
  var $passMethod = 'md5';
  var $displayErrors = true;
 /* Config Finish */
  
  var $userID;
  var $dbConn;
  var $userData=array();

  function flexibleAccess($dbConn = '', $settings = '')
  {
	    if ( is_array($settings) ){
		    foreach ( $settings as $k => $v ){
				    if ( !isset( $this->{$k} ) ) die('Property '.$k.' does not exists. Check your settings.');
				    $this->{$k} = $v;
			}
	    }
	    $this->remCookieDomain = $this->remCookieDomain == '' ? $_SERVER['HTTP_HOST'] : $this->remCookieDomain;
	    if( !isset( $_SESSION ) ) session_start();
	    if ( !empty($_SESSION[$this->sessionVariable]) )
	    {
		    $this->loadUser( $_SESSION[$this->sessionVariable] );
	    }
	    //Maybe there is a cookie?
	    if ( isset($_COOKIE[$this->remCookieName]) && !$this->is_loaded()){
	      //echo 'I know you<br />';
	      $u = unserialize(base64_decode($_COOKIE[$this->remCookieName]));
	      $this->login($u['uname'], $u['password']);
	    }
  }
  
  function loadUser($userID)
  {
	$res = mysql_query("SELECT * FROM `{$this->dbTable}` WHERE `{$this->tbFields['userID']}` = '".$this->escape($userID)."' LIMIT 1");
    if ( mysql_num_rows($res) == 0 )
    	return false;
    $this->userData = mysql_fetch_array($res);
    $this->userID = $userID;
    $_SESSION[$this->sessionVariable] = $this->userID;
    return true;
  }
 
  function login($uname, $password, $remember)
  {
    	$uname    = $this->escape($uname);
    	$password = $originalPassword = $this->escape($password);
        $password = '"'.MD5($password).'"';
		$res = mysql_query("SELECT * FROM `{$this->dbTable}` WHERE `{$this->tbFields['login']}` = '$uname' AND `{$this->tbFields['pass']}` = $password LIMIT 1") or die("hata:".mysql_error());
		if ( mysql_num_rows($res) == 0) {return FALSE;} else {
		$this->userData = mysql_fetch_assoc($res);
			$this->userID = $this->userData[$this->tbFields['userID']];
			$_SESSION[$this->sessionVariable] = $this->userID;
			if ($remember) {
			$cookie = base64_encode(serialize(array('uname'=>$uname,'password'=>$originalPassword)));
			setcookie($this->remCookieName, $cookie, time()+$this->remember, NULL,NULL,false,true) or die("cookie sorunu");
			}
			@mysql_query("UPDATE {$this->dbTable} SET `last_login` = CURRENT_TIMESTAMP WHERE username = '$uname' LIMIT 1 ;");
			return TRUE;
  }
  }

  function logout($redirectTo = '')
  {
    setcookie($this->remCookieName, '', time()-3600);
    $_SESSION[$this->sessionVariable] = '';
    $this->userData = '';
    if ( $redirectTo != '' && !headers_sent()){
	   header('Location: '.$redirectTo );
	   exit;//To ensure security
	}
  }
  
  function get_property($property)
  {
    if (empty($this->userID)) {return'No user is loaded';}
    if (!isset($this->userData[$property])) {return 'Unknown property <b>'.$property.'</b>';}
    return $this->userData[$property];
  }
  
  /**
   * Is the user loaded?
   * @ return bool
   */
  function is_loaded()
  {
    return empty($this->userID) ? false : true;
  }
  /**
  	* Activates the user account
  	* @return bool
  */

  function insertUser($data, $activation=TRUE){ 
    $data[$this->tbFields['pass']] = MD5($data[$this->tbFields['pass']]);
	foreach ($data as $k => $v ) $data[$k] = "'".$this->escape($v)."'";
	if($activation) {
	$key = $this->randomPass(12);
	$sql = mysql_query("INSERT INTO `{$this->dbTable}` (`".implode('`, `', array_keys($data))."`) VALUES (".implode(", ", $data).")") or die("hata".mysql_error());
	if($sql) {return TRUE;}else {return FALSE;}
	}
 } 

  public function randomPass($length=10, $chrs = '1234567890qwertyuiopasdfghjklzxcvbnm'){
  $pwd=NULL;
    for($i = 0; $i < $length; $i++) {
        $pwd .= $chrs{mt_rand(0, strlen($chrs)-1)};
    }
    return $pwd;
  }

  function escape($str) {
    $str = get_magic_quotes_gpc()?stripslashes($str):$str;
    $str = mysql_real_escape_string($str);
    return $str;
  }
  
	function is_valid_username ($username) {
		if(preg_match('/^[0-9a-z,_-]+$/i', $username)){return TRUE;} else{return FALSE;}
	}
	  
	function is_valid_email($email){
		if(preg_match("/[a-zA-Z0-9_-.+]+@[a-zA-Z0-9-]+.[a-zA-Z]+/", $email)) {return TRUE;} else{return FALSE;}
	}
	
	function is_valid_password($password, $min_char = 4, $max_char = 20)
	{
	$password = trim($password); 
	$eregi = eregi_replace('([a-zA-Z0-9%&-*!$/_]{'.$min_char.','.$max_char.'})','', $password);
	if(empty($eregi)){return TRUE;} else{return FALSE;}
	}
}

?>