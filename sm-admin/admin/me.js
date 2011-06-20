$(function() {

$( "#customimgbrowse" ).click(function() {
	$gallery = $("<div class='popupDialog'></div>").load('browseimg.php?ref=custom&name='+$(this).attr('alt'));
	$gallery.dialog({
			width:'620',
			height:'430',
			modal: true
	});
});

$("a#effect").fancybox({ //custom
	'overlayShow'	: false,
	'transitionIn'	: 'elastic',
	'transitionOut'	: 'elastic',
	'title'			: this.title,
	'titlePosition' : 'inside'
});

$('elem > textarea').live('focus', function() {
  doldur(this);
});

   var path = jQuery.url.attr("file");
   if ( path ) {
     $('#nav li a[href$="' + path + '"]').attr('class', 'selected');
 }
 
 $('#nav6 li:first').css('margin-left', '0');
 $('#nav6 li:last').css('margin-right', '0');
(function($) {
	$.fn.ellipsis = function(enableUpdating){
		var s = document.documentElement.style;
		if (!('textOverflow' in s || 'OTextOverflow' in s)) {
			return this.each(function(){
				var el = $(this);
				if(el.css("overflow") == "hidden"){
					var originalText = el.html();
					var w = el.height();
					
					var t = $(this.cloneNode(true)).hide().css({
                        'position': 'absolute',
                        'height': 'auto',
                        'overflow': 'visible',
                        'max-width': 'inherit'
                    });
					el.after(t);
					
					var text = originalText;
					while(text.length > 0 && t.height() > el.height()){
						text = text.substr(0, text.length - 1);
						t.html(text + "..");
					}
					el.html(t.html());
					
					t.remove();
					
					if(enableUpdating == true){
						var oldW = el.height();
						setInterval(function(){
							if(el.height() != oldW){
								oldW = el.height();
								el.html(originalText);
								el.ellipsis();
							}
						}, 800);
					}
				}
			});
		} else return this;
	};
})(jQuery);


$(".blockm").ellipsis(true);
        $('span.toggle').click(function() {
            $($(this).parent().parent().find('.altcat')).slideToggle('fast');
            $($(this).parent().parent().find('.blocks:first')).slideToggle('fast');
        });

	$("input.disabled").qtip({
	position: {
		corner: {
         target: 'rightMiddle',
         tooltip: 'leftMiddle'
		}
   },
   show: { effect:'fade', solo: true },
   hide: { when: 'mouseout', fixed: true },
   style: {
         border: {
         width: 3,
         radius: 3,
		},
      tip: 'leftMiddle',
      name: 'orange'
   }
});


$('a#code').click(function() {
  $('span#blockid').html($(this).attr('alt'));
});

$('a#code').fancybox({
		'transitionIn'	:	'elastic',
		'transitionOut'	:	'elastic',
		'speedIn'		:	600, 
		'speedOut'		:	200, 
		'overlayShow'	:	false,
		'title'			:	'Bloğu göstermeniz için gereken kod',
	});

$('a#catcode').click(function() {
  $('span#catvar').html($(this).parent().siblings('.cat').attr('id'));
});

$('a#catcode').fancybox({
		'transitionIn'	:	'elastic',
		'transitionOut'	:	'elastic',
		'speedIn'		:	600, 
		'speedOut'		:	200, 
		'overlayShow'	:	false,
		'title'			:	'Kategoriyi listelemek için kopyalamanız gereken kod',
	});

$( ".date" ).datepicker();

    $.fn.wait = function(time, type) {
        time = time || 5000;
        type = type || "fx";
        return this.queue(type, function() {
            var self = this;
            setTimeout(function() {
                $(self).dequeue();
            }, time);
        });
    };
	
jQuery.fn.shake = function(intShakes /*Amount of shakes*/, intDistance /*Shake distance*/, intDuration /*Time duration*/) {
this.each(function() {
$(this).css({position:'relative'});
for (var x=1; x<=intShakes; x++) {
$(this).animate({left:(intDistance*-1)}, (((intDuration/intShakes)/4)))
.animate({left:intDistance}, ((intDuration/intShakes)/2))
.animate({left:0}, (((intDuration/intShakes)/4)));
}
});
return this;
};

	if ($(".success").length) {$(".box").wait().slideUp('slow');	}
	if ($(".fail").length) {$(".box").shake(3,12,400).wait().slideup(slow);	}
	if ($.browser.msie && $.browser.version <= 7) {$('<div>Çok kötü bir tarayıcı kullanıyorsun dostum</div>').attr("class","box success").appendTo("#notification");}
	$("tr#custom>td:nth-child(odd)").addClass("middle");
	$("tr#custom>td:nth-child(even)").addClass("ve");



	
$(".colsumn:nth-child(odd)").addClass("last");

$( "#show" ).buttonset();
});

function number_check(value) {
var binary = value.toString(2);
if (binary.charAt(binary.length - 1) == "1") {
return false;
}
else {
return true;
}
}

function confirm() {
return confirm("Bu imaj geri dönüşümsüz biçimde silinecek, emin misiniz?");
}

