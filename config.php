<?php
//error_reporting(0);
/* */
include("function.php");

$mysql_host = 'localhost';
$mysql_user = 'root';
$mysql_user_pass = '';
$mysql_dbname= 'cms';

/* */

mysql_connect($mysql_host, $mysql_user, $mysql_user_pass) or die("MySQL Hata: " . mysql_error());
mysql_select_db($mysql_dbname);
@mysql_query("SET NAMES 'utf8'");

define('SITE_ADDRESS', 'http://localhost/cms/');


///////// login
$user = new flexibleAccess();
if (!isset($nonaccess)) {if(!$user->is_loaded()) {header('Location: ../index.php');}}
$ic = NULL;
$not= NULL;
$nav = NULL;
$head = NULL;
$sayi = 1;
$dir=TRUE;
$textarea=FALSE;
$imgupload=FALSE;
$date=FALSE;
$login=FALSE;

if(isset($_GET["logout"])) {
if(!$user->logout($redirectTo = '../index.php')) {echo "gel";}
}

?>


