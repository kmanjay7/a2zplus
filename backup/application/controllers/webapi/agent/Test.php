<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends CI_Controller{
	
	  function __construct() {
         parent::__construct();
           
      }


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

}
?>