<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Smadmin_Cruds extends Controller_Template {
	public $template = 'smadmin/template';

	function before() {
        parent::before();
		$this -> model = Model::factory('smadmin_cruds_tours');
		if ($this -> request -> action() != 'index' && !$this -> request -> param('id')) {
			//throw new HTTP_Exception_404('You need to show what you want. :)');
		} else {
			$this -> crud = $this -> request -> param('crudname');
		}
	}

	function action_index() {
		$cruds = Kohana::list_files('classes/model/smadmin/cruds');
		$this -> template -> content = null;
		foreach ($cruds as $crud) {
			$crud = basename($crud, '.php');
			$this -> template -> content .= "<div class='vertical crudlist' id='{$crud}'><a style='text-transform:capitalize' href='".URL::site(ADMINPATH)."/cruds/{$crud}'>{$crud}</a></div>";
			$this -> template -> menu = "<li><a href='customsetup.php'>Create a new crud</a></li>";
		}
	}

	function action_list() {
		$view = View::factory("admin/cruds/{$this->crud}/list");
		$this->template->scripts = 'media/admin/js/jquery.dataTables.min.js';
		$this->template->styles = 'media/admin/css/dataTables.css';
		
		if (isset($_POST["activestatus"]) && isset($_POST['rowid'])) {
			$this->model->updatestatus();
		}
		
		$this->model->pre_list();
		
		$this -> template -> content = $view;
	}
	
	function action_ajax() {
		$this->auto_render=false;
		if (!$this->request->is_ajax()) {
			//
		}
		
		$message = Model::factory('smadmin_ajaxcrud')->getjson($_GET);
		$this->response->body($message);
	}

	function action_new() {
		if (isset($_POST["var0"])) {
			$action = $this -> model -> save($_POST);
			if (!$action) {
				Message::error(Kohana::message('crud', 'error.entrycreated'));
				$this -> request -> redirect('cruds/tours/new');
			} else {
				Message::success(Kohana::message('crud', 'success.entrycreated'));
				$this -> request -> redirect('cruds/tours' . (isset($_POST["saveandedit"]) ? "/edit/" . mysql_insert_id() : ""));
			}

		}

		$view = View::factory('admin/cruds/tours/new');
		$this -> template -> content = $view;
	}

	function action_edit() {
		$id = (int) $this -> request -> param('id');

		if (isset($_POST["var0"])) {
			$action = $this -> model -> update($id, $_POST);
			if (!action) {
				Message::error(Kohana::message('crud', 'error.entrycreated'));
				$this -> request -> redirect('cruds/tours');
			} else {
				Message::success(Kohana::message('crud', 'success.entrycreated'));
				$this -> request -> redirect('cruds/tours' . (isset($_POST["saveandedit"]) ? "/edit/" . mysql_insert_id() : ""));
			}

			$view = View::factory('admin/cruds/tours/edit');
			$view -> row = db::fetchone("SELECT * FROM $table WHERE id={$id}");
			$this -> template -> content = $view;
		}

		function action_delete() {
			/*
			 if(isset($this->request->param('id')) && is_numeric($this->request->param('id'))) {
			 $id = $this->request->param('id');
			 }
			 */

			if (isset($id)) {
				$action = db::delete("c_tours", array("id" => $_GET["del"]));
				if ($action) {
					Message::success(Kohana::message('crud', 'success.entrydeleted'));
					$this -> request -> redirect('cruds/tours');
				} else {
					Message::error(Kohana::message('crud', 'error.entrydeleted'));
					$this -> request -> redirect('cruds/tours');
				}
			}else if (isset($_POST["tableitems"])) {
				foreach ($_POST["tableitems"] as $id) {
					$action = db::delete("c_tours", array("id" => $id));
					if (!$action) {
						Message::error(Kohana::message('crud', 'error.entrydeleted'));
						$this -> request -> redirect('cruds/tours');
					}
				}
				Message::success(Kohana::message('crud', 'success.entrydeleted'));
				$this -> request -> redirect('cruds/tours');
			}
		}

	}

}
?>