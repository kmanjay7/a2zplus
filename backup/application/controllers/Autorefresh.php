<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Autorefresh extends CI_Controller{
var $redirect; 
var $key;
	public function __construct(){
		parent::__construct();
        $this->load->library('session'); 
        $this->key = 'kJ(8&Uy_oir^%7_hjyi';   
        $this->redirect = 'https://bp.mydigicash.in';
		}
	
	 

    public function check(){

      $table = 'dt_users';
           
               $token = $this->input->post('token'); 
               $token = $this->decrypt($this->key,$token);

               $id = explodeme($token,'|',0 );
               $uniqueid = explodeme($token,'|',1 );
               $dbtoken = explodeme($token,'|',2 );

  if( !$id || !$uniqueid || !$dbtoken ){
  redirect( $this->redirect );
  exit;
  }
        
          
            $ckeck['id'] = $id;
            $ckeck['uniqueid'] = $uniqueid;
            $ckeck['token'] = $dbtoken; 
        
           $checkuser = $this->c_model->countitem($table,$ckeck ); 
             
           if( $checkuser == 1 ){
              $keys = ' * ';
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
                    'fromdate'      => $query['fromdate'],
                    'todate'        => $query['todate'],
                    'is_ok'         => 'yes'
                ];
                $this->session->set_userdata($data);
                if($query['user_type'] == 'BP'){
                   redirect(ADMINURL.'bp/dashboard'); 
                }

                redirect( $this->redirect );

           }else{ 
                redirect( $this->redirect );
               }
         
            
    }
      
    public  function decrypt($key, $garble) {
    list($encrypted_data, $iv) = explode('::', base64_decode($garble), 2);
    return openssl_decrypt($encrypted_data, 'aes-256-cbc', $key, 0, $iv);
}
        
}