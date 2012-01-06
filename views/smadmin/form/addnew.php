<div class="addform" style="float:right;">
<a class="btn purple" onclick="addtext()">Add a Textbox</a>
<a class="btn purple" onclick="addselect()">Add a SelectBox</a>
<a class="btn purple" onclick="addcheck()">Add a Checkbox</a>
<a class="btn purple" onclick="addradio()">Add a Radio Button</a>
<a class="btn purple" onclick="addtextarea()">Add a Textarea</a>
<a class="btn orange formcreate">Create</a>
</div>
<div id="fullform">
<div class="formarea">
<div id="submit" class="elem submit hide"><input type="submit"></div>
</div>
<div class="edit textboxedit" style="display:none;">

	<ul>
	<li><a href="#tab1">General</a></li>
	<li><a href="#tab2">Validation</a></li>
	</ul>
	
	<div id="tab1" class="formtabcontent">
		<div id="requiretextbox" class="require">
			<input type="radio" id="textboxrequire0" class="requireradio require0" name="requireselect" /><label for="textboxrequire0">Required</label>
			<input type="radio" id="textboxrequire1" class="requireradio require1" name="requireselect" /><label for="textboxrequire1">Not Required</label>
		</div>
		
		<label>Type:</label>
		<div class="texttype">
			<input type="radio" id="all" name="texttype" example="text123%@.." /><label for="all">All</label>
			<input type="radio" id="onlyLetterNumber" name="texttype" example="text123.." /><label for="onlyLetterNumber">Alphanumeric</label>
			<input type="radio" id="onlyLetterSp" name="texttype" example="lorem ipsum.." /><label for="onlyLetterSp">Text</label>
			<input type="radio" id="number" name="texttype" example="1234567890" /><label for="number">Number</label>
			<input type="radio" id="email" name="texttype" example="example@example.com" /><label for="email">E-mail</label>
			<input type="radio" id="date" name="texttype" example="YYYY-MM-DD" /><label for="date">Date</label>
			<input type="radio" id="url" name="texttype" example="http://example.com" /><label for="url">Url</label>
		</div>
		
		<label>Label:</label>
		<input class="labelname" type="text">
	</div>
	
	<div id="tab2" class="formtabcontent">
		<label>Minimum Value:</label>
		<input class="minval" type="text">
		
		<label>Maximum Value:</label>
		<input class="maxval" type="text">
	</div>
</div>

<div class="edit selectedit" style="display:none;">
	<div id="requireselect" class="require">
		<input type="radio" id="selectrequire0" class="requireradio require0" name="requireselect" /><label for="selectrequire0">Required</label>
		<input type="radio" id="selectrequire1" class="requireradio require1" name="requireselect" /><label for="selectrequire1">Not Required</label>
	</div>
	
	<label>Select box\'s label:</label>
	<input class="labelname" type="text">
	
	<label>Options:</label>
	<ul class="options">
	<li class="new"><input class="newoption" value="" placeholder="Add Values.." type="text"></li>
	</ul>
</div>
<div class="edit textareaedit" style="display:none;">
	<div id="requiretextarea" class="require">
		<input type="radio" id="textarearequire0" class="requireradio require0" name="requireselect" /><label for="textarearequire0">Required</label>
		<input type="radio" id="textarearequire1" class="requireradio require1" name="requireselect" /><label for="textarearequire1">Not Required</label>
	</div>
	
	<label>Textarea\'s label:</label>
	<input class="labelname" type="text">
	
	<label>Default value:  (not necessary)</label>
	<textarea></textarea>
</div>
<div class="edit checkboxedit" style="display:none;">
	<div id="requirecheckbox" class="require">
		<input type="radio" id="checkboxrequire0" class="requireradio require0" name="requireselect" /><label for="checkboxrequire0">Required</label>
		<input type="radio" id="checkboxrequire1" class="requireradio require1" name="requireselect" /><label for="checkboxrequire1">Not Required</label>
	</div>
	
	<label>Checkboxs\' label:</label>
	<input class="labelname" type="text">
	
	<label>Checkbox(s):</label>
	<ul class="checkboxs">
	<li class="element"><input type="checkbox"><input class="newcheckbox" value="" type="text"><span class="ui-icon ui-icon-trash checkbox"></span></li>
	<li class="new"><input class="newcheckbox" value="" placeholder="Add Values.." type="text"></li>
	</ul>
</div>
<div class="edit radioboxedit" style="display:none;">
	<div id="requireradiobox" class="require">
		<input type="radio" id="radioboxrequire0" class="requireradio require0" name="requireselect" /><label for="radioboxrequire0">Required</label>
		<input type="radio" id="radioboxrequire1" class="requireradio require1" name="requireselect" /><label for="radioboxrequire1">Not Required</label>
	</div>
	
	<label>Radio\' label:</label>
	<input class="labelname" type="text">
	
	<label>Radio(s):</label>
	<ul class="radios">
	<li class="element"><input type="radio"><input class="newradio" value="" type="text"><span class="ui-icon ui-icon-trash radio"></span></li>
	<li class="new"><input class="newradio" value="" placeholder="Add Values.." type="text"></li>
	</ul>
</div>

</div>

<div class="createtemp hide"></div>