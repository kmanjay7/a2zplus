<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Checkuniqid extends CI_Controller {
    
   public function __construct(){
    parent::__construct(); 
    $this->load->library('session'); 
    $this->load->model('Common_model','dg_model');
    }
               
   public function index()
   {  
      $mobile = $this->input->post('mobile');
      $user_type = $this->input->post('user_type');

      if($user_type)
      {
         $checkArr=['user_type'=>$user_type,'uniqueid'=>$mobile];
      }else{
         $checkArr=['uniqueid'=>$mobile];
      }

      if($mobile){
         $user = $this->dg_model->getSingle( 'dt_users',$checkArr,'id');
         if(!empty($user)) {
            echo '1';
         }else{
            echo '2'; 
         }    
      }
   }
   
}
