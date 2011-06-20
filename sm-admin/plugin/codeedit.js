/*
 +-------------------------------------------------------------------+
 |                 J S - C O D E E D I T   (v1.0)                    |
 |                                                                   |
 | Copyright Gerd Tentler               www.gerd-tentler.de/tools    |
 | Created: Oct. 3, 2009                Last modified: Jan. 4, 2010  |
 +-------------------------------------------------------------------+
 | This program may be used and hosted free of charge by anyone for  |
 | personal purpose as long as this copyright notice remains intact. |
 |                                                                   |
 | Obtain permission before selling the code for this program or     |
 | hosting this software on a commercial website or redistributing   |
 | this software over the Internet or in any other medium. In all    |
 | cases copyright must remain intact.                               |
 +-------------------------------------------------------------------+

===========================================================================================================
 This script was tested with the following systems and browsers:

 - Windows XP/Vista: IE 8, Opera 9, Firefox 3

 If you use another browser or system, this script may not work for you - sorry.

 Generally, code editing should work on Windows with Internet Explorer 5.5+ and with browsers using the
 Mozilla 1.3+ engine, i.e. all browsers that support "designMode".

 NOTE: The script also works with browsers that don't support code editing - a simple textarea will 
 replace the code editor.
 
 For instructions on how to use this script, read the README file or visit my website:
 http://www.gerd-tentler.de/tools/codeedit/
===========================================================================================================
*/
//---------------------------------------------------------------------------------------------------------
// Add new methods to Function prototype - needed to pass editor instance to event handlers etc.
//---------------------------------------------------------------------------------------------------------
Function.prototype.bind = function() {
	var _this = this, args = [], object = arguments[0];
	for(var i = 1; i < arguments.length; i++) args.push(arguments[i]);
	return function() {
		return _this.apply(object, args);
	}
}

Function.prototype.bindAsEventListener = function() {
	var _this = this, args = [], object = arguments[0];
	for(var i = 1; i < arguments.length; i++) args[i + 1] = arguments[i];
	return function(e) {  
		args[0] = e || event;
		return _this.apply(object, args);
	}
}

//---------------------------------------------------------------------------------------------------------
// Global variables and functions
//---------------------------------------------------------------------------------------------------------
var OP = (window.opera || navigator.userAgent.indexOf('Opera') != -1);
var IE = (navigator.userAgent.indexOf('MSIE') != -1 && !OP);
var FF = (navigator.userAgent.indexOf('Firefox') != -1 && !OP);
var WK = (navigator.userAgent.indexOf('WebKit') != -1 && !OP);
var GK = (navigator.userAgent.indexOf('Gecko') != -1 || OP);
var DM = (document.designMode && document.execCommand && !WK); /* WebKit not supported at the moment */

