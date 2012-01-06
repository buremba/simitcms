<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Template_Crud extends Controller_Template {
	public $template = "smadmin/template";

	function before() {
		parent::before();
		require_once Kohana::find_file('vendor', 'redbean/rb');
		$this -> model = Model::factory("smadmin_cruds_".$this->request->controller());
	}
	
	function action_ajax() {
		$this->auto_render=false;
		if (!$this->request->is_ajax()) {
			throw new HTTP_Exception_503('This is not a normal call');
		}
		
		$message = Model::factory('smadmin_ajaxcrud')->getjson($_GET);
		$this->response->body($message);
	}

	function action_index() {
		if ($this->request->post('activestatus') && $this->request->post('rowid')) {
			$action = db::update($this->request->controller(), array('is_active' => $_POST['activestatus']), array('id' => $_POST['rowid']));
			if ($action) {
				die('true');
			} else {
				die('false');
			}
		}
		$view = View::factory("smadmin/cruds/{$this->request->controller()}/list");
		$this -> template->content = $view;
	}

	function action_new() {
		if (isset($_POST["var0"])) {
			$action = $this -> model -> save($_POST);
			if (!$action) {
				Message::error(Kohana::message('crud', 'error.entrycreated'));
				$this -> request -> redirect("cruds/{$this->request->controller()}");
			} else {
				Message::success(Kohana::message('crud', 'success.entrycreated'));
				$this -> request -> redirect("cruds/{$this->request->controller()}" . (isset($_POST["saveandedit"]) ? "/edit/" . mysql_insert_id() : ""));
			}

		}

		$view = View::factory("smadmin/cruds/{$this->request->controller()}/new");
		$this -> template->content = $view;
	}

	function action_edit() {
		$id = (int)$this -> request -> param('id');

		if (isset($_POST["var0"])) {
			$action = $this -> model -> update($id, $_POST);
			if (!action) {
				Message::error(Kohana::message('crud', 'error.entrycreated'));
				$this -> request -> redirect("cruds/{$this->request->controller()}");
			} else {
				Message::success(Kohana::message('crud', 'success.entrycreated'));
				$this -> request -> redirect("cruds/{$this->request->controller()}" . (isset($_POST["saveandedit"]) ? "/edit/" . mysql_insert_id() : ""));
			}
		}
		
		$view = View::factory("smadmin/cruds/{$this->request->controller()}/edit");
		$view -> row = $this -> model -> get($id);
		$this -> template->content = $view;
	}

		function action_delete() {
			$id = (int)$this -> request -> param('id');
			if ($id > 0) {
				$id = $this -> request -> param('id');
			}

			if (isset($id)) {
				$action = db::delete("c_tours", array("id" => $_GET["del"]));
				if ($action) {
					Message::success(Kohana::message('crud', 'success.entrydeleted'));
					$this -> request -> redirect('cruds/tours');
				} else {
					Message::error(Kohana::message('crud', 'error.entrydeleted'));
					$this -> request -> redirect("cruds/{$this->request->controller()}");
				}
			}else if (isset($_POST["tableitems"])) {
				foreach ($_POST["tableitems"] as $id) {
					$action = db::delete("c_tours", array("id" => $id));
					if (!$action) {
						Message::error(Kohana::message('crud', 'error.entrydeleted'));
						$this -> request -> redirect("cruds/{$this->request->controller()}");
					}
				}
				Message::success(Kohana::message('crud', 'success.entrydeleted'));
				$this -> request -> redirect("cruds/{$this->request->controller()}");
			}
	}

}
