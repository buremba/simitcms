<?php
include("../config.php");
include("../function.php");

// GET ile veri gelmemişse burayı işle
if(!$_GET) {

// BLog ile ilgili genel ayarları al

$sor = mysql_query("SELECT blog.post_id, blog.post_title, blog.post_date, blog.post_content, COUNT(comments.comment_id)  FROM blog LEFT JOIN comments ON blog.post_id=owner_id GROUP BY blog.post_id");

while ($veri = mysql_fetch_assoc($sor)) {

$ic.=$veri["post_date"];
$ic.='<a href="?sayfa='.$veri["post_id"].'">'.$veri["post_title"].'</a>';
$ic.=$veri["COUNT(comments.comment_id)"].' yorum';
$ic.=short(100, $veri["post_content"]);

}

}

// GET ile sayfa verisi gelmişse burayı işe
if(isset($_GET["sayfa"])) {
$id = $_GET["sayfa"];

if(isset($_POST["name"])) {
ob_start();
$name = $_POST["name"];
$comment = $_POST["comment"];
$date = date("d.m.Y G:i");
mysql_query("INSERT INTO comments (owner_id, comment_name, comment_content, comment_date) VALUES ('$id','$name','$comment','$date')");
header ("Location:".$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']."&mesaj=1"); 
}

else {
// BLog içeriğiyle ile ilgili genel ayarları al

$sor = mysql_query("SELECT * FROM blog WHERE post_id='$id'");
while ($veri = mysql_fetch_assoc($sor)) {
$comm = mysql_query("SELECT * FROM comments WHERE owner_id='$id'");

		$ic.=$veri["post_date"]."\n";
		$ic.='<a href="?sayfa='.$veri["post_id"].'">'.$veri["post_title"].'</a>'."\n";
		$ic.=mysql_num_rows($comm).' yorum'."\n";
		$ic.=$veri["post_content"]."\n";

$ic.='<hr>';	

		while ($com = mysql_fetch_assoc($comm)) {
			$ic."\n";
			$ic.= $com["comment_name"]."\n";
			$ic.= $com["comment_date"]."\n";
			$ic.= $com["comment_content"]."\n";
		}
	
	if(isset($_GET["mesaj"])) {if ($_GET["mesaj"]=="1") {$ic.='Yorumunuz Eklendi.<br>';}}
$ic.=<<<html
	Bir Yorum Yazın<br>
	<form method="POST" action="">
	İsim:<input type="text" name="name"><br>
	Yorumunuz:<textarea name="comment"></textarea><br>
	<input type="submit" Value="Gönder">
	</form>
html;
	}
}
}

if(isset($_GET["tamam"])) {
include("block.php?sayfa=9");
}
include("../template.php");
?>