<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller{
	
	public function __construct(){
		parent::__construct(); 
    $this->load->library('session'); 
    $this->load->helper('cookie');
		}
	
	public function index(){ 
		$data = array();
		$data['title'] = 'Login Page'; 
		$this->load->view('login',$data);
	}
       

        public function check(){

        $table = 'users';

        $post = $this->input->post();
        $where['uniqueid'] = filter_var( trim($post['userid']),FILTER_SANITIZE_NUMBER_INT );
        $where['en_password'] = md5( trim($post['password']) );
        $where['user_type'] = trim($post['usertype']); 

        /*block numbers check start */
        $blocked = $this->c_model->countitem('dt_blocked',['mobileno'=>$where['uniqueid']] );
        if( $blocked ){
        $this->session->set_flashdata('error', 'Your account has been blocked by Administrator!');
        redirect(ADMINURL.'login');
        exit;
        }
        /*block numbers check end */

if( ( strlen($where['uniqueid'])==10) && $where['en_password'] ){
    $checkuser = $this->c_model->countitem($table,$where ); 
    if( $checkuser == 1 ){

         // delete_cookie("user_login");
        
          $encryption=get_cookie("user_login");
          $decryption_iv = '9104407890014621'; 
          $decryption_key = "digiencrypt"; 
          $ciphering = "AES-128-CTR"; 
          $decryption=openssl_decrypt ($encryption, $ciphering,  
                  $decryption_key, 0, $decryption_iv);
          $user_login_arr = explode("|", $decryption);


          $query = $this->c_model->getSingle($table,["uniqueid"=>$where['uniqueid'], "user_type"=>$where['user_type']],'*');

            if($query['id'] && $query['kyc_status']!='yes' && $where['user_type']!='AGENT')
            {
                $postwhereptm['mobileno']=$query['uniqueid'];
                $postwhereptm['usertype']=$where['user_type'];
                $postwhereptm['usertableid']=$query['id'];
                $posturlptm=ADMINAPIURL.'Update_kyc_from_parent/index';
                $buffer = curlApis($posturlptm,'POST',$postwhereptm);
               
            } 
    
          if(count($user_login_arr)==3 && $user_login_arr[1]==$where['uniqueid']){
            $keys = '*';


            $query = $this->c_model->getSingle($table,["uniqueid"=>$where['uniqueid'], "user_type"=>$where['user_type']],$keys);

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
              
             
              /*if($query['user_type'] == 'BP'){
                 redirect(ADMINURL.'bp/dashboard'); 
              }else*/ if ($query['user_type'] == 'MD') {
                 $this->session->set_userdata($data);
                 redirect(ADMINURL.'md/dashboard');
              }elseif ($query['user_type'] == 'AD') {
                 $this->session->set_userdata($data);
                 redirect(ADMINURL.'ad/dashboard'); 
              }elseif ($query['user_type'] == 'AGENT') {
                 $this->session->set_userdata($data);
                 redirect(ADMINURL.'ag/dashboard');  
              }
          }
              
              
       $keys = 'mobileno,en_password,uniqueid,id,user_type,ownername';
       $row = $this->c_model->getSingle($table, $where, $keys );
       $otp = generateOtp();
       $update  = $this->c_model->saveupdate($table,array('otp'=>$otp,'otp_time'=>date('Y-m-d H:i:s')),null, array('id'=>$row['id']),null );
        // $smsarray = array('mobile'=> $row['mobileno'],'otp'=>$otp);
       //$sendsms = userregistrationotp($smsarray);

    /*send OTP msg to ALL USER start*/
    if( $row['mobileno'] ){
    $msgbody = 'Dear '.strtoupper($row['ownername']).', Your OTP to login your DigiCash India Account is - '.$otp.'. Never share your Login Password or OTP with anyone.
Regards,
DigiCash India.';
    $sendsms = simplesms($row['mobileno'],$msgbody);
    }
    /*send OTP msg to ALL USER END*/

         
         $store['checkmobile'] = $row['mobileno'];
         $store['password'] = $row['en_password'];
         $store['uniqueid'] = $row['uniqueid'];
         $store['user_type'] = $row['user_type'];
         $store['ownername'] = $row['ownername'];  
         $this->session->set_userdata($store);
         redirect(ADMINURL.'otp'); 


    }else{
      $this->session->set_flashdata('error', 'username/password did not match!');
        redirect(ADMINURL.'login');
    }


}else{
  $this->session->set_flashdata('error', 'kindly fill all mandatory fields!');
    redirect(ADMINURL.'login');
}



}


}
?>