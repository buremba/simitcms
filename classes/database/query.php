<?php defined('SYSPATH') or die('No direct script access.');

class Database_Query extends Kohana_Database_Query {

   public function execute($db = NULL, $as_object = NULL, $object_params = NULL)
   {
		var_dump($db);
      $result = parent::execute($db = NULL, $as_object = NULL, $object_params = NULL);
      // now you can log $db->last_query - its a last query text
      return $result;
   }
}