<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Applicationtype extends CI_Controller {
   public function __construct(){
    parent::__construct(); 
    $this->load->model('Common_model','dg_model');
    }
     public function dob() 
    {
    $arr = array();
    $id = $this->input->post('id');
    $arr = $this->dg_model->getAll('address_proof','content ASC',array('status'=>'yes','prooftype'=>'DOB','pancardtype'=>$id));
    
echo json_encode($arr);
  }
  public function Identity()  {
    $arr = array();
    $id = $this->input->post('id');
    $arr = $this->dg_model->getAll('address_proof','content ASC',array('status'=>'yes','prooftype'=>'Identity','pancardtype'=>$id));
    
echo json_encode($arr);
  }
   public function Address()  {
    $arr = array();
    $id = $this->input->post('id');
    $arr = $this->dg_model->getAll('address_proof','content ASC',array('status'=>'yes','prooftype'=>'Address','pancardtype'=>$id));
    
echo json_encode($arr);
  }

}