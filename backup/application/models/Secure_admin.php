<?php defined("BASEPATH") OR exit("No Direct Access Allowed!");

class Secure_admin extends CI_Model{
  
  function __construct() {
      parent::__construct();
   }

public function secureadmin(){
   $user_data = $this->session->userdata('adminloginstatus');
   return $user_data;
}

}
?>