function call(emre) {
$.ajax({
	type: 'POST',
	url: 'blockadmin.php',
	data: 'catid='+ emre.attr('name') +'&catname='+ emre.val(),
	error: function(ajaxCevap) {
		$(".box").addClass("fail");
		$('.box').html('Ajax sorgusu sırasında hata oluştu');
	},
	success: function(ajaxCevap) {
		$('.box').addClass('success');
		$('.box').html(ajaxCevap);
	}
	});
emre.parent().html("<span id='"+ emre.attr('name') +"' onclick='ilk($(this))'>"+emre.val()+"</span>")
}
function ilk(taha) {taha.parent().fadeOut('fast').html("<input name='"+ taha.attr('id') +"' onChange='call($(this));' onblur='call($(this));' value='"+ taha.find("span").html() +"'>").fadeIn('fast')}

function createcat(value) {
$.ajax({
	type: 'GET',
	url: 'blockadmin.php',
	data: 'setting&createcat='+value,
	error: function(ajaxCevap) {
		$(".box").addClass("fail");
		$('.box').html('Ajax sorgusu sırasında hata oluştu');
	},
	success: function(ajaxCevap) {
		$('body').html(ajaxCevap);
		$('.box').addClass('success');
	}
	});
}

function doldur(o){ if (o.value==o.defaultValue){o.value=""; $(o).removeClass('gray');} else if(o.value==""){o.value=o.defaultValue; $(o).addClass('gray');}o.onblur=function(){doldur(o)}}

function blogcontent() {
$('#codeedit').contents().find("body").append('<var contenteditable="false">{{dasda}}</var>');
}

function catdelete(id) {
var ok = confirm('Kategori silinecek ve içindeki öğeler kategorisiz kalacak, devam etmek istiyor musunuz?');
if(ok) {
$.ajax({
	type: 'GET',
	url: 'blockadmin.php',
	data: 'catdelete='+id+'&setting',
	error: function(ajaxCevap) {
		$(".box").addClass("fail");
		$('.box').html('Ajax sorgusu sırasında hata oluştu');
	},
	success: function(ajaxCevap) {
		$('body').html(ajaxCevap);
		$('.box').addClass('success');
	}
	});
	
}
}

	function ImgError(source){
	check =source.attr("id").split("var");
	source = source.val();
	var imgsrc = source; var img = new Image();
	img.onerror = function (evt){document.getElementById("check" + check[1]).innerHTML="<img style=\'margin:2px;\' src=\'images/error.png\'>";}
	img.onload = function (evt){document.getElementById("check" + check[1]).innerHTML="<img src=\'images/okay.png\'>";}
	img.src = imgsrc;
	return true;
	}
	
function browseimg(ref, address, name) {
if(ref=='ckeditor') {
window.opener.CKEDITOR.tools.callFunction(1, address);
window.close();
}else
if(ref=='custom') {
$('input#'+name).val(address);
$($gallery).dialog('close');
}
}
jQuery.url=function(){var segments={};var parsed={};var options={url:window.location,strictMode:false,key:["source","protocol","authority","userInfo","user","password","host","port","relative","path","directory","file","query","anchor"],q:{name:"queryKey",parser:/(?:^|&)([^&=]*)=?([^&]*)/g},parser:{strict:/^(?:([^:\/?#]+):)?(?:\/\/((?:(([^:@]*):?([^:@]*))?@)?([^:\/?#]*)(?::(\d*))?))?((((?:[^?#\/]*\/)*)([^?#]*))(?:\?([^#]*))?(?:#(.*))?)/,loose:/^(?:(?![^:@]+:[^:@\/]*@)([^:\/?#.]+):)?(?:\/\/)?((?:(([^:@]*):?([^:@]*))?@)?([^:\/?#]*)(?::(\d*))?)(((\/(?:[^?#](?![^?#\/]*\.[^?#\/.]+(?:[?#]|$)))*\/?)?([^?#\/]*))(?:\?([^#]*))?(?:#(.*))?)/}};var parseUri=function(){str=decodeURI(options.url);var m=options.parser[options.strictMode?"strict":"loose"].exec(str);var uri={};var i=14;while(i--){uri[options.key[i]]=m[i]||""}uri[options.q.name]={};uri[options.key[12]].replace(options.q.parser,function($0,$1,$2){if($1){uri[options.q.name][$1]=$2}});return uri};var key=function(key){if(jQuery.isEmptyObject(parsed)){setUp()}if(key=="base"){if(parsed.port!==null&&parsed.port!==""){return parsed.protocol+"://"+parsed.host+":"+parsed.port+"/"}else{return parsed.protocol+"://"+parsed.host+"/"}}return(parsed[key]==="")?null:parsed[key]};var param=function(item){if(jQuery.isEmptyObject(parsed)){setUp()}return(parsed.queryKey[item]===null)?null:parsed.queryKey[item]};var setUp=function(){parsed=parseUri();getSegments()};var getSegments=function(){var p=parsed.path;segments=[];segments=parsed.path.length==1?{}:(p.charAt(p.length-1)=="/"?p.substring(1,p.length-1):path=p.substring(1)).split("/")};return{setMode:function(mode){options.strictMode=mode=="strict"?true:false;return this},setUrl:function(newUri){options.url=newUri===undefined?window.location:newUri;setUp();return this},segment:function(pos){if(jQuery.isEmptyObject(parsed)){setUp()}if(pos===undefined){return segments.length}return(segments[pos]===""||segments[pos]===undefined)?null:segments[pos]},attr:key,param:param}}();
