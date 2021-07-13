<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Paytm_gateway_status extends CI_Controller{

    var $folder;
    var $apiid;
    var $serviceid;
     
    public function __construct() {
         parent::__construct();  
               $this->folder = '';
               $this->apiid = 8;
               $this->serviceid = 7;

      }

 


public function resp(){


 header("Pragma: no-cache");
 header("Cache-Control: no-cache");
 header("Expires: 0");

    $response = array();
    $data = array();
    $request = requestJson('dts');


if( ($_SERVER['REQUEST_METHOD'] == 'POST') ){

    $orderid = isset($request['orderid'])?$request['orderid']:false;
    if(!$orderid){ exit; }

   /* prepare to create checksum start */
    $where['orderid'] = $orderid;
    $odrdb = $this->c_model->getSingle('dt_paytmlog',$where,'id,userid,usertype,final_status');
    $oldstatus = $odrdb['final_status'];
  
    // following files need to be included
    require_once(APPPATH . "/third_party/paytmgateway/config_paytm.php");
    require_once(APPPATH . "/third_party/paytmgateway/encdec_paytm.php");


/* initialize an array */
$paytmParams = array();

/* body parameters */
$paytmParams["body"] = array( 
    "mid" => PAYTM_MERCHANT_MID, 
    "orderId" => $orderid,
);
 $log = $this->pushlog($orderid,'adfund','I',json_encode($paytmParams["body"], JSON_UNESCAPED_SLASHES));
$checksum = getChecksumFromString(json_encode($paytmParams["body"], JSON_UNESCAPED_SLASHES), PAYTM_MERCHANT_KEY );
$paytmParams["head"] = array(
    "signature" => $checksum
);

/* prepare JSON string for request */
$post_data = json_encode($paytmParams, JSON_UNESCAPED_SLASHES);
$url = "https://securegw.paytm.in/merchant-status/api/v1/getPaymentStatus";
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));  
$jsonresponse = curl_exec($ch);
curl_close($ch);
$log = $this->pushlog($orderid,'adfund','O',$jsonresponse );
 
$postn = $jsonresponse ? json_decode( $jsonresponse, true ) : array();
$post = isset($postn['body'])?$postn['body']:[];

if(empty($post)){
     $response['status'] = true; 
     $response['message'] = 'No Response From API';
     echo json_encode($response);
     exit;
}

//print_r($post); exit;

$txnstatus = isset($post['resultInfo']['resultStatus'])?$post['resultInfo']['resultStatus']:false;
$txnmsg = isset($post['resultInfo']['resultMsg'])?$post['resultInfo']['resultMsg']:false;
$cardscheme = isset($post['cardScheme']) ? $post['cardScheme'] : false;


/*check is record has been maintained */
 if($oldstatus == 'yes'){
     unset($post['resultInfo']);
     $post['txn_status'] = $txnstatus;
     $post['message'] = $txnmsg;
     $response['status'] = true;
     $response['data'] = $post; 
     $response['message'] = isset($txnmsg) ? $txnmsg : false;
     echo json_encode($response);
     exit;
 }





    $save['transctionid'] = isset($post['txnId'])?$post['txnId']:false;
    $save['amount'] = isset($post['txnAmount'])?$post['txnAmount']:false;
    $save['paymode'] = isset($post['paymentMode'])?$post['paymentMode']:false;
    $save['status_update'] = date('Y-m-d H:i:s');
    $save['status'] = $txnstatus;
    $save['respmsg'] = isset($txnmsg) ? $txnmsg : false;
    $save['gatewayname'] = isset($post['gatewayName'])?$post['gatewayName']:false;
    $save['banktxnid'] = isset($post['bankTxnId'])?$post['bankTxnId']:false;
    $save['bankname'] = isset($post['bankName'])?$post['bankName']:false;
     
    $save['status'] = strpos($save['status'], '_') ? explodeme($save['status'],'_', 1 ) : 'PENDING';

   

    /*get order info*/  
    $usrwhere['id'] = $odrdb['userid'];
    $usrwhere['user_type'] = $odrdb['usertype']; 
    $userdb = $this->c_model->getSingle('dt_users',$usrwhere,'id,uniqueid,user_type,scheme_type ');

    

    /* get surcharge start */
    $surcharge = ($txnstatus == 'TXN_SUCCESS') ? $this->surcharge($userdb['scheme_type'],$userdb['user_type'],$save['paymode'],$save['amount'],$cardscheme ) : 0 ;
    $save['sur_charge'] = 0;
    $save['final_status'] = 'yes';
    if( !empty($surcharge) && !empty($surcharge['surcharge']) ){
             $save['sur_charge'] = $surcharge['surcharge'];
    }
    /* get surcharge end */

    if($save['status']=='PENDING'){
        $save['final_status'] = 'no'; 
    }



    $update = !empty($save['status']) ? $this->c_model->saveupdate('dt_paytmlog',$save, null, $where ) : false;
    if( $update ){
        /* in success status*/

        if( $txnstatus == 'TXN_SUCCESS'){

        $ch_wt['userid'] = $userdb['id'];
        $ch_wt['usertype'] = $userdb['user_type'];
        $ch_wt['referenceid'] = $orderid;
        $ch_wt['subject'] = 'fill_wt_onl';
        $ch_wt['credit_debit'] = 'credit';
        $check_duplicate = $this->c_model->countitem('dt_wallet',$ch_wt ); 

            /* check wallet for this transaction start */   
        $wtsave['userid'] = $userdb['id'];
        $wtsave['usertype'] = $userdb['user_type'];
        $wtsave['uniqueid'] = $userdb['uniqueid'];
        $wtsave['paymode'] = $save['paymode'];
        $wtsave['transctionid'] = 'NA';
        $wtsave['credit_debit'] = 'credit';
        $wtsave['upiid'] = '';
        $wtsave['bankname'] = $save['bankname']; 
        $wtsave['remark'] = 'Wallet Top Up - Online'; 
        $wtsave['status'] = 'success'; 
        $wtsave['amount'] = ( (float)$save['amount'] - (float)$save['sur_charge']); 
        $wtsave['subject'] = 'fill_wt_onl';
        $wtsave['addby'] = $userdb['id'];
        $wtsave['orderid'] = $orderid;

        $wtsave['odr'] = (float)$save['amount'];
        $wtsave['surch'] = (float)$save['sur_charge'];
        $wtsave['comi'] = '0';
        $wtsave['tds'] = '0';
        $wtsave['flag'] = 1;

        $wallet_apiurl = base_url('webapi/wallet/Creditdebit');
        $upwtwhere['dts'] = $wtsave;  
        $header = array('auth: Access-Token='.WALLETOKEN ); 

        if( $check_duplicate == 0 ){
            $buffer = $save['amount'] ? curlApis($wallet_apiurl,'POST', $upwtwhere,$header ):array();
        }
        
        /* check wallet for this transaction end */
                 
                $response['status'] = true; 
                $response['message'] = $save['respmsg']; 

        
        }else if( ($txnstatus == 'TXN_FAILURE') || $txnstatus ) {
            $response['status'] = false; 
            $response['message'] = $save['respmsg']; 
        }else{
            $response['status'] = false; 
            $response['message'] = 'Some error Occured!'; 
        }
  }
    
}else{
        $response['status'] = false; 
        $response['message'] = 'Bad Request!';
}

 header('Content-Type: application/json');
 echo json_encode($response);

 } 



