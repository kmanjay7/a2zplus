<?php defined("BASEPATH") OR exit("No Direct Access Allowed!");

class Web_hook_manimulti extends CI_Controller{
  var $ipaddress;
	public function __construct(){
		parent::__construct();
    $this->load->model('Creditdebit_model','cr_model');  
    $this->ipaddress = '109.203.112.41';
		}
		
	
				
public function mmkh(){

    $ip = $this->get_IP_address();
    $ip = $ip ? $ip :1;
    $post = $this->input->post()?$this->input->post():$this->input->get();

    //$table = 'dt_rech_history';
    $response = [];

		//header("Pragma: no-cache");
		//header("Cache-Control: no-cache");
		//header("Expires: 0");

if($post){
    $save['testdata'] = json_encode($post, JSON_UNESCAPED_SLASHES);
    $save['testip'] = $ip;
    $save['indate'] = date('Y-m-d H:i:s');
   echo $this->c_model->saveupdate('dt_test',$save);
}else{ echo 'bad';}
    



//header("Content-Type:application/json");
//echo json_encode( $response ); 

}



private function pushlog($odr,$type,$io,$payload){
  $payload = json_encode($payload, JSON_UNESCAPED_SLASHES);
  $insert = [];
  $insert['odr'] = $odr;
  $insert['type'] = $type;
  $insert['io'] = $io;
  $insert['req_res'] = $payload;
  $insert['timeon'] = date('Y-m-d H:i:s'); 
  return $this->c_model->saveupdate('dt_pushlog',$insert );
}



private function wallet($arr){ 

      $userid = $arr['userid'];
      $usertype = $arr['usertype'];
      $transctionid = $arr['transctionid'];
      $referenceid = $arr['orderid'];
      $credit_debit = $arr['credit_debit'];
      $amount = $arr['amount'];
      $subject = $arr['subject'];
      $status = $arr['status'];
      $paymode = $arr['paymode'];
      $remark = $arr['remark']; 

      if(!$userid){ return false; exit;}
      elseif(!$usertype){ return false; exit;}
      elseif(!$referenceid){ return false; exit;}
      elseif(!$credit_debit){ return false; exit;}
      elseif(!$amount){ return false; exit;}
      elseif(!$paymode){ return false; exit;}


      $table = 'dt_wallet';
   
    /* wallet deduction/addition start*/
    $inkey = null;
  $invalue = null;

  $checklast['userid'] = $userid;
  $checklast['usertype'] = $usertype; 
  $inkey = 'credit_debit';
  $invalue = 'credit,debit';
    

  $lastentry = $this->cr_model->getSingle_cr( $table,$checklast, 'finalamount, beforeamount','id DESC' , 1, $inkey, $invalue );
    $beforeamount = $lastentry['finalamount'];
    $totalamount = $lastentry['finalamount'];


      $execute = false;
      $newtotalamount = false;
      if( $credit_debit == 'credit'){
      $newtotalamount = $totalamount + $amount ; 
      $execute = true;
      }else if( ($credit_debit == 'debit' ) && ($totalamount >= $amount ) &&  $totalamount > 0 ) { 
      $newtotalamount = $totalamount - $amount ; 
      $execute = ($totalamount >= $amount) ? true : false;
      }
 
    /* wallet deduction/addition end*/  
       
      $post['id'] =  NULL ;
      $post['userid'] = $userid ;
      $post['usertype'] = $usertype ;
      $post['paymode'] = $paymode ;
      $post['transctionid'] = $transctionid ;
      $post['credit_debit'] = $credit_debit ; 
      $post['add_date'] = date('Y-m-d H:i:s') ;
      $post['remark'] = $remark ;
      $post['status'] = $status ;
      $post['referenceid'] = $referenceid ;
      $post['amount'] = (float)$amount ;
      $post['finalamount'] = $newtotalamount ;
      $post['subject'] = $subject ;
      $post['addby'] = $userid ;
      $post['beforeamount'] = $beforeamount ;

      $post['odr'] = (float)$amount ; 
      $post['flag'] = 1; 
  
      $post = $this->security->xss_clean($post);
      $update = false;
      if( $amount && $userid && $credit_debit && $execute ){
       $update = $this->cr_model->saveupdate_cr( $table, $post ); 
      }
   return  $update;
}


public function get_IP_address(){
    foreach (array('HTTP_CLIENT_IP',
                   'HTTP_X_FORWARDED_FOR',
                   'HTTP_X_FORWARDED',
                   'HTTP_X_CLUSTER_CLIENT_IP',
                   'HTTP_FORWARDED_FOR',
                   'HTTP_FORWARDED',
                   'REMOTE_ADDR') as $key){
        if (array_key_exists($key, $_SERVER) === true){
            foreach (explode(',', $_SERVER[$key]) as $IPaddress){
                $IPaddress = trim($IPaddress); // Just to be safe

                if (filter_var($IPaddress,
                               FILTER_VALIDATE_IP,
                               FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)
                    !== false) {

                    return $IPaddress;
                }
            }
        }
    }
}



		
}
?>