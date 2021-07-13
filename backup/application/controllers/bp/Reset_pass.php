<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Reset_pass extends CI_Controller{
	
	public function __construct(){
		parent::__construct(); 
    $this->load->library('session'); 
    bpsession_check();
		}
	
	public function index(){ 
        $data['title'] = 'Login Page';
        bpview('reset',$data);
	}
        public function resetpass(){
          $post = $this->input->post(); 
          $oldpass = trim($post['oldpass']);
          $password = trim($post['password']);
          $cpassword = trim($post['cpassword']);

          if(!$oldpass){
          $this->session->set_flashdata('success', 'Old password is blank!!');
          redirect(ADMINURL.'bp/reset_pass');
          }else if(!$password){
          $this->session->set_flashdata('success', 'Please enter password!!');
          redirect(ADMINURL.'bp/reset_pass');
          }else if(!$cpassword){
          $this->session->set_flashdata('success', 'Please enter confirm password!!');
          redirect(ADMINURL.'bp/reset_pass');
          }else if( $cpassword != $password ){
          $this->session->set_flashdata('success', 'Password and confirm password not matched!!');
          redirect(ADMINURL.'bp/reset_pass');
          }


          $userid = getloggeduserdata('id'); 
          $item = $this->c_model->countitem('users',array('id'=>$userid,'password'=>$oldpass ) );

          if(($cpassword == $password ) && !empty($userid) && ($item ==1 )){
            $update['password'] = $password;
            $update['en_password'] = md5($password);
            $where['id'] = $userid;

           $status  = $this->c_model->saveupdate('users', $update ,null, $where ); 
            if($status){  
            $this->session->set_flashdata('success', 'your password has been changed successfully!!');
            redirect( 'https://bp.mydigicash.in/');
            }else{
              $this->session->set_flashdata('error', 'Password/ Confirm password did not match!!!');
              redirect(ADMINURL.'bp/reset_pass'); 
            }
           
          }else{
           $this->session->set_flashdata('error', 'Password/ Confirm password did not match!!!');
            redirect(ADMINURL.'bp/reset_pass');     
          }
        }
}