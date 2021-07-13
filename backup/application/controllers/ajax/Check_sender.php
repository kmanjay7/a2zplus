<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Check_sender extends CI_Controller{
	public function __construct(){
        parent::__construct();
        $this->load->library('session'); 
        agsession_check();  
        }


    public function index(){ 
		 
		  $post = $this->input->post()?$this->input->post():$this->input->get();
      $buffer['status'] = false;
          $url = APIURL.('webapi/dmtusers/Check_sender');
          $postdata['mobile'] = $post['mobile'];  
          $buffer = curlApis($url,'POST',$postdata);
          
          $buffer['gotourl'] = ''; 
          if(isset($buffer['status']) && $buffer['status'] ){
              if($buffer['data']['otpverify'] =='yes'){
              $buffer['gotourl'] = base_url($post['folder'].'/Kyc_nonkyc_users');
              $this->session->set_userdata('dmtusers',$buffer);
              }
            
          } 

          echo json_encode($buffer); 
	}


  public function register(){  
   
      $post = $this->input->post()?$this->input->post():$this->input->get();
      $buffer['status'] = false;
      $add_by = getloggeduserdata('id');;
      $name = $post['name'];
      $mobile = $post['mobile'];
      $pincode = $post['pincode'];
      $bankname = '';
      $ac_number = '';
      $ifsc_code = '';
      $buffer = array();
      if( $add_by && $name && $mobile && $pincode ){
          $url = APIURL.('webapi/dmtusers/Register_sender');
          $postdata['add_by'] = $add_by;
          $postdata['name'] = $name;
          $postdata['mobile'] = $mobile;
          $postdata['pincode'] = $pincode; 
          $postdata['bankname'] = $bankname; 
          $postdata['ac_number'] = $ac_number; 
          $postdata['ifsc_code'] = $ifsc_code;  
          $buffer = curlApis($url,'POST',$postdata);
      }else{ 
        $buffer['status'] = FALSE;
        $buffer['message'] = 'Please fill all required fields!'; 
      }
           
          echo json_encode($buffer); 
  }


public function resendmatchotp(){ 
     
          $post = $this->input->post()?$this->input->post():$this->input->get();
          $buffer['status'] = false;
          $url = APIURL.('webapi/dmtusers/Send_match_sender_otp');
          $postdata['mobile'] = $post['mobile'];
          $postdata['action'] = $post['action'];
          $postdata['otp'] = $post['otp'];  
          $buffer['gotourl'] = '';
          $buffer = curlApis($url,'POST',$postdata);
          if($post['action']=='match' && $buffer['status']){
            $buffer['gotourl'] = base_url($post['folder'].'/Kyc_nonkyc_users');
            $this->session->set_userdata('dmtusers',$buffer);
          }  
          echo json_encode($buffer); 
  }


} ?>