function CodeEdit(node, options, id) {
//---------------------------------------------------------------------------------------------------------
// Initialization
//---------------------------------------------------------------------------------------------------------
	this.node = node;
	this.language = options[0] ? options[0].toLowerCase() : '';
	this.viewLineNumbers = tools.inArray('lineNumbers', options, true);
	this.setFocus = tools.inArray('focus', options, true);
	this.id = id;
	this.textWidth = node.offsetWidth;
	this.textHeight = node.offsetHeight;
	this.fieldName = (node.name != '') ? node.name : node.id;
	this.bgColor = this.node.style.backgroundColor ? this.node.style.backgroundColor : '#FFFFFF';
	this.borderWidth = this.node.style.borderWidth ? parseInt(this.node.style.borderWidth) : 1;
	this.content = this.node.value.replace(/\s+$/, '');
	this.editor = null;
	this.canvas = null;
	this.numbers = null;
	this.input = null;
	this.timer = null;
	this.lines = [];
	this.cntLines = Math.round(this.textHeight / 16);
	this.maxLines = 0;
	this.curLine = 0;
	this.paste = false;

//---------------------------------------------------------------------------------------------------------
// Class methods
//---------------------------------------------------------------------------------------------------------
	this.create = function() {
		if((IE || GK) && DM) {
			var cont = document.createElement('div');
			cont.style.width = (this.textWidth - this.borderWidth * 2) + 'px';
			cont.style.borderWidth = this.borderWidth + 'px';
			cont.style.borderStyle = this.node.style.borderStyle ? this.node.style.borderStyle : 'solid';
			cont.style.borderColor = this.node.style.borderColor ? this.node.style.borderColor : '#D3DFC3';
			this.node.parentNode.replaceChild(cont, this.node);
		
			if(this.viewLineNumbers) {
				this.numbers = document.createElement('div');
				this.numbers.style.display = 'none';
				this.numbers.style.styleFloat = 'left';
				this.numbers.style.cssFloat = 'left';
				this.numbers.style.overflow = 'hidden';
				this.numbers.style.textAlign = 'right';
				this.numbers.style.padding = '4px';
				this.numbers.style.borderRight = '1px solid #D3DFC3';
				this.numbers.style.color = '#808080';
				this.numbers.style.backgroundColor = '#F0F0F0';
				this.numbers.style.height = (this.textHeight - 8) + 'px';
				this.numbers.style.width = '20px';
				this.numbers.style.fontFamily = 'consolas,monospace';
				this.numbers.style.fontSize = '12px';
				cont.appendChild(this.numbers);
				tools.setUnselectable(this.numbers);
				this.setNumbers();
			}
			
			this.node = document.createElement('iframe');
			this.node.id = 'codeedit';
			this.node.frameBorder = 0;
			this.node.style.width = (cont.offsetWidth - this.borderWidth * 2) + 'px';
			this.node.style.height = this.textHeight + 'px';
			cont.appendChild(this.node);

			this.input = document.createElement('input');
			this.input.type = 'hidden';
			this.input.name = this.input.id = this.fieldName;
			cont.appendChild(this.input);

			if(!this.initEditor()) alert("Could not create code editor");
		}
		else {
			this.node.style.whiteSpace = 'pre';
			this.node.style.padding = '2px';
			this.editor = this.node;
			tools.addListener(this.node, 'keydown', this.keyDownHandler.bindAsEventListener(this));
		}
	}

	this.getEditor = function() {
		if(IE) return document.frames[this.id];
		if(GK) return this.node.contentWindow;
		return false;
	}

	this.initEditor = function() {
		if(this.editor = this.getEditor()) {
			var html =	'<html><head><style> ' +
						'BODY { ' +
						'margin: 4px; ' +
						'background-color: ' + this.bgColor + '; ' +
						'white-space: nowrap; ' +
						'color: #000000; ' +
						'font-family:consolas,monospace; ' +
						'font-size: 12px; ' +
						'font-style: italic; ' +
						'} ' +
						'P { margin: 0px; } ' +
						'IMG { width: 1px; height: 1px; } ' +
						'var {border:1px solid pink;}' +
						this.setLanguageStyle() +
						'</style> <script>document.body.contentEditable = false;</script></head>' +
						'<div id="ohho"></div></html>';
						 
			this.editor.document.designMode = 'on';

			if(GK) {
				this.editor.document.execCommand('useCSS', false, true); /* for older browsers */
				this.editor.document.execCommand('styleWithCSS', false, false);
			}
			this.editor.document.open();
			this.editor.document.write(html);
			this.editor.document.close();
			this.canvas = this.editor.document.body;
			
			if(this.viewLineNumbers) {
				this.numbers.style.display = 'block';
				this.node.style.width = (this.node.offsetWidth - 46) + 'px';
			}

			if(this.content != '') {
				this.content = this.content.replace(/</g, '&lt;');
				this.content = this.content.replace(/>/g, '&gt;');
				this.setCode(0, 0, this.content);
				this.syntaxHilight(true);
			}
			else if(FF) {
				/* workaround for Firefox: place caret correctly into canvas */
				this.canvas.innerHTML = '<br>';
			}
			tools.addListener(this.editor.document, 'keydown', this.keyDownHandler.bindAsEventListener(this));
			tools.addListener(this.editor.document, 'keyup', this.keyUpHandler.bindAsEventListener(this));
			tools.addListener(this.editor, 'scroll', this.scrollHandler.bindAsEventListener(this));

			if(FF) {
				/* for some reason, this only works with Firefox :-( */
				tools.addListener(this.editor, 'load', this.loadHandler.bindAsEventListener(this));
			}
			else {
				/* ugly workaround for other browsers */
				setTimeout(this.loadHandler.bindAsEventListener(this), 1000);
			}
			return true;
		}
		return false;
	}

	this.setLanguageStyle = function() {
		var map = languages[this.language];
		var style = 'u, tt, b, s, i, em, ins { text-decoration: none; font-style: normal; font-weight: normal; } ';
		for(var key in map) if(map[key].style) style += map[key].style + ' ';
		return style;
	}
	
	this.setNumbers = function() {
		if(this.lines) {
			var cnt = this.lines.length + 1;
			if(cnt < this.cntLines) cnt = this.cntLines;
		}
		else var cnt = this.cntLines;

		var numbers = [];
		cnt += 10;
		for(var i = 1; i <= cnt; i++) numbers.push(i);
		this.numbers = tools.replaceHtml(this.numbers, numbers.join('<br>'));
		this.maxLines = cnt;
	}
	
	this.getCode = function(lineFrom, lineTo, convSpecialChars) {
		var code = this.canvas.innerHTML.replace(/[\r\n]/g, '');
		if(code) {
			if(IE) {
				/* ugly workaround for IE */
				code = code.replace(/<p>\u0001<\/p>/i, '\u0001');
			}
			else if(OP) {
				/* ugly workarounds for Opera */
				code = code.replace(/<p>(.*?)<br><\/p>/gi, '$1\n');
				code = code.replace(/<p><img><\/p>/i, '\n<img>');
				code = code.replace(/<p>(<[^>]+>)?<img>(<\/[^>]+>)?<\/p>/i, '\n<img>');
				code = code.replace(/<img><br>/i, '\n<img>');
			}
			else if(WK) {
				/* ugly workarounds for Chrome */
				code = code.replace(/<div><img><br><\/div>/i, '\n<img>\n');
				code = code.replace(/<div>(.*?)<br><\/div>/gi, '$1\n');
			}
			code = code.replace(/<(p|div)>(.*?)<\/(p|div)>/gi, '$2\n');
			code = code.replace(/<br>/gi, '\n');
			if(!IE) code = code.replace(/<img>/i, '\u0001');

			this.lines = code.split('\n');

			if(!lineFrom) lineFrom = 0;
			if(!lineTo || lineTo > this.lines.length) lineTo = this.lines.length;

			code = this.lines.slice(lineFrom, lineTo).join('\n');
			code = code.replace(/(&nbsp;){4}/g, '\t');
			code = code.replace(/&nbsp;/g, ' ');
			code = code.replace(/<[^>]+>/g, '');
			
			if(convSpecialChars) {
				code = code.replace(/&amp;/g, '&');
				code = code.replace(/&lt;/g, '<');
				code = code.replace(/&gt;/g, '>');
			}
		}
		return code;
	}
	
	this.setCode = function(lineFrom, lineTo, code) {
		code = code.replace(/\r?\n/g, '<br>');
		code = code.replace(/\t/g, '&nbsp;&nbsp;&nbsp;&nbsp;');
		code = code.replace(/\s/g, '&nbsp;');
		
		if(this.lines && lineTo > 0) {
			var c = [];
			c = this.lines.slice(0, lineFrom);
			c.push(code);
			c = c.concat(this.lines.slice(lineTo));
			code = c.join('<br>');
		}
		if(!IE) code = code.replace(/\u0001/, '<img>');
		this.canvas = tools.replaceHtml(this.canvas, code);
		
		if(this.numbers) {
			if(this.lines.length > this.maxLines) this.setNumbers();
			setTimeout(this.scrollHandler.bind(this), 50);
		}
	}
	
	this.parseCode = function(code) {
		var map, key, i;
		if(map = languages[this.language]) for(key in map) {
			if(map[key].match) for(i = 0; i < map[key].match.length; i++) {
				code = code.replace(map[key].match[i], map[key].replace[i]);
			}
		}
		return code;
	}

	this.insertMarker = function() {
		if(IE) this.insertText('\u0001');
		else if(GK) {
			var range = this.editor.getSelection().getRangeAt(0);
			range.insertNode(this.editor.document.createElement('img'));
		}
	}
	
	this.removeMarker = function() {
		if(IE) {
			var range = this.canvas.createTextRange();
			if(range.findText('\u0001')) {
				range.text = '';
				range.select();
			}
		}
		else if(GK) {
			var sel = this.editor.getSelection();
			var range = this.editor.document.createRange();
			var node = this.canvas.getElementsByTagName('img')[0];
			range.selectNode(node);
			if(OP) range.collapse(true);
			sel.removeAllRanges();
			sel.addRange(range);
			node.parentNode.removeChild(node);
		}
	}
	
	this.insertText = function(str) {
		if(IE) {
			var range = this.editor.document.selection.createRange();
			range.text = str;
		}
		else if(GK) {
			if(DM) {
				this.insertMarker();
				var range = this.editor.getSelection().getRangeAt(0);
				range.insertNode(this.editor.document.createTextNode(str));
				this.removeMarker();
			}
			else {
				/* special treatment for textarea */
				var start = this.editor.selectionStart;
				var end = this.editor.selectionEnd;
				var top = this.editor.scrollTop;
				var content = this.editor.value;
				this.editor.value = content.substring(0, start) + str + content.substring(end, content.length);
				this.editor.selectionStart = start + str.length;
				this.editor.selectionEnd = start + str.length;
				if(top) this.editor.scrollTop = top;
			}
		}
	}
	
	this.syntaxHilight = function(init) {
		if(init) {
			var lineFrom = lineTo = 0;
		}
		else {
			var lineFrom = Math.round(this.canvas.scrollTop / 16);
			var lineTo = lineFrom + this.cntLines;

			if(lineFrom > this.curLine) {
				var tmp = lineFrom;
				lineFrom = this.curLine;
				this.curLine = tmp;
			}
		}
		this.insertMarker();
		var code = this.parseCode(this.getCode(lineFrom, lineTo));
		this.setCode(lineFrom, lineTo, code);
		this.removeMarker();
		this.timer = null;
	}
	
//---------------------------------------------------------------------------------------------------------
// Event handlers
//---------------------------------------------------------------------------------------------------------
	this.loadHandler = function(e) {
		if(this.setFocus) this.editor.focus();
		tools.addListener(this.input.form, 'submit', this.submitHandler.bindAsEventListener(this));
	}
	
	this.keyDownHandler = function(e) {
		var evt = e ? e : this.editor.event;
		var keyCode = (evt.which || evt.keyCode || evt.charCode);
		this.paste = (keyCode == 86 && (evt.ctrlKey || evt.metaKey));
		
		if(keyCode == 9 && !evt.shiftKey && !evt.ctrlKey && !evt.altKey && !evt.metaKey) {
			this.insertText('\u00A0\u00A0\u00A0\u00A0');
			if(evt.preventDefault) evt.preventDefault();
			return false;
		}
	}
	
	this.keyUpHandler = function(e) {
		if(typeof tools == 'undefined') return;
		var evt = e ? e : this.editor.event;
		var keyCode = (evt.which || evt.keyCode || evt.charCode);
		var ctrlA = (keyCode == 65 && (evt.ctrlKey || evt.metaKey));
		var ctrlC = (keyCode == 67 && (evt.ctrlKey || evt.metaKey));
		var ignoreKey = (tools.inArray(keyCode, [16, 17]) || ctrlA || ctrlC);
		var moveKey = tools.inArray(keyCode, [33, 34, 37, 38, 39, 40]);
		
		if(!ignoreKey && !moveKey) {
			if(this.timer) clearTimeout(this.timer);
			this.timer = setTimeout(this.syntaxHilight.bind(this), 500);
		}
	}
	
	this.scrollHandler = function(e) {
		if(this.numbers) this.numbers.scrollTop = this.canvas.scrollTop;
		if(!this.paste) this.curLine = Math.round(this.canvas.scrollTop / 16);
	}
	
	this.submitHandler = function(e) {
		this.input.value = this.getCode(0, 0, true);
	}
}

