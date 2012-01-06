<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Smadmin_Static extends Controller_Template {
	public $template = 'smadmin/template';

	public function before() {
		parent::before();
		$statics = db::fetchquery('SELECT id, category FROM static GROUP BY category ORDER by category ASC');
		$this -> template -> menu = array();
		foreach ($statics as $item) {
			if ($item['category'] == "") {
				$item['category'] = 'General';
				$this -> firstopening = null;
			}
			if (!isset($firstopening)) {
				$this -> firstopening = $item['category'];
			}
			$this -> template -> menu[] = array('link' => SM_SCRIPTPATH."admin/static/list/".$item['id'], 'title' => $item['category']);
		}
		$this -> model = Model::factory('smadmin_static_static');
	}

	public function action_index() {
		$view = View::factory('admin/static/list');

		$view -> statics = db::fetchquery('SELECT * FROM static WHERE category = "' . $this -> firstopening . '" ORDER BY id');
		$view -> static = new Model_admin_static_types('adminpanel');
		$view -> types = get_class_methods($view -> static);
		if (!empty($_POST)) {
			$action = $this -> model -> batchsave($_POST);
			if ($action) {
				Message::success(Kohana::message('static', 'success.saved'));
				$this -> request -> redirect('admin/static');
			} else {
				Message::error(Kohana::message('static', 'error.saved'));
				$this -> request -> redirect('admin/static');
			}
		}

		$this -> template -> content = $view;
	}

	public function action_list() {
		$view = View::factory('admin/static/list');
		$view->id = (int) $this -> request -> param('id');
		$view -> static = new Model_admin_static_types('adminpanel');
		$view -> types = get_class_methods($view -> static);
		$view -> statics = db::fetchquery("SELECT * FROM static WHERE category = (SELECT category FROM static WHERE id = {$view->id} ) ORDER BY id");
		if (!empty($_POST)) {
			$action = $this -> model -> batchsave($_POST);
			if ($action) {
				Message::success(Kohana::message('static', 'success.saved'));
				$this -> request -> redirect("admin/static/list/{$view->id}");
			} else {
				Message::error(Kohana::message('static', 'error.saved'));
				$this -> request -> redirect("admin/static/list/{$view->id}");
			}
		}

		$this -> template -> content = $view;
	}

	public function action_new() {
		if (isset($_POST['save'])) {
			$action = db::insert('static', array('title' => $_POST['title'], 'type' => $_POST['type'], 'category' => $_POST['category'], 'description' => $_POST['description']));
			if ($action) {
				Message::success(Kohana::message('static', 'success.saved'));
				$this -> request -> redirect('admin/static');
				send('"' . $_POST['title'] . '" başarıyla oluşturuldu!', 'success', 'static.php' . (($_POST['save'] == 'Save and edit again') ? '?edit=' . mysql_insert_id() : ''));
			} else {
				send('Alan oluşturulamadı!', 'error', 'static.php');
			}
		}

		$view = View::factory('admin/static/new');
		$view -> types = get_class_methods(new Model_admin_static_types('adminpanel'));
		array_shift($view -> types);
		$this->template -> content = $view;
	}

	public function action_edit() {
		$id = (int) $this -> request -> param('id');

		if (isset($_POST['save'])) {
			$action = db::update('static', array('title' => $_POST['title'], 'category' => $_POST['category'], 'description' => $_POST['description']), array('id' => $id));
			if ($action) {
				send('"' . $_POST['title'] . '" başarıyla düzenlendi!', 'success', 'static.php' . (($_POST['save'] == 'Save and edit again') ? '?edit=' . $id : ''));
			} else {
				send('"' . $_POST['title'] . '" düzenlenemedi!', 'error', 'static.php');
			}
		}

		$view = View::factory('admin/cruds/tours/edit');
		$view -> static = db::fetchone("SELECT * FROM static WHERE id=$id");

		$this -> template -> content = $view;
	}

	public function action_delete() {
		$id = (int) $this -> request -> param('id');

		$action = db::delete('static', array('id' => $id));
		if ($action) {
			send('Alan başarıyla silindi!', 'success', 'static.php');
		} else {
			send('Alan silinemedi!', 'error', 'static.php');
		}
	}

}
