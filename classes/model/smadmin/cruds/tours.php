<?php

class Model_smadmin_cruds_tours extends Model {
	protected $table = 'c_tours';
	
	public function save($post) {
		return db::insert($this->table, $this -> preparearray($post));
	}
	
	public function edit($id, $post) {
		return db::update($this->table, $this -> preparearray($post), array("id" => $id));
	}
	
	public function updatestatus($id, $value) {
		return db::update("c_{$this->table}", array('is_active' => $value), array('id' => $id));
	}

	private function preparearray($post) {
		foreach ($post["extraprice"] as $key => $case) {
			$post["extraprice"][$key]['start'] = strtotime($post["extraprice"][$key]['start']);
			$post["extraprice"][$key]['finish'] = strtotime($post["extraprice"][$key]['finish']);
		}

		foreach ($post["specialdates"] as $key => $case) {
			$post["specialdates"][$key]['start'] = strtotime($post["specialdates"][$key]['start']);
			$post["specialdates"][$key]['finish'] = strtotime($post["specialdates"][$key]['finish']);
		}

		$dbset["lang"] = $post["var0"];
		$dbset["is_flight"] = $post["is_flight"];
		$dbset["is_active"] = $post["var1"];
		$dbset["showroom"] = $post["showroom"];
		$dbset["code_no"] = $post["var2"];
		$dbset["name"] = $post["var3"];
		$dbset["description"] = $post["var4"];
		$dbset["in_tour"] = $post["var5"];
		$dbset["out_tour"] = $post["var6"];
		$dbset["extra_notes"] = $post["var7"];
		$dbset["images"] = $post["var8"];
		$dbset["category"] = $post["var9"];
		$dbset["country"] = serialize($post["var10"]);
		$dbset["files"] = serialize(array('doc' => $post["docfile"], 'pdf' => $post["pdffile"]));
		$dbset["meta_key"] = $post["var12"];
		$dbset["meta_desc"] = $post["var13"];
		$dbset["cl_discount"] = $post["var14"];
		$dbset["total_days"] = $post["var15"];
		$dbset["first_day"] = strtotime($post["var16"]);
		$dbset["last_day"] = strtotime($post["var17"]);
		$dbset["remove_before"] = $post["var18"];
		$dbset["default_price"] = array($post["var19"], $post["hotel"]['tourist']['double']);
		$dbset["min_people"] = $post["var20"];
		$dbset["hotels"] = serialize($post["hotel"]);
		$dbset["overnight"] = serialize($post["overnight"]);
		$dbset["extra_tours"] = serialize($post["extratour"]);
		$dbset["extra_prices"] = serialize($post["extraprice"]);
		$dbset["flights"] = serialize($post["flights"]);
		$dbset["active_days"] = serialize($post["active_days"]);
		$dbset["extra_dates"] = serialize($post["specialdates"]);
	}

	public function delete() {

	}

	public function batchdelete() {

	}

	

}
?>