//---------------------------------------------------------------------------------------------------------
// Little helpers
//---------------------------------------------------------------------------------------------------------
var tools = {

	inArray: function(val, arr, ignoreCase) {
		var str = '|' + arr.join('|') + '|';
		if(ignoreCase) {
			str = str.toLowerCase();
			val = val.toLowerCase();
		}
		return (str.indexOf('|' + val + '|') != -1);
	},
	
	addListener: function(obj, type, fn) {
		if(obj.addEventListener) {
			obj.addEventListener(type, fn, false);
		}
		else if(obj.attachEvent) {
			obj.attachEvent('on' + type, fn);
		}
	},
	
	setUnselectable: function(node) {
		node.unselectable = true;
		node.style.MozUserSelect = 'none';
		node.onmousedown = function() { return false; }
		node.style.cursor = 'default';
	},
	
	replaceHtml: function(node, html) {
		/*@cc_on // pure innerHTML is slightly faster in IE
			node.innerHTML = html;
			return node;
		@*/
		var newNode = node.cloneNode(false);
		newNode.innerHTML = html;
		node.parentNode.replaceChild(newNode, node);
		return newNode;
	}
}

//---------------------------------------------------------------------------------------------------------
// Convert textareas
//---------------------------------------------------------------------------------------------------------
tools.addListener(window, 'load', function() {
	var nodes = document.getElementsByTagName('textarea');
	var options = ceos = [];
	
	for(var i = 0; i < nodes.length; i++) {
		if(nodes[i].className.match(/^codeedit(\s+(.+))?/i)) {
			options = RegExp.$2.split(/\s+/);
			ceos.push(new CodeEdit(nodes[i], options, 'codeEdit_' + (i + 1)));
		}
	}
	for(i in ceos) ceos[i].create();
});

