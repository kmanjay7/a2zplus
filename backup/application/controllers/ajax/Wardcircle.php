<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Wardcircle extends CI_Controller {
   public function __construct(){
    parent::__construct(); 
    $this->load->model('Common_model','dg_model');
    }
     public function detail() 
    {
    $id = $this->input->post('id');
    $query = $this->dg_model->getSingle('wardcircle',array('id'=>$id),'*');
    if(!empty($query)){
       $arra = array('status'=>'1','id'=>$query['id'],'areacode'=>$query['areacode'],'aotype'=>$query['aotype'],'rangecode'=>$query['rangecode'],'aono'=>$query['aono']);  
    }else{
         $arra = array('status'=>'0');
    }
echo json_encode($arra);
  }
  
  public function wardlist(){
      {  
        $wardcircle = array();
        $id = $this->input->post('id');
        if($id){
            $wardcircle = $this->dg_model->getAll('wardcircle', 'id ASC', array('cityid'=>$id,'status'=>'yes') );
        }
        echo json_encode($wardcircle);
	}
  }
}