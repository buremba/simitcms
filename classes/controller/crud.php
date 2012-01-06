<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Crud extends Controller {
	public $table = "events";

	function before() {
		parent::before();
		//$this -> model = Model::factory("cruds_".$this->request->controller());
	}

	function action_index() {
		if (isset($_POST["activestatus"]) && isset($_POST['rowid'])) {
			$action = db::update($this->table, array('is_active' => $_POST['activestatus']), array('id' => $_POST['rowid']));
			if ($action) {
				die('true');
			} else {
				die('false');
			}
		}
		$view = View::factory("cruds/{$this->table}/list");
		$this -> response -> body($view);
	}

	function action_new() {
		if (isset($_POST["var0"])) {
			$action = $this -> model -> save($_POST);
			if (!$action) {
				Message::error(Kohana::message('crud', 'error.entrycreated'));
				$this -> request -> redirect("cruds/{$this->table}");
			} else {
				Message::success(Kohana::message('crud', 'success.entrycreated'));
				$this -> request -> redirect("cruds/{$this->table}" . (isset($_POST["saveandedit"]) ? "/edit/" . mysql_insert_id() : ""));
			}

		}

		$view = View::factory("cruds/{$this->table}/new");
		$this -> response -> body($view);
	}

	function action_edit() {
		$id = (int)$this -> request -> param('id');

		if (isset($_POST["var0"])) {
			$action = $this -> model -> update($id, $_POST);
			if (!action) {
				Message::error(Kohana::message('crud', 'error.entrycreated'));
				$this -> request -> redirect("cruds/{$this->table}");
			} else {
				Message::success(Kohana::message('crud', 'success.entrycreated'));
				$this -> request -> redirect("cruds/{$this->table}" . (isset($_POST["saveandedit"]) ? "/edit/" . mysql_insert_id() : ""));
			}

			$view = View::factory("cruds/{$this->table}/edit");
			$view -> row = db::fetchone("SELECT * FROM $table WHERE id={$id}");
			$this -> response -> body($view);
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
					$this -> request -> redirect("cruds/{$this->table}");
				}
			}else if (isset($_POST["tableitems"])) {
				foreach ($_POST["tableitems"] as $id) {
					$action = db::delete("c_tours", array("id" => $id));
					if (!$action) {
						Message::error(Kohana::message('crud', 'error.entrydeleted'));
						$this -> request -> redirect("cruds/{$this->table}");
					}
				}
				Message::success(Kohana::message('crud', 'success.entrydeleted'));
				$this -> request -> redirect("cruds/{$this->table}");
			}
		}

	}

}
