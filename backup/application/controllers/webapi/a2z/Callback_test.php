<?php
defined("BASEPATH") OR exit("No Direct Access Allowed!");

class Callback_test extends CI_Controller{
    var $ipaddress;
    public function __construct(){
        parent::__construct();
        $this->load->library('bbps_recharge');
        $this->load->model('Creditdebit_model','cr_model');  
        $this->ipaddress = '182.18.157.156';
        $this->load->library('curl');
    }
    

public function index(){

    $ip = $this->get_IP_address();
    
    $table = 'dt_rech_history';

    if(empty($_POST))
    {
        echo 'Invalid Data';exit;
    }
    
    // if($ip!=$this->ipaddress)
    // {
    //     echo 'Invalid Request';exit;
    // }

    
    $request=$_POST;

    $log = $this->pushlog($request['clientId'],'a2zcallback','O',$_POST);
    
    
    $where['reqid'] = $request['clientId']; 
    
    $keys = 'id,serviceid,user_id,final_status,status';
    
    $orderinfo = $this->c_model->getSingle($table, $where, $keys/*,null,null,$inkey,$invalue */);
    
    if(empty($orderinfo)){
      echo 'No Record Exist!.'; exit;
    }

    $final_status = $orderinfo['final_status'];
    $id = $orderinfo['id'];
    $serviceid = (int)$orderinfo['serviceid'];
    
    if( $final_status == 'yes' ){
        echo 'Already Refunded!.';exit;
    } 

    if($request['statusId'] && $request['statusDescription'] && $request['txnId'] && $request['clientId'])
    {

        $statusId=$request['statusId'];
        $statusDescription=$request['statusDescription'];
        $txnId=$request['txnId'];
        $message=$request['message'];
        $clientId=$request['clientId'];

        $remark=($message) ? $message : $statusDescription;
        if($request['statusId']==1)
        {
            $status='SUCCESS';
        }else if($request['statusId']==21)
        {
            $status='FAILURE';
        }

        if($status)
        {
            $savedt['status']=$status;
            $savedt['remark']=$remark;
            $savedt['a2z_reach_status']=$request['statusId'];
            $savedt['op_transaction_id']=$request['txnId'];
            $savedt['status_update'] = date('Y-m-d H:i:s');
             
            $update = $this->c_model->saveupdate($table,$savedt,null,['reqid'=>$clientId ] );
            if($update)
            {
                
                $newstatus = strtolower($status);
                
                if($status=='FAILURE')
                {
                    $postapiurl = AGENTPANEL.('webapi/recharge/topup_reversal'); 
                    $postonurl['orderid'] = $clientId;  
                    $buffer = curlApis($postapiurl,'POST', $postonurl );
                }
                
                
                echo 'Success!';
            }       
        }else{
            echo 'Invalid Request';exit;
        } 
            
    }else{
        echo 'Invalid Request';exit;
    }

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