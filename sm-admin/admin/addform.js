function addtext() {
$('.formarea').append('<div class="elem textbox" req="0"><label><span>Textbox</span></label><input readonly="" type="text"></div>');
}

function addtextarea() {
$('.formarea').append('<div class="elem textarea" req="0"><label><span>Textarea</span></label><textarea></textarea></div>');
}

function addselect() {
$('.formarea').append('<div class="elem select" req="0"><label><span>Selectbox</span></label><select></select></div>');
}

function addcheck() {
$('.formarea').append('<div class="elem checkbox" req="0"><label><span>Checkbox</span></label><p><input readonly="" type="checkbox"><span></span></p></div>');
}

function addradio() {
$('.formarea').append('<div class="elem radiobox" req="0"><label><span>Radio</span></label><p><input readonly="" type="radio"><span></span></p></div>');
}

function selectdate(which) {
$(which).parent().parent().append('<select><option onclick="showdate(this)">show current date</option><option selected>don\'t show current date</option></select>');
}
function showdate(which) {
$(which).parent().parent().append('<input type="text" value="d M Y H:i">');
}

$('.elem').live('click', function(event) {
if ($(event.target).is('input, textarea, select')) {return;}

if($(this).is('.textbox')){var edit = '.textboxedit';}else
if($(this).is('.select')) {var edit =  '.selectedit';}else
if($(this).is('.textarea')) {var edit =  '.textareaedit';}else
if($(this).is('.checkbox')) {var edit =  '.checkboxedit';}else
if($(this).is('.radiobox')) {var edit =  '.radioboxedit';}

if($('.edit').is(':visible')) {
	if($(this).is('.fselected')) {
		$(this).removeClass('fselected');
		$(edit).removeAttr('alt');
		$(edit).hide();
	}else {
		$('.fselected').removeClass('fselected');
		$('.edit').hide();
		$(this).addClass('fselected');
		$(edit).toggle("slide");
		$(edit).attr('alt', $('.elem').index(this));
		filleditarea($('.elem').index(this), edit);
	}
}else {
	$(this).addClass('fselected');
	$(edit).toggle('slide');
	$(edit).attr('alt', $('.elem').index(this));
	filleditarea($('.elem').index(this), edit);
}

});

function filleditarea(inde, edit) {
$('.req').css('border', 'none');
$('input.labelname').val($('.elem:eq('+inde+')').find('label').find('span:eq(0)').text());
var req = $('.elem:eq('+inde+')').attr('req');
if(req==1) {
$('.req-on').css('border', '2px solid #DBDBDB');
}else
if(req==0) {
$('.req-off').css('border', '2px solid #DBDBDB');
}

if(edit=='.checkboxedit') {
$('ul.checkboxs').empty();
$('.elem:eq('+inde+') p').each(function(index) {
var name = $(this).find('span').text();
if (name=='') {var disabled=''; var ok='<span class="checkboxok">✔</span>'}else {var disabled='disabled="true"'; var ok='';}
$('ul.checkboxs').append('<li><input type="checkbox"><input type="text" value="'+name+'" '+disabled+'>'+ok+'<span class="optiondel">✖</span></li>');
});
}

if(edit=='.radioboxedit') {
$('ul.radios').empty();
$('.elem:eq('+inde+') p').each(function(index) {
var name = $(this).find('span').text();
if (name=='') {var disabled=''; var ok='<span class="radiook">✔</span>'}else {var disabled='disabled="true"'; var ok='';}
$('ul.radios').append('<li><input type="radio"><input type="text" value="'+name+'" '+disabled+'>'+ok+'<span class="radiodel">✖</span></li>');
});
}

if(edit=='.selectedit') {
$('ul.options').empty();
$('.elem:eq('+inde+') select option').each(function(index) {
var name = $(this).text();
if (name=='') {var disabled=''; var ok='<span class="optionok">✔</span>'}else {var disabled='disabled="true"'; var ok='';}
$('ul.options').append('<li><input class="option" type="text" value="'+name+'" '+disabled+'>'+ok+'<span class="optiondel">✖</span></li>');
});
}

}

