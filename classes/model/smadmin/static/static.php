<?php

class Model_Admin_Static_Static extends Model {

	function batchsave($array) {
		foreach ($array as $key => $data) {
			$action = db::update('static', array('content' => serialize($data)), array('id' => intval($key)));
			if (!$action) {
				return false;
			}
		}
		return true;
	}

}
