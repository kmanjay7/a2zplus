<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Search_city extends CI_Controller {
    
   public function __construct(){
    parent::__construct();
    $this->load->library('session');  
    $this->load->model('Common_model','dg_model');
    }
               
     public function index()
	{  
        $citydata = array();
        $id = $this->input->post('id');
        if($id){
            $citydata = $this->dg_model->getAll('city', 'cityname ASC', array('stateid'=>$id),null );
        }
        echo json_encode($citydata);
	} 
}
