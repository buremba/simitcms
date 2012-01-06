<?php defined('SYSPATH') or die('No direct script access.');

class Model_smadmin_cruds_events extends RedBean_SimpleModel {
	public $table = 'c_events';

	public function insert($array, $prepared = false) {
	if(!$prepared) $array = $this->prepare($array);
	$row = R::dispense($table);
	$row -> title = $array -> title;
	$row -> author = $array -> author;
	return R::store($book); // it returns id
	}

	public function update($array, $id, $prepared = false) {
		if(!$prepared) $array = $this->prepare($array);
		$check = $this -> validate($array);
		if($check->validate()) {
			$row = R::load($table, $id);
			$row -> lang = $array -> lang;
			$row -> name = $array -> name;
			$row -> start_date = $array -> start_date;
			$row -> finish_date = $array -> finish_date;
			$row -> description = $array -> description;
			$row -> gallery = $array -> gallery;
			$row -> meta_keys = $array -> meta_keys;
			$row -> meta_description = $array -> meta_description;
			return R::store($row); // it returns id
		}else {
			return array(false, $check->validate()->errors());
		}
	}

	public function delete($id) {
		$row = R::load($table, $id);
		R::trash($book);
	}
	
	public function prepare($array) {
		$array['start_date'] = strtotime($array['start_date'] );
		$array['finish_date'] = strtotime($array['finish_date'] );
	}

	public function validate($post) {
		$post = Validation::factory($post);
		$post 
		-> rule('lang', 'in_array', array(':value', sm_static("Languages")))
		-> rule('name', 'not_empty') 
		-> rule('start_date', 'numeric')
		-> rule('finish_date', 'numeric');
	}

}
