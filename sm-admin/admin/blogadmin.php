<?php
include_once('../../config.php');

if(empty($_GET)) {
$ic.='<a class="awesome orange" href="?newblog">Yeni Yazı Ekle</a>';
$ask = mysql_query("SELECT post_id, post_date, post_title FROM blog");
while($blck = mysql_fetch_assoc($ask)) {
$ic.=<<<html
<div class="blogedit">
<a href="?editpost={$blck["post_id"]}">{$blck["post_title"]}</a>
</div>
html;
}
}

if(isset($_GET["newblog"])) {
if(isset($_POST["postname"])) {
$postname = $_POST["postname"];
$postcontent = $_POST["postcontent"];
$date = date("d.m.Y G:i");
mysql_query("INSERT INTO blog (post_title, post_content, post_date) VALUES ('$postname','$postcontent','$date')");
$ic.="başardım";
}
else{
$textarea=TRUE;
$ic.=<<<html
Yeni Blog Girdisi Ekle<br>
<form method="POST" value="">
<input type="text" name="postname"><br>
<textarea class="ckeditor" cols="80" id="editor1" name="postcontent"></textarea><br>
<input type="submit" value="Gönder">
</form>
html;
}
}

elseif(isset($_GET["editpost"])) {
if(isset($_POST["editptitle"])) {
$postid = $_POST["postid"];
$editptitle = $_POST["editptitle"];
$editpcontent = $_POST["editpcontent"];
mysql_query("UPDATE blog SET post_title='$editptitle', post_content='$editpcontent' WHERE post_id='$postid'");
$ic.="oldu";
}
else{
$postid = $_GET["editpost"];
$ask = mysql_query("SELECT * FROM blog WHERE post_id='$postid'");
$textarea=TRUE;
while ($edit = mysql_fetch_assoc($ask)) {
$ic.=<<<html
<form method="POST" value="">
<input type="hidden" name="postid" value="{$postid}">
<input type="text" name="editptitle" value="{$edit["post_title"]}"><br>
<textarea class="ckeditor" cols="80" id="editor1" name="editpcontent">{$edit["post_content"]}</textarea><br>
<input class="awesome" type="submit" value="Gönder">
</form>
html;
		}
	}
}

elseif(isset($_GET["setting"])) {
$head=<<<html
	<script type="text/javascript" src="plugin/jquery-1.3.2.js" ></script>
	<script type="text/javascript" src="plugin/jquery-ui-1.7.2.custom.min.js" ></script>
	
	<script type="text/javascript" >
	$(function(){
		$('.blogset')
		.each(function(){
			$(this).hover(function(){
				$(this).find('h2').addClass('collapse');
			}, function(){
				$(this).find('h2').removeClass('collapse');
			})
			.find('h2').hover(function(){
				$(this).find('.configure').css('visibility', 'visible');
			}, function(){
				$(this).find('.configure').css('visibility', 'hidden');
			})
			.click(function(){
				$(this).siblings('.blogset-content').toggle();
			})
			.end()
			.find('.configure').css('visibility', 'hidden');
		});
		$('.column').sortable({
			connectWith: '.column',
			handle: 'h2',
			cursor: 'move',
			placeholder: 'placeholder',
			forcePlaceholderSize: true,
			opacity: 0.4,
			stop: function(event, ui){
				$(ui.item).find('h2').click();
				var sortorder='';
				$('.column').each(function(){
					var itemorder=$(this).sortable('toArray');
					var columnId=$(this).attr('id');
					sortorder+=columnId+'='+itemorder.toString();
				});
				$.ajax({
	type: 'GET',
	url: 'blogset.php',
	data: 'blogsetting=' + sortorder,
	success: function(ajaxCevap) {
		alert("oldu");
	}
});
				/*Pass sortorder variable to server using ajax to save state*/
			}
		})
	});
</script>
html;

$ic.=<<<html
<div class="column" id="column1">

	<div class="blogset blogset-code" id="26">
	<h2>dsa</h2>
	<div class="blogset-content"><textarea type"text" class="blogset-textarea"></textarea></div>
	</div>
		
	<div class="blogset" id="cont">
	<h2>Blog Content</h2>
	</div>

	<div class="blogset blogset-code" id="27">
	<h2>dsa</h2>
	<div class="blogset-content"><textarea type"text" class="blogset-textarea"></textarea></div>
	</div>

	<div class="blogset" id="head">
	<h2>Blog Header</h2>
	</div>
	
	<div class="blogset blogset-code" id="28">
	<h2>dsa</h2>
	<div class="blogset-content"><textarea type"text" class="blogset-textarea"></textarea></div>
	</div>

	<div class="blogset" id="date">
	<h2>Blog Date</h2>
	</div>
	
	<div class="blogset blogset-code" id="29">
	<h2>dsa</h2>
	<div class="blogset-content"><textarea type"text" class="blogset-textarea"></textarea></div>
	</div>

	<div class="blogset" id="com">
	<h2>Blog Comment</h2>
	</div>
	
	<div class="blogset blogset-code" id="30">
	<h2>dsa</h2>
	<div class="blogset-content"><textarea type"text" class="blogset-textarea"></textarea></div>
	</div>

	<div class="blogset" id="comcount">
	<h2>Blog Comment Count</h2>
	</div>
	
	<div class="blogset blogset-code" id="31">
	<h2>dsa</h2>
	<div class="blogset-content"><textarea type"text" class="blogset-textarea"></textarea></div>
	</div>
	
</div>
html;
}

elseif(isset($_GET["blogsetting"])) {
$veri = $_GET["blogsetting"];
mysql_query("UPDATE settings SET setting_value='$veri' WHERE setting_id='33'");
}


include("../template.php");
?>