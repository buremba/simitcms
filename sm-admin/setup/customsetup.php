<?php
include("../config.php");

$head.=<<<html
<script type="text/javascript" src="../admin/me.js"></script>
html;

if(!$_GET) {
$ic.=<<<html
Kaç değişkenli bir alan oluşturmak istiyorsunuz: <form method="GET" value=""><input type="text" name="variable" style="width:20px;"><input type="submit" value="oluştur"></form>
html;
}

if(isset($_GET["variable"]) && empty($_POST["many"])) {
$many = $_GET["variable"];
$manyy = $many+1;

$ic.=<<<html
<form method="POST" value="">
<input type="text" style="width:60%;" name="name" value="Oluşturduğunuz alanın genel ismi" onfocus="doldur(this)" class="gray">
html;

for($i=1; $i<=$many; $i++) {
$ic.=<<<html
<textarea style="width:75%;" name="constant{$i}"></textarea>
<input type="text" style="width:60%;" name="variable{$i}" value="{$i}. değişken alanın ismi" onfocus="doldur(this)" class="gray">
&#126;
<select name="type{$i}">
<option value="text">text</option>
<option value="image">image</option>
<option value="date">date</option>
<option value="textarea">textarea</option>
</select>
html;
}

$ic.=<<<html
<textarea style="width:75%;" name="constant{$manyy}"></textarea>
<input type="hidden" value="{$many}"  name="many">
<input type="submit" value="oluştur">
</form>
html;
}

if(isset($_POST["many"])) {
$many = $_POST["many"];
$manyy = $many+1;
$name = $_POST["name"];

/* CREATE TABLE START*/
// create table's name
$table='custom';
$sql = mysql_query("show tables like '".$table."';");
$exist = mysql_num_rows($sql);
$i=1;
while($exist > 0) {$table = 'custom'.$i; $i++; $sql = mysql_query("show tables like '".$table."';"); $exist = mysql_num_rows($sql);}
// run sql code
$sql = "CREATE TABLE `".$table."` (`id` INT(10) NOT NULL AUTO_INCREMENT, PRIMARY KEY (`id`)) ENGINE = MyISAM CHARACTER SET utf8 COLLATE utf8_general_ci;\n";
$create = mysql_query($sql);
if (!$create) {$ic.="yapma be\n"; $ic.=mysql_error();}
for($i=1; $i<=$many; $i++) {
$veri = $_POST["variable".$i];
$sql= "ALTER TABLE `".$table."` ADD `".$i."` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '".$veri."';";
$create = mysql_query($sql);
if (!$create) {$ic.="yapma be\n"; $ic.=mysql_error();}
}
/* CREATE TABLE FINISH */

/* CREATE FILE WHICH WILL INCLUDE START */
$file='<?php
mysql_connect('.$mysql_host.', '.$mysql_user.', "'.$mysql_user_pass.'") or die("MySQL Hata: " . mysql_error());
mysql_select_db('.$mysql_dbname.');
@mysql_query("SET NAMES \'utf8\'");

$ask = mysql_query("SELECT * FROM '.$table.'");
while($b = mysql_fetch_assoc($ask)) {
?>';
for($i=1; $i<=$many; $i++) {
$file.="\n".$_POST["constant".$i]."\n<?php ".'echo $b['.$i.']."\n"; ?>';
}
$file.=$_POST["constant".$manyy]."\n\n<?php } ?>";

$filename = '../inc/'.$table.'.php';
$ic.=filewrite($filename, $file).'<br>';
/* CREATE FILE WHICH WILL INCLUDE FINISH */

/* CREATE ADMIN FILE START */
$z=1; $types=NULL;
while(isset($_POST["type".$z])) {
$types.='$vartype['.($z-1).'] = \''.$_POST["type".$z].'\';'."\n";
$z++;
}
$ali= file_get_contents('customadmin.txt');
$ali=str_replace(array("{{table}}", "{{types}}"),array($table, $types), $ali);

$filename = '../admin/customadmin.php'; $y=1; while(file_exists($filename)){$filename = '../admin/custom'.$y.'admin.php'; $y++;}
$ic.=filewrite($filename, $ali);
/* CREATE ADMIN FILE FINISH */
}
include("../template.php");
?>