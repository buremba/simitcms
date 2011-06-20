<?php
include_once('../../config.php');
$head.="<script type='text/javascript' src='addform.js'></script>";
$ic.='
<div class="addform" style="float:right;">
<a class="button purple" onclick="addtext()">Add a Textbox</a>
<a class="button purple" onclick="addselect()">Add a SelectBox</a>
<a class="button purple" onclick="addcheck()">Add a Checkbox</a>
<a class="button purple" onclick="addradio()">Add a Radio Button</a>
<a class="button purple" onclick="addtextarea()">Add a Textarea</a>
</div>
<div id="fullform">
<div class="formarea">
</div>
<div class="edit textboxedit" style="display:none;">
	<label>Require?</label>
	<p><span class="req req-on">ON</span> / <span class="req req-off">OFF</span></p>
	
	<label>Textbox\'s label:</label>
	<input class="labelname" type="text">
	
	<label>Type:</label>
	<select class="texttype">
		<option value="text">text</option>
		<option value="1234567890">number</option>
		<option value="example@example.com">e-mail</option>
		<option value="date" onclick="selectdate(this)">date</option>
	</select>
	<p class="dateoptions">
	<label>Show current date?</label>
	
	</p>


</div>
<div class="edit selectedit" style="display:none;">
	<label>Require?</label>
	<p><span class="req req-on">ON</span> / <span class="req req-off">OFF</span></p>
	
	<label>Select box\'s label:</label>
	<input class="labelname" type="text">
	<label>Options:</label>
	<ul class="options"></ul>
	<span class="optionadd">+</span>
</div>
<div class="edit textareaedit" style="display:none;">
	<label>Require?</label>
	<p><span class="req req-on">ON</span> / <span class="req req-off">OFF</span></p>
	
	<label>Textarea\'s label:</label>
	<input class="labelname" type="text">
	
	<label>Default value:  (not necessary)</label>
	<textarea></textarea>
</div>
<div class="edit checkboxedit" style="display:none;">
	<label>Require?</label>
	<p><span class="req req-on">ON</span> / <span class="req req-off">OFF</span></p>
	
	<label>Checkboxs\' label:</label>
	<input class="labelname" type="text">
	
	<label>Checkbox(s):</label>
	<ul class="checkboxs"></ul>
	<span class="checkboxadd">+</span>
</div>
<div class="edit radioboxedit" style="display:none;">
	<label>Require?</label>
	<p><span class="req req-on">ON</span> / <span class="req req-off">OFF</span></p>
	
	<label>Radio\' label:</label>
	<input class="labelname" type="text">
	
	<label>Radio(s):</label>
	<ul class="radios"></ul>
	<span class="radioadd">+</span>
</div>

</div>
';

include("../template.php");

?>