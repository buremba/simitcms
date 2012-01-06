<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Smadmin_Media extends Controller_Template {
	public $template = 'smadmin/template';

	public function action_upload() {
		$this -> auto_render = false;

		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");

		// it returns full path of image, if you want to use url you can change configs/uploads.php
		$response = Model::factory("admin_media") -> upload(Kohana::$config -> load('upload.directory'), $_FILES['file']);

		if (empty($response['error'])) {
			db::insert('media', array('address' => $response['url'], 'mimetype' => pathinfo($response['url'], PATHINFO_EXTENSION), 'from' => ($this -> request -> param('id')) ? $this -> request -> param('id') : 'media_page', 'author' => $GLOBALS['user'] -> get_property('id'), 'time' => time(), ));
		}
		$response['url'] = Kohana::$config -> load('upload.url').$response['url'];
		$this -> response -> body(json_encode($response));
	}

	public function action_delete() {
		$model = Model::factory("admin_media");
		$id = (int) $this -> request -> param('id');
		if ($id > 0) {
			$action = $model -> delete($id);
			if (!$action) {
				Message::error(Kohana::message('upload', 'error.deleted'));
				$this -> request -> redirect('media');
			} else {
				Message::success(Kohana::message('upload', 'success.deleted'));
				$this -> request -> redirect('media' . (isset($_POST["saveandedit"]) ? "/edit/" . mysql_insert_id() : ""));
			}
		}else
		if (isset($_POST["tableitems"])) {
			foreach ($_POST["tableitems"] as $del) {
				if(is_numeric($del)) {
					$action = $model -> delete($del);
				}
			}
			if (!$action) {
				Message::error(Kohana::message('upload', 'error.deleted'));
				$this -> request -> redirect('media');
			} else {
				Message::success(Kohana::message('upload', 'success.deleted'));
				$this -> request -> redirect('media');
			}
		}
	}

	public function action_index() {
		$this -> template -> content = View::factory("admin/media");
		$this -> template -> scripts = array();
		$this -> template -> scripts[] = 'media/admin/js/jquery.dataTables.min.js';
		$this -> template -> scripts[] = 'media/admin/js/plupload/plupload.full.js';
		//$this->template->scripts[] = 'media/admin/js/plupload/jquery.ui.plupload/jquery.ui.plupload.js';
		$this -> template -> scripts[] = 'media/admin/js/plupload/jquery.plupload.queue/jquery.plupload.queue.js';
		$this -> template -> styles = array();
		$this -> template -> styles[] = 'media/admin/css/dataTables.css';
		$this -> template -> styles[] = 'media/admin/js/plupload/jquery.plupload.queue/css/jquery.plupload.queue.css';
	}

}
