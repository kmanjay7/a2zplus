<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Shemeamount extends CI_Controller {
    
   public function __construct(){
    parent::__construct(); 
    $this->load->model('Common_model','dg_model');
    }
               
     public function index()
	{  
       $id = $this->input->post('id');
        if($id){
            $scheme = $this->dg_model->getSingle( 'dt_scheme',array('id'=>$id),'sch_amount');
        }
        echo $scheme;
	} 
}
