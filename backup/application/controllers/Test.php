<?php defined('BASEPATH') OR exit('No direct script access allowed');

//use thiagoalessio\TesseractOCR\TesseractOCR;


class Test extends CI_Controller{
	
	  function __construct() {
         parent::__construct();
           
      }



function checkmail(){
    $to = 'anil.duplextechnology@gmail.com';
    $message = 'My otp is 4545';
    $subject = 'Test';
   echo  sendmail($to,$subject,$message );
    
}

 function index(){
  $data['title'] = 'fg';
  $data['heading'] = 'fg';
  $data['list'] = [];
  $this->load->view('test',$data);
 
 }

/*

function index_2(){
    $mobile = '6386695694';
    $msg = 'Dear Mohit Singh, Thank you for joining DigiCash India. Login Id- 6386695694t & Password- Jh78^5 to login at mydigicash.in.
Regards,
DigiCash India.';

echo simplesms($mobile,$msg);
    
}    
    
function index_1(){

$data = [];
$data['url'] = '';
$get = $this->input->get();
$data['uid'] = $get['uid'];
if(!$get['uid']){
	echo 'no user';
exit;
}
 
//$data['url'] = 'http://localhost/development/digicash/digicashadmin/commission_check/indexup';
if($get['mode']=='mob'){
$data['url'] = 'http://localhost/development/digicash/digicashadmin/debug/Commission_check_mob/indexup';	
}else if($get['mode']=='dmt'){
$data['url'] = 'http://localhost/development/digicash/digicashadmin/debug/Commission_check_dmt/indexup';	
}




$this->load->view('test',$data );

}


public function checkrc(){

     //$rc_where['status'] = 'PROCESSED';
     $rc_where['apiid'] = '7';
     $rc_where['add_date >='] = date('Y-m-d H:i:s',strtotime(date('Y-m-d H:i:s').' -2 hours'));
     $rc_where['add_date <='] = date('Y-m-d H:i:s',strtotime(date('Y-m-d H:i:s').' -32 seconds')); 
     $rc_get = 'get'; 
     $rc_keys = 'id,user_id,apiid,serviceid,reqid,status,amount,mobileno,field1,field2,apirefid,operatorid,final_status ';
     $rc_limit = 5;
     $rc_start = 0;
     $rech_arr = $this->c_model->getfilter('dt_rech_history',$rc_where, $rc_limit, $rc_start , 'DESC', 'id', null, null, null, null, $rc_get ,null , null , $rc_keys );
     echo '<pre>';
     print_r( $rech_arr);
}


public function compareImage(){
   $this->load->library('CompareImages');
   $obj = new CompareImages();
   $image1 = base_url('uploads/13_6386695694_AGENT.jpeg');
   $image2 = base_url('uploads/WhatsApp_2.jpeg');

 echo  $response = $obj->compare( $image1, $image2 ); 
}


function match_pan_details(){
 */
 /* $text = strtoupper('INCOME TAX DEPARTMENT
D MANIKANDAN
DURAISAMY
16/07/1986
Permanent Account Number
BNZPM2501F
Signature
GOVT. OF INDIA');*/ /*
  $text = strtoupper('• INCOME TAX DEPARTMENT
SURYABHAN YADAV
SHIVDAN YADAV
- •16/10/1972
Permanent Account Number
AHEPY8216D
Signature
GOVT. OF INDIA');
$out = [];
$str =  trim( str_replace('•', '', $text) );
$str =  trim( str_replace('-', '', $str) );

$str = explodeme($str,'SIGNATURE',0 );
$str = trim( str_replace('INCOME TAX DEPARTMENT', '', $str) );
$pan_name = explodeme($str,'PERMANENT ACCOUNT NUMBER',0 ); 
$pan_number_text = explodeme($str,'PERMANENT ACCOUNT NUMBER',1 );

$pattern = "/(\d{2}\/\d{2}\/\d{4})/";
$matches = [];
preg_match_all($pattern, $pan_name, $matches);
$dob = !empty($matches[0][0])?$matches[0][0]:'';
$out['dob'] = $dob;
$user_mixname = explodeme($str,$dob,0 ); 
$out['panname'] = !empty($user_mixname) ? $user_mixname : '';
$out['pannumber'] = !empty($pan_number_text) ? $pan_number_text : '';  
//return $out;
print_r($out);
}

function updatemystatus(){
  $input = $this->input->get();
  $orderid = $input['orderid'];
  if( !empty($orderid) && ($orderid =='AEPS21132125525771') ){
  $up['status'] = 'PENDING';
  $up['final_status'] = '';
  $where['mode'] = 'WAP';
  $where['sys_orderid'] = $orderid;
  /*echo $this->c_model->saveupdate('dt_aeps', $up, null, $where );*/

 /* }






}*/

}
?>