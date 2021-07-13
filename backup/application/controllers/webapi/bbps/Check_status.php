<?php
defined("BASEPATH") OR exit("No Direct Access Allowed!");

class Check_status extends CI_Controller{
    var $ipaddress;
    public function __construct(){
        parent::__construct();
        $this->load->library('bbps_recharge');
        $this->load->model('Creditdebit_model','cr_model');  
        $this->ipaddress = '208.109.12.98';
    }
    

public function index(){

    $ip = $this->get_IP_address();
    $post = requestJson();

    $table = 'dt_rech_history';
    $response = [];


        header("Pragma: no-cache");
        header("Cache-Control: no-cache");
        header("Expires: 0");

if( /*($ip == $this->ipaddress ) &&*/ !empty($post) && ($_SERVER['REQUEST_METHOD'] == 'POST') ){

$Merchantrefno = isset($post['reqid'])?$post['reqid']:false;
$arr['ref_id'] = $Merchantrefno;
$log = $this->pushlog($Merchantrefno,'rech','I',$arr);/*log entry*/
$bbps_response = $this->bbps_recharge->status($arr);
$log = $this->pushlog($Merchantrefno,'rech','O',$bbps_response);/*log entry*/


/************* start code to maintaine order ************************/ 


$Status = isset($bbps_response['status'])?$bbps_response['status']:false;
$TransactionID = isset($bbps_response['bbpsRefId'])?$bbps_response['bbpsRefId']:false;
$apiorderID = '';
$message = isset($bbps_response['message'])?$bbps_response['message']:false;


if(empty($Merchantrefno) || empty($Status) ){
  echo 'error';
exit;
}



if($Merchantrefno && $Status){ 
   

    $where['reqid'] = $Merchantrefno; 
    $keys = 'id,serviceid,user_id,final_status,status';
    /*$inkey = 'status';
    $invalue = 'PROCESSED,PENDING';*/
    $orderinfo = $this->c_model->getSingle($table, $where, $keys/*,null,null,$inkey,$invalue */);

    if(empty($orderinfo)){
      $response['status'] = false;
      $response['message'] = 'already done!';
      header("Content-Type: application/json");
      echo json_encode( $response );
      exit;
    }

    $final_status = $orderinfo['final_status'];
    $id = $orderinfo['id'];
    $serviceid = (int)$orderinfo['serviceid'];



                if( $final_status == 'yes' ){
                $response['status'] = true; 
                $response['data'] = $bbps_response; 
                $response['message'] = 'success!';
                header("Content-Type: application/json");
                echo json_encode( $response );
                exit;
                } 


                  $save_dt['op_transaction_id'] = $TransactionID;
                  $save_dt['apirefid'] = $apiorderID;
                  $save_dt['status_update'] = date('Y-m-d H:i:s');
                  $save_dt['status'] = $Status; 

                if($Status == 'SUCCESS'){
                  $save_dt['remark'] = $message?$message:'Your transaction has been SUCCESSED.';
                  $update = $this->c_model->saveupdate($table,$save_dt,null,['id'=>$id ] );
                }else if($Status == 'FAILURE'){
                  $save_dt['remark'] = $message?$message:'Your transaction has been FAILED';
                  $save_dt['final_status'] = 'yes';
                }


                 $newstatus = strtolower($Status); 

        /*********prepare query to refund amount **************/
        if( $newstatus == 'failure' ){
        
        $refund_orderid = 'RFND'.filter_var($Merchantrefno,FILTER_SANITIZE_NUMBER_INT ); 

         
       /* $us_where['id'] = $orderinfo['user_id'];  
        $userdata = $this->c_model->getSingle('dt_users',$us_where,'id,uniqueid,user_type');
       */


        $countwt = 0;
        $rfnd_amount = 0;
        $reversalAmount = 0;
        $invalue = $Merchantrefno.','.$refund_orderid;
        $wtkeys = 'credit_debit,amount';
        $wt_record = $this->c_model->getfilter('dt_wallet',['userid'=> $orderinfo['user_id'],'usertype'=>'AGENT'],null,null, null, null, null, null, null, null,'get','referenceid',$invalue, $wtkeys );


        if(!empty($wt_record)){
          foreach ($wt_record as $key => $value) {
              if($value['credit_debit']=='debit'){
              $countwt = 1; 
              $reversalAmount = $value['amount'];
              }
              if($value['credit_debit']=='credit'){
              $rfnd_amount = 1;
              }
          }
        }



        if($serviceid == 5){
          $servicename = 'Mobile Recharge';
          $servicename2 = 'mob_rech';
        }else if($serviceid == 3){
          $servicename = 'DTH Recharge';
          $servicename2 = 'dth_rech';
        }

        /* check wallet for this transaction start */ 
        $wtsave['userid'] = $orderinfo['user_id'];
        $wtsave['usertype'] = 'AGENT';
        $wtsave['uniqueid'] = '';/*$userdata['uniqueid'];*/
        $wtsave['paymode'] = 'wallet';
        $wtsave['transctionid'] = 'NA';//$Merchantrefno;
        $wtsave['credit_debit'] = 'credit';
        $wtsave['upiid'] = NULL;
        $wtsave['bankname'] = NULL; 
        $wtsave['remark'] = 'Refund for order ID: '.$Merchantrefno;
        $wtsave['status'] = 'success'; 
        $wtsave['amount'] = (float)$reversalAmount;
        $wtsave['subject'] = $servicename2.'_refund';
        $wtsave['addby'] = $orderinfo['user_id']; 
        $wtsave['orderid'] = $refund_orderid; 
        $wtsave['odr'] = (float)$reversalAmount; 
        $wtsave['surch'] = '0';
        $wtsave['comi'] = '0';
        $wtsave['tds'] = '0'; 
        $wtsave['flag'] = 1;

        if( ($rfnd_amount == 0 ) && ($countwt == 1) && ($reversalAmount > 0) ){
            $upwt =  $this->wallet($wtsave);
            if($upwt){
              $update = $this->c_model->saveupdate($table,$save_dt,null,['id'=>$id ] );

            }
        }else if( ($rfnd_amount == 1 ) && ($countwt == 1) && ($reversalAmount > 0) ){
              $update = $this->c_model->saveupdate($table,$save_dt,null,['id'=>$id ] );
        }
        

        }
        /*********prepare query to refund amount **************/



$response['status'] = true;
$response['data'] = $bbps_response;
$response['message'] = 'Success';



}      
/************* end code to maintaine order ************************/ 
}else{
 $response['status'] = false;
 $response['message'] = 'Bad Request';
}


header("Content-Type:application/json");
echo json_encode( $response ); 

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