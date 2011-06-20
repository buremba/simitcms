<?php
mysql_connect("localhost", "root","") or die("MySQL Hata: " . mysql_error());
mysql_select_db("plazma");
@mysql_query("SET NAMES 'utf8'");
setlocale(LC_ALL,"turkish");

$ask = mysql_query("SELECT * FROM custom1");
while($b = mysql_fetch_assoc($ask)) {
?>
<a href="#">
	<img src='
<?php echo $b[1]."\n"; ?>
'>
<span>
<?php echo $b[2]."\n"; ?></span>
	</a>

<?php } ?>