<?php

function sm_static($title=null, $category = null, $type = 'text') {
	$row = db::fetchone("SELECT * FROM static WHERE ".((is_int($title) && (func_num_args()==1)) ? "id = $title" : "title = '$title'"));
	if (count($row)>0) {
		$view = Kohana::find_file("views", "smadmin/static/types/{$row['type']}/frontend");
		$row['content'] = unserialize($row['content']);
		if($view) {
			return View::factory("smadmin/static/types/{$row['type']}/frontend")->bind('id', $row['id'])->bind('title', $row['title'])->bind('description', $row['description'])->bind('content', $row['content'])->bind('content', $row['content']);
		}else {
			return $row['content'];
		}
	}else
	if(isset($title) && isset($type) && isset($category)){
		return db::sendquery("INSERT INTO static (`title`, `category`, `type`) VALUES ('{$title}', '{$category}', '{$type}')");
	}else {
		return false;
	}
	
}