$('input.labelname').live('change', function(){
var id = $(this).parent().attr('alt');
$('.elem:eq('+id+')').find('label').find('span:eq(0)').text($(this).val());
});

$('.texttype').live('change', function(){
var id = $(this).parents('.textboxedit').attr('alt');
$('.elem:eq('+id+')').find('input').val($(this).find('option:selected').val());
});

$('.optionadd').live('click', function(){
var id = $(this).parents('.selectedit').attr('alt');
$('ul.options').append('<li> <input class="option" type="text"><span class="optionok">✔</span><span class="optiondel">✖</span></li>');
$('.elem:eq('+id+')').find('select').append('<option></option>');
});

$('.optionok').live('click', function(){
var id = $(this).parents('.selectedit').attr('alt');
var value = $(this).siblings('input').val();
var order = $('ul.options li').index($(this).parent());
$('.elem:eq('+id+')').find('select').find('option:eq('+order+')').html(value);
$(this).siblings('input').attr('disabled', 'true');
$(this).remove();
});

$('.optiondel').live('click', function(){
var id = $(this).parents('.selectedit').attr('alt');
var order = $('ul.options li').index($(this).parent());
$(this).parent().remove();
$('.elem:eq('+id+')').find('select').find('option:eq('+order+')').remove();
});

$('.checkboxadd').live('click', function(){
var id = $(this).parents('.checkboxedit').attr('alt');
$('ul.checkboxs').append('<li><input type="checkbox"><input type="text"><span class="checkboxok">✔</span><span class="optiondel">✖</span></li>');
$('.elem:eq('+id+')').append('<p><input readonly="" type="checkbox"><span></span></p>');
});

$('.checkboxok').live('click', function(){
var id = $(this).parents('.checkboxedit').attr('alt');
var value = $(this).siblings('input:eq(1)').val();
var order = $('ul.checkboxs li').index($(this).parent());
$('.elem:eq('+id+')').find('p:eq('+order+')').find('span').html(value);
$(this).siblings('input').attr('disabled', 'true');
$(this).remove();
});

$('.checkboxdel').live('click', function(){
var id = $(this).parents('.checkboxedit').attr('alt');
var order = $('ul.checkboxs li').index($(this).parent());
$(this).parent().remove();
$('.elem:eq('+id+')').find('p:eq('+order+')').remove();
});

$('.radioadd').live('click', function(){
var id = $(this).parents('.radioboxedit').attr('alt');
$('ul.radios').append('<li><input type="radio"><input type="text"><span class="radiook">✔</span><span class="radiodel">✖</span></li>');
$('.elem:eq('+id+')').append('<p><input readonly="" type="radio"><span></span></p>');
});

$('.radiook').live('click', function(){
var id = $(this).parents('.radioboxedit').attr('alt');
var value = $(this).siblings('input:eq(1)').val();
var order = $('ul.radios li').index($(this).parent());
$('.elem:eq('+id+')').find('p:eq('+order+')').find('span').html(value);
$(this).siblings('input').attr('disabled', 'true');
$(this).remove();
});

$('.radiodel').live('click', function(){
var id = $(this).parents('.radioboxedit').attr('alt');
var order = $('ul.radios li').index($(this).parent());
$(this).parent().remove();
$('.elem:eq('+id+')').find('p:eq('+order+')').remove();
});

$('span.req').live('click', function(){
var id = $(this).parent().parent().attr('alt');
if($(this).is('.req-on')) {
if(!$('.elem:eq('+id+')').find('label').find('span:eq(1)').length) {
$('.elem:eq('+id+')').find('label').append('<span style="color:red;">*</span>');
$('.elem:eq('+id+')').attr('req', '1');
$('.req-off').css('border', 'none');
$('.req-on').css('border', '2px solid #DBDBDB');
}
}
else {
$('.elem:eq('+id+')').find('label').find('span:eq(1)').remove();
$('.elem:eq('+id+')').attr('req', '0');
$('.req-on').css('border', 'none');
$('.req-off').css('border', '2px solid #DBDBDB');
}
});