<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Smadmin_Dashboard extends Controller_Template {
	public $template = 'smadmin/template';

	public function action_index() {
		$this -> template -> content = View::factory('smadmin/dashboard');
	}

}
