<?php
mysql_connect(localhost, root, "") or die("MySQL Hata: " . mysql_error());
mysql_select_db(plazma);
@mysql_query("SET NAMES 'utf8'");

$ask = mysql_query("SELECT * FROM custom3");
while($b = mysql_fetch_assoc($ask)) {
?>
<li><a href="

<?php echo $b[1]."\n"; ?>
">
<?php echo $b[2]."\n"; ?></a></li>

<?php } ?>