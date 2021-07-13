<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Otp extends CI_Controller{
	
	public function __construct(){
		parent::__construct();
        $this->load->library('session');   
		}
	
	public function index(){ 
		$data = array();
		$data['title'] = 'Otp Page';
		$this->load->view('otp',$data);
	}


    public function check(){

            $table = 'users';
            $uniqueid = $this->session->userdata('uniqueid');
            $pass   = $this->session->userdata('password');
            $user_type   = $this->session->userdata('user_type');
            $post   = $this->input->post();             
            $ckeck['otp']         = trim($post['otp']);
            $ckeck['uniqueid']    = $uniqueid;
            $ckeck['en_password'] = $pass;
            $ckeck['user_type']   = $user_type;
            $ckeck['otp_time <=']   = date('Y-m-d H:i:s');
            $ckeck['otp_time >=']   = date('Y-m-d H:i:s',strtotime(date('Y-m-d H:i:s').' -4 minutes')); 

            
        if($ckeck['otp']){

           $checkuser = $this->c_model->countitem($table,$ckeck ); 
             
           if( $checkuser == 1 ){
               
            $this->load->helper('cookie');
            $enc_data = "TKBSAQUoiL|".$uniqueid."|548*&M-p_F5"; 
            $ciphering = "AES-128-CTR"; 
            $iv_length = openssl_cipher_iv_length($ciphering); 
            $encryption_iv = '9104407890014621'; 
            $encryption_key = "digiencrypt";
            $encryption = openssl_encrypt($enc_data, $ciphering, 
                        $encryption_key, 0, $encryption_iv);

            $days=15;
            set_cookie("user_login", $encryption, 60*60*24*$days);
            
            
              $keys = '*';
              $query = $this->c_model->getSingle($table,$ckeck,$keys );
              $data = [
                    'id'            => $query['id'],
                    'uniqueid'      => $query['uniqueid'],
                    'user_type'     => $query['user_type'],
                    'parenttype'    => $query['parenttype'],
                    'parentid'      => $query['parentid'],
                    'ownername'     => $query['ownername'],
                    'mobileno'      => $query['mobileno'],
                    'alt_mobileno'  => $query['alt_mobileno'],
                    'emailid'       => $query['emailid'],
                    'pancard'       => $query['pancard'],
                    'aadharno'      => $query['aadharno'],
                    'password'      => $query['stateid'],
                    'cityid'        => $query['cityid'],
                    'pincode'       => $query['pincode'],
                    'address'       => $query['address'],
                    'firmname'      => $query['firmname'],
                    'scheme_type'   => $query['scheme_type'],
                    'scheme_amount' => $query['scheme_amount'],
                    'credit_amount' => $query['credit_amount'],
                    'services'      => $query['services'],
                    'capamount'     => $query['capamount'],
                    'balance'       => $query['balance'],
                    'agentids'      => $query['agentids'],
                    'kyc_status'    => $query['kyc_status'],
                    'status'        => $query['status'],
                    'en_password'   => $query['en_password'],
                    'uniquecode'    => $query['uniquecode'],
                    'aeps'          => $query['aeps'],
                    'outlet_id'     => $query['outlet_id'],
                    'aepspan_no'    => $query['aepspan_no'],
                    'aeps_status'   => $query['aeps_status'],
                    'fromdate'      => $query['fromdate'],
                    'todate'        => $query['todate'],
                    'is_ok'         => 'yes'
                ];
                $this->session->set_userdata($data);
                /*if($query['user_type'] == 'BP'){
                   redirect(ADMINURL.'bp/dashboard'); 
                }else*/ if ($query['user_type'] == 'MD') {
                   redirect(ADMINURL.'md/dashboard');
                }elseif ($query['user_type'] == 'AD') {
                   redirect(ADMINURL.'ad/dashboard'); 
                }elseif ($query['user_type'] == 'AGENT') {
                  redirect(ADMINURL.'ag/dashboard');  
                }
           }else{
                $this->session->set_flashdata('error', 'OTP did not match or Expired!');
                redirect(ADMINURL.'otp');
               }
        }else{
          $this->session->set_flashdata('error', 'Please fill the 4 digit OTP!');
            redirect(ADMINURL.'otp');
        }
            
    }
        
       public function resendotp(){
            $response = array();
            $sendotpat = date('Y-m-d H:i:s');
            $mobile = $this->session->userdata('checkmobile');
            $uniqueid = $this->session->userdata('uniqueid');
            $ownername = $this->session->userdata('ownername');
            $otp = generateOtp();
            $update  = $this->c_model->saveupdate('users',array('otp'=>$otp,'otp_time'=>date('Y-m-d H:i:s')),null, array('uniqueid'=>$uniqueid),null );
            //$smsarray = array('mobile'=> $mobile,'otp'=>$otp);
            //$sendsms =   userregistrationotp($smsarray);

    /*send OTP msg to ALL USER start*/
    if( $mobile ){
    $msgbody = 'Dear '.strtoupper($ownername).', Your OTP to login your DigiCash India Account is - '.$otp.'. Never share your Login Password or OTP with anyone.
Regards,
DigiCash India.';
    $sendsms = simplesms($mobile,$msgbody);
    }
    /*send OTP msg to ALL USER END*/


            echo json_encode($response);
        }
        
}