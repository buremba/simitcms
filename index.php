<?php
require_once('function.php');
require_once('smt_loader.php');

class Model {
   public function user_info()
   {
      // simulates real data
      return array(
         'first' => 'Jeffrey',
         'last'  => 'Way'
      );
   }
}

class Controller {
   public $load;
   public $model;

   function __construct()
   {
      $this->load = new Load();
      $this->model = new Model();

      // determine what page you're on
      $this->page();
   }

   function home()
   {
      $data = $this->model->user_info();
      $this->load->view('index.php', $data);
   } 
   
   function page()
   {
      $data = $this->model->user_info();
      $this->load->view('page.php', $data);
   }
}

new Controller(); 

?>