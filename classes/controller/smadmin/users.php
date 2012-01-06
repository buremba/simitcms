<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Smadmin_User extends Controller_Template {
	public $template = 'smadmin/template';

	public function action_login() {
		$this -> auto_render = false;
		$view = View::factory('admin/user/login');
		
		if (!empty($_POST)) {
			if (isset($_POST['remember']) && $_POST['remember'] == 1) {
				$remember = TRUE;
			} else {
				$remember = FALSE;
			}
			$login = $user -> login($_POST['loguser'], $_POST['logpass'], $remember);
			if (!$login) {
				$view->error = 'Wrong username and password combination.';
			} elseif ($login == 1) {
				$view->error = "Your browser doesn't allow cookies, but we have to use cookie feature.";
			} elseif ($login == 2) {
				
			}
		}
		
		$this -> response -> body($view);
	}

}