//---------------------------------------------------------------------------------------------------------
// Supported languages
//---------------------------------------------------------------------------------------------------------
var languages = {

	javascript: {
		operators: {
			match: [ /\/\*/g, /\*\//g, /\/\//g, /((&amp;)+|(&lt;)+|(&gt;)+|[\|!=%\*\/\+\-]+)/g, /\u0002/g, /\u0003/g, /\u0004/g ],
			replace: [ '\u0002', '\u0003', '\u0004', '<tt>$1</tt>', '/*', '*/', '//' ],
			style: 'tt { color: #C00000; }'
		},
		brackets: {
			match: [ /([\(\)\{\}\[\]])/g ],
			replace: [ '<b>$1</b>' ],
			style: 'b { color: #A000A0; font-weight: bold; }'
		},
		numbers: {
			match: [ /\b(-?\d+)\b/g ],
			replace: [ '<u>$1</u>' ],
			style: 'u { color: #C00000; }'
		},
		keywords: {
			match: [ /\b(break|case|catch|const|continue|default|delete|do|else|export|false|finally|for|function|if|in|instanceof|new|null|return|switch|this|throw|true|try|typeof|undefined|var|void|while|with)\b/g ],
			replace: [ '<em>$1</em>' ],
			style: 'em { color: #0000C0; }'
		},
		strings: {
			match: [ /(".*?")/g, /('.*?')/g ],
			replace: [ '<s>$1</s>', '<s>$1</s>' ],
			style: 's, s u, s tt, s b, s em, s i { color: #008000; font-weight: normal; }'
		},
		comments: {
			match: [ /(\/\/[^\n]*)(\n|$)/g, /(\/\*)/g, /(\*\/)/g ],
			replace: [ '<i>$1</i>$2', '<i>$1', '$1</i>' ],
			style: 'i, i u, i tt, i b, i s, i em { color: #808080; font-weight: normal; }'
		}
	},
	
	php: {
		tags: {
			match: [ /&lt;(\/?(a|abbr|acronym|address|applet|area|b|base|basefont|bdo|big|blockquote|body|br|button|caption|center|cite|code|col|colgroup|dd|del|dfn|dir|div|dl|dt|em|fieldset|font|form|frame|frameset|h[1-6]|head|hr|html|i|iframe|img|input|ins|isindex|kbd|label|legend|li|link|map|menu|meta|noframes|noscript|object|ol|optgroup|option|p|param|pre|q|s|samp|script|select|small|span|strike|strong|style|sub|sup|table|tbody|td|textarea|tfoot|th|thead|title|tr|tt|u|ul|var)(\s+.*?)?)&gt;/gi ],
			replace: [ '도$1도�' ]
		},
		operators: {
			match: [ /\/\*/g, /\*\//g, /\/\//g, /((&amp;)+|(&lt;)+|(&gt;)+|[\|!=%\*\/\+\-]+)/g, /\u0002/g, /\u0003/g, /\u0004/g, /도(.+?)도�/g ],
			replace: [ '\u0002', '\u0003', '\u0004', '<tt>$1</tt>', '/*', '*/', '//', '<em>&lt;$1&gt;</em>' ],
			style: 'tt { color: #C00000; }'
		},
		brackets: {
			match: [ /([\(\)\{\}\[\]])/g, /(<tt>)?&lt;(<\/tt>)?\?(php)?/gi, /\?(<tt>)?&gt;(<\/tt>)?/gi ],
			replace: [ '<b>$1</b>', '<b>&lt;?$3</b>', '<b>?&gt;</b>' ],
			style: 'b { color: #A000A0; font-weight: bold; }'
		},
		numbers: {
			match: [ /\b(-?\d+)\b/g ],
			replace: [ '<u>$1</u>' ],
			style: 'u { color: #C00000; }'
		},
		keywords: {
			match: [ /\b(__CLASS__|__FILE__|__FUNCTION__|__LINE__|__METHOD__|abstract|and|array|as|break|case|catch|class|clone|const|continue|declare|default|die|do|echo|else|elseif|empty|enddeclare|endfor|endforeach|endif|endswitch|endwhile|eval|exception|exit|extends|final|false|for|foreach|function|global|if|implements|include|include_once|interface|isset|list|new|or|print|private|protected|public|require|require_once|return|static|switch|this|throw|true|try|unset|use|var|while|xor)\b/g ],
			replace: [ '<em>$1</em>' ],
			style: 'em, em tt { color: #0000C0; font-weight: normal; }'
		},
		scriptAreas: {
			match: [ /({{)/g, /(}})/g ],
			replace: [ '<var>$1', '$1</var>'  ],
			style: 'var { color: #fff; background:#000; -moz-border-radius:2px; padding:1px 2px;}'
		},
		variables: {
			match: [ /(\$)(<[^>]+>)?(\w+)(<\/[^>]+>)?\b/gi ],
			replace: [ '<ins>$1$3</ins>' ],
			style: 'ins { color: #909000; }'
		},
		strings: {
			match: [ /(".*?")/g, /('.*?')/g ],
			replace: [ '<s>$1</s>', '<s>$1</s>' ],
			style: 's, s u, s tt, s b, s em, s ins, s i { color: #008000; font-weight: normal; }'
		},
		comments: {
			match: [ /(\/\/[^\n]*)(\n|$)/g, /(#[^\n]*)(\n|$)/g, /(\/\*)/g, /(\*\/)/g, /(<tt>)?&lt;(<\/tt><tt>)?!--(<\/tt>)/gi, /(<tt>)?--(<\/tt><tt>)?&gt;(<\/tt>)?/gi ],
			replace: [ '<i>$1</i>$2', '<i>$1</i>$2', '<i>$1', '$1</i>', '<i>&lt;!--', '--&gt;</i>' ],
			style: 'i, i u, i tt, i b, i s, i em, i ins { color: #808080; font-weight: normal; }'
		}
	},
	
	html: {
		scriptAreas: {
			match: [ /(&lt;script(.*?)&gt;)/gi, /(&lt;\/script&gt;)/gi ],
			replace: [ '$1<tt>', '</tt>$1' ],
			style: 'tt { color: #909000; }'
		},
		styleAreas: {
			match: [ /(&lt;style(.*?)&gt;)/gi, /(&lt;\/style&gt;)/gi ],
			replace: [ '$1<b>', '</b>$1' ],
			style: 'b { color: #A000A0; }'
		},
		variables: {
			match: [ /({{)/g, /(}})/g ],
			replace: [ '<var>$1', '$1</var>'  ],
			style: 'var { color: #fff; background:#000; -moz-border-radius:2px; padding:1px 2px; font-style:normal;}'
		},
		tags: {
			match: [ /(&lt;\/?(a|abbr|acronym|address|applet|area|b|base|basefont|bdo|big|blockquote|body|br|button|caption|center|cite|code|col|colgroup|dd|del|dfn|dir|div|dl|dt|em|fieldset|font|form|frame|frameset|h[1-6]|head|hr|html|i|iframe|img|input|ins|isindex|kbd|label|legend|li|link|map|menu|meta|noframes|noscript|object|ol|optgroup|option|p|param|pre|q|s|samp|script|select|small|span|strike|strong|style|sub|sup|table|tbody|td|textarea|tfoot|th|thead|title|tr|tt|u|ul|var)(\s+.*?)?&gt;)/gi ],
			replace: [ '<em>$1</em>' ],
			style: 'em { color: #0000C0; }'
		},
		strings: {
			match: [ /=(".*?")/g, /=('.*?')/g ],
			replace: [ '=<s>$1</s>', '=<s>$1</s>' ],
			style: 's, s tt, s b, s em, s i { color: #008000; }'
		},
		comments: {
			match: [ /(&lt;!--)/g, /(--&gt;)/g ],
			replace: [ '<i>$1', '$1</i>' ],
			style: 'i, i tt, i b, i s, i em { color: #808080; }'
		}
	},
	
	css: {
		classes: {
			match: [ /(.+?)\{/g ],
			replace: [ '<tt>$1</tt>{' ],
			style: 'tt { color: #0000C0; }'
		},
		keys: {
			match: [ /([\{\n]\s*)([\w-]*?:)([^\/])/g ],
			replace: [ '$1<u>$2</u>$3', ':' ],
			style: 'u { color: #C00000; }'
		},
		brackets: {
			match: [ /([\{\}])/g ],
			replace: [ '<b>$1</b>' ],
			style: 'b { color: #A000A0; font-weight: bold; }'
		},
		comments: {
			match: [ /(\/\*)/g, /(\*\/)/g ],
			replace: [ '<i>$1', '$1</i>' ],
			style: 'i, i tt, i u, i b { color: #808080; font-weight: normal; }'
		}
	}
}