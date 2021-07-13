<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Reset_pass extends CI_Controller{
	
	public function __construct(){
		parent::__construct(); 
    $this->load->library('session');
               $this->load->model('Common_model','dg_model');
               date_default_timezone_set('Asia/Kolkata');
                adsession_check();
		}
	
	public function index(){ 
        $data['title'] = 'Login Page';
        adview('reset',$data);
	}
        public function resetpass(){
          $post = $this->input->post(); 
          $useridd = $this->session->userdata('id');
          $userid = $this->dg_model->getSingle('users',array('id'=>$useridd,'password'=>$post['oldpass']),'id');
          if($post['password'] == $post['cpassword'] && !empty($userid)){
           $query1  = $this->dg_model->saveupdate('users',array('password'=>$post['password'],'en_password'=>md5($post['password'])),null, array('id'=>$userid),null );   
           $this->session->set_flashdata('success', 'your password has been changed successfully!!');
           redirect(ADMINURL.'logout');
           
          }else{
           $this->session->set_flashdata('error', 'Password/ Confirm password did not match!!!');
            redirect(ADMINURL.'ad/reset_pass');     
          }
        }
}