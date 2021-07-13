<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Changeuserstatus extends CI_Controller {
    
   public function __construct(){
    parent::__construct(); 
    $this->load->model('Common_model','dg_model');
    }
               
     public function index() 
    {
    $id = $this->input->post('id');
    $status = $this->input->post('status');
     $update = $this->dg_model->saveupdate('dt_users', array('status'=>$status),null,array('id'=>$id));
     echo $parentid = $this->dg_model->getSingle('users',array('id'=>$id),'status');
  } 
}