public function surcharge($sch,$usertype,$op,$amount,$cardscheme){
                if(($cardscheme == 'RUPAY') && ($op =='DC') ){
                    $op = 'RUPAY';
                }
                $opc_wh['serviceid'] = $this->serviceid;
                $opc_wh['op_code'] = $op;
                $opc_wh['apiid'] = $this->apiid;
                $op_code_data = $this->c_model->getSingle('operators_code',$opc_wh,'id,operatorid,op_code,apioperatorname'); 
 
                $scheme_type = $sch;
                $serviceid = $this->serviceid;
                $operatorid = $op_code_data['operatorid'];
                $apiid = $this->apiid;

   $output = $this->getUserComission($scheme_type,$serviceid,$operatorid,$apiid,$usertype,$amount);
   return $output;

}


public function getUserComission($scheme_type,$serviceid,$operatorid,$apiid=null,$user_type,$amount){  

             $cmkey = 'commision_percent,commision_fixed,surcharge_percent,surcharge_fixed';

             $cm_where['scheme_type'] = $scheme_type;
             $cm_where['user_type'] = $user_type;
             isset($operatorid)&&$operatorid ?($cm_where['operatorid'] = $operatorid):null;
             isset($apiid)&&$apiid ?($cm_where['apiid'] = $apiid):null;
             $cm_where['serviceid'] = $serviceid;
             $get_comi = $this->c_model->getSingle('dt_set_commission',$cm_where, $cmkey );

              
             $comi_p = $get_comi['commision_percent']; 
             $comi_f = $get_comi['commision_fixed'];
             $such_p = $get_comi['surcharge_percent'];
             $such_f = $get_comi['surcharge_fixed'];

            $comi = false;
            $such = false;

            if( $comi_f > 0){
              $comi = $comi_f;
            }else if( $comi_p > 0){
              $comi = percentage($amount,$comi_p);
            }

            if( $such_f > 0){
              $such = $such_f;
            }else if( $such_p > 0){
              $such = percentage($amount,$such_p);
            } 
             
             $output['commision'] = $comi; 
             $output['surcharge'] = $such;  
             return $output;
}


 public function pushlog($odr,$type,$io,$payload){
    $insert = [];
    $insert['odr'] = $odr;
    $insert['type'] = $type;
    $insert['io'] = $io;
    $insert['req_res'] = $payload;
    $insert['timeon'] = date('Y-m-d H:i:s'); 
    return $this->c_model->saveupdate('dt_pushlog',$insert );
}


}?>