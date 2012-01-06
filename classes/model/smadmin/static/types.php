<?php
class Model_admin_static_types extends Model {
	public function __construct($where) {
		$this -> where = $where;
	}

	public function youtube($id, $title, $content, $description) {
		if ($this -> where == 'adminpanel') {
			return <<<html
		<div class='element configurable' id="{$id}">
			<label>{$title}</label>
			<input type="text" name="{$id}" value="{$content}" id="{$id}">
			<span class="desc">{$description}</span>
		</div>
html;
		}else if ($this -> where == 'frontend') {
			return "<iframe width='420' height='315' src='http://www.youtube.com/embed/{$content}' frameborder='0' allowfullscreen></iframe>";
		}
	}

	public function gmaps($id, $title, $content, $description) {
		if ($this -> where == 'adminpanel') {
			return <<<html
		<div class='element configurable' id="{$id}">
			<label>{$title}</label>
			<input type="text" name="{$id}[]" value="{$content[0]}" id="{$id}">
			<input type="text" name="{$id}[]" value="{$content[1]}" id="{$id}">
			<span class="desc">{$description}</span>
		</div>
html;
		}else if ($this -> where == 'frontend') {
			return "<iframe width='425' height='350' frameborder='0' scrolling='no' marginheight='0' marginwidth='0' src='http://maps.google.com/?ll{$content[0]},{$content[1]}=&amp;t=h&amp;z=9&amp;output=embed'></iframe>";
		}
	}

	public function textarea($id, $title, $content, $description) {
		if ($this -> where == 'adminpanel') {
			return <<<html
		<div class='element configurable' id="{$id}">
			<label>{$title}</label>
			<textarea name="{$id}" id="textarea">{$content}</textarea>
			<span class="desc">{$description}</span>
		</div>
html;
		}else if ($this -> where == 'frontend') {
			return "{$content}";
		}
	}

	public function number($id, $title, $content, $description) {
		if ($this -> where == 'adminpanel') {
			return <<<html
		<div class='element configurable' id="{$id}">
			<label>{$title}</label>
			<input type="text" name="{$id}" class="validate[custom[number]]" id="{$id}" value="{$content}">
			<span class="desc">{$description}</span>
		</div>
html;
		}else if ($this -> where == 'frontend') {
			return "{$content}";
		}
	}

	public function email($id, $title, $content, $description) {
		if ($this -> where == 'adminpanel') {
			return <<<html
		<div class='element configurable' id="{$id}">
			<label>{$title}</label>
			<input type="text" name="{$id}" class="validate[custom[email]]" id="{$id}" value="{$content}">
			<span class="desc">{$description}</span>
		</div>
html;
		}else if ($this -> where == 'frontend') {
			return "{$content}";
		}
	}

	public function url($id, $title, $content, $description) {
		if ($this -> where == 'adminpanel') {
			return <<<html
		<div class='element configurable' id="{$id}">
			<label>{$title}</label>
			<input type="text" name="{$id}" class="validate[custom[url]]" id="{$id}" value="{$content}">
			<span class="desc">{$description}</span>
		</div>
html;
		}else if ($this -> where == 'frontend') {
			return "{$content}";
		}
	}

	public function ckeditor($id, $title, $content, $description) {
		if ($this -> where == 'adminpanel') {
			$ckeditor = true;
			return <<<html
		<div class='element configurable' id="{$id}">
			<label>{$title}</label>
			<textarea name="{$id}" class="ckeditor" id="{$id}">{$content}</textarea>
			<span class="desc">{$description}</span>
		</div>
html;
		}else if ($this -> where == 'frontend') {
			return "{$content}";
		}
	}

	public function image($id, $title, $content, $description) {
		if ($this -> where == 'adminpanel') {
			return <<<html
		<div class='element configurable' id="{$id}">
			<label>{$title}</label>
			<input type='text' name='{$id}' id='{$id}' class='image validate[required,custom[url]]' value='{$content}'>
			<span class='button grey uploader'>Upload</span><span class='button leftmargin' id='customimgbrowse' alt='var0'>Browse</span>
			<span class='status'></span>
			<span class="desc">{$description}</span>
		</div>
html;
		}else if ($this -> where == 'frontend') {
			return "<img src='{$content}'>";
		}
	}

	public function date($id, $title, $content, $description) {
		if ($this -> where == 'adminpanel') {
			return <<<html
		<div class='element configurable' id="{$id}">
			<label>{$title}</label>
			<input type="text" name="{$id}" class="validate[custom[date]] date" id="{$id}" value="{$content}">
			<span class="desc">{$description}</span>
		</div>
html;
		}else if ($this -> where == 'frontend') {
			return "{$content}";
			// timestamp
		}
	}

	public function text($id, $title, $content, $description) {
		if ($this -> where == 'adminpanel') {
			return <<<html
			<div class='element configurable' id="{$id}">
				<label>{$title}</label>
				<input type="text" name="{$id}" id="{$id}" value="{$content}">
				<span class="desc">{$description}</span>
			</div>
html;
		}else if ($this -> where == 'frontend') {
			return $content;
		}
	}

	public function monoarray($id, $title, $content, $description) {
		if ($this -> where == 'adminpanel') {
			if ($content == null) {$content = array();
			}
			$pairs = array();
			foreach ($content as $key => $pair) {
				$pairs[] = "<div class='clear inputpair'><input type='text' id='{$id}' name='{$id}[]' value='{$pair}'><remove onclick='$(this).parent().remove();'>-</remove></div>";
			}
			$pairs = implode("\n", $pairs);
			return <<<html
		<div class='element configurable' id="{$id}">
			<label>{$title}</label>
			<div class="inputgroup" name="{$id}">
				<p class="add">Add new value</p>
				<example style="display:none;"><div class="clear inputpair"><input type="text" id="{$id}" name="%s[]"><remove onclick="$(this).parent().remove();">-</remove></div></example>
				{$pairs}
			</div>
			<span class="desc">{$description}</span>
		</div>
html;
		}else if ($this -> where == 'frontend') {
			return $content;
		}
	}

	public function multikey($id, $title, $content = array(), $description) {
		if ($this -> where == 'adminpanel') {
			$pairs = array();
			foreach ($content as $key => $pair) {
				$pairs[] = '<div class="clear inputpair"><input type="text" id="' . $id . '" value="' . $pair['key'] . '" name="' . $id . '[' . $key . '][key]"> = <input type="text" name="' . $id . '[' . $key . '][value]" id="' . $id . '" value="' . $pair['value'] . '"><remove onclick="$(this).parent().remove();">-</remove></div>';
			}
			$pairs = implode("\n", $pairs);
			return <<<html
		<div class='element configurable' id="{$id}">
			<label>{$title}</label>
			<div class="inputgroup" name="{$id}">
				<p class="add">Add new key value</p>
					<example style="display:none;">
						<div class="clear inputpair"><input type="text" id="{$id}" name="%s[%n][key]"> = <input type="text" name="%n[%s][value]"><remove onclick="$(this).parent().remove();">-</remove></div>
					</example>
					{$pairs}
			</div>
			<span class="desc">{$description}</span>
		</div>
html;
		}else if ($this -> where == 'frontend') {
			$i = 0;
			//$array['title'] = $title;
			foreach ($content as $c) {
				$array[$i]['key'] = $c['key'];
				$array[$i]['value'] = $c['value'];
				$i++;
			}
			return $array;
		}
	}

}

/*
 $a = 'text';
 $static = new smt_static('adminpanel');
 echo $static->$a(30, 'deneme', 'content', 'desc');
 */
?>