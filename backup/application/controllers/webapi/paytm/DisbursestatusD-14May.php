<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Disbursestatus extends CI_Controller{
	
	function __construct() {
         parent::__construct(); 
         $this->load->model('Disbursal_model','dbl_model'); 
    }



	function index(){

			header("Pragma: no-cache");
			header("Cache-Control: no-cache");
			header("Expires: 0");

		    // following files need to be included
			require_once(APPPATH . "/third_party/PaytmKit/lib/config_paytm.php");
			require_once(APPPATH . "/third_party/PaytmKit/lib/encdec_paytm.php");

			$request = requestJson('dts');
			$response = array();

if( ($_SERVER['REQUEST_METHOD'] == 'POST') ){

$orderId = isset($request['orderId']) ? trim($request['orderId']) : false;
$getagentid = $this->dbl_model->getSingle_dbs('dt_dmtlog', array('orderid'=>$orderId) ,' * ' );
$buffer['status'] = $getagentid['status'];

$id = $getagentid['id'];
if(!$id){ exit;}
$final_status = $getagentid['final_status'];
$refundorderid = 'RFND'.filter_var($orderId,FILTER_SANITIZE_NUMBER_INT );
 


/*return REQUEST AMOUNT IN WALLET in database start script */
if( $orderId ){
$paytmParams = array(); 
$paytmParams["orderId"] = $orderId;
$post_data = json_encode($paytmParams, JSON_UNESCAPED_SLASHES);
$log = $this->pushlog($orderId,'dmt','I',$post_data);/*log entry*/

$checksum = getChecksumFromString($post_data, PAYTM_MERCHANT_KEY ); 
$x_mid = PAYTM_MERCHANT_MID;
$x_checksum = $checksum;

$url = PAYTM_FUND_CHECKSUM_URL; 
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "x-mid: " . $x_mid, "x-checksum: " . $x_checksum)); 
//curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10 );
curl_setopt($ch, CURLOPT_TIMEOUT, 30 );
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
$jsonresponse = curl_exec($ch);
$log = $this->pushlog($orderId,'dmt','O',$jsonresponse);/*log entry*/

$buffer = $jsonresponse ? json_decode( $jsonresponse, true ) : array();

/*check if any response not comes*/
if( empty($buffer) ){
		$response['status'] = true;
		$response['message'] ='Some Error Occured!';
		header('Content-Type:application/json');
		echo json_encode($response);
		exit;
}

if($final_status == 'yes'){
		$response['status'] = true;
		$response['data'] = $buffer ;
		$response['message'] = isset($buffer['statusMessage'])?$buffer['statusMessage']:'Success!';
		header('Content-Type:application/json');
		echo json_encode($response);
		exit;
}



/*save response data in database start script */
if(isset($buffer['status']) && ($buffer['status']=='SUCCESS') ){ 
   $save['banktxnid'] = $buffer['result']['paytmOrderId'];
   $save['status'] = 'SUCCESS';
   $save['transctionid'] = $buffer['result']['mid'];
   $save['respmsg'] = $buffer['statusMessage'];
   $save['ptm_comision'] = $buffer['result']['commissionAmount'];
   $save['ptm_tax'] = $buffer['result']['tax'];
   $save['ptm_rrn'] = $buffer['result']['rrn'];
   $save['status_update'] = date('Y-m-d H:i:s'); 
   $save['final_status'] = 'no'; 
   $this->dbl_model->saveupdate_dbs('dt_dmtlog',$save,null,array('id'=>$id));
}
/*save response data in database end script */



/*save response data in database start script */
/*if(isset($buffer['status']) && ( ($buffer['status']=='FAILURE') || ($buffer['status']=='PENDING' && $buffer['statusCode']=='DE_102' ) ) ){*/
else if(isset($buffer['status']) && (trim($buffer['status'])=='FAILURE') ){
   $save['banktxnid'] = isset($buffer['result']['paytmOrderId'])?$buffer['result']['paytmOrderId']:'';
   $save['status'] = 'FAILURE';
   $save['transctionid'] = isset($buffer['result']['mid'])?$buffer['result']['mid']:'';
   $save['respmsg'] = isset($buffer['statusMessage'])?$buffer['statusMessage']:'';
   $save['ptm_comision'] = isset($buffer['result']['commissionAmount'])?$buffer['result']['commissionAmount']:'';
   $save['ptm_tax'] = isset($buffer['result']['tax'])?$buffer['result']['tax']:'';
   $save['ptm_rrn'] = isset($buffer['result']['rrn'])?$buffer['result']['rrn']:'';
   $save['status_update'] = date('Y-m-d H:i:s');
   $save['final_status'] = 'yes';
   $updatetbl = $this->dbl_model->saveupdate_dbs('dt_dmtlog',$save,null,array('id'=>$id));
 if(!$updatetbl){ exit; }

$check_orderid = isset($buffer['result']['orderId'])?$buffer['result']['orderId']:false;
/*check paytm error code */
$statusCode = $buffer['statusCode']?$buffer['statusCode']:false;


   /*if( $statusCode == 'DE_039' ){  
   	    $response['status'] = true;
		$response['data'] = $buffer ;
		$response['message'] = isset($buffer['statusMessage'])?$buffer['statusMessage']:'Success!';
		header('Content-Type:application/json');
		echo json_encode($response);
		exit;
   }else if( !$check_orderid ){  
   	    $response['status'] = true;
		$response['data'] = $buffer ;
		$response['message'] = isset($buffer['statusMessage'])?$buffer['statusMessage']:'Success!';
		header('Content-Type:application/json');
		echo json_encode($response);
		exit;
   }else if( $check_orderid != $orderId ){  
   	    $response['status'] = true;
		$response['data'] = $buffer ;
		$response['message'] = isset($buffer['statusMessage'])?$buffer['statusMessage']:'Success!';
		header('Content-Type:application/json');
		echo json_encode($response);
		exit;
   }
*/

   /*return amount to agent wallet*/ 
   $getaddby = $this->dbl_model->getSingle_dbs('users',array('id'=>$getagentid['userid']),'uniqueid,user_type');

   /* check wallet entry */
	$ch_wt['userid'] = $getagentid['userid'];
	$ch_wt['usertype'] = 'AGENT';
	$ch_wt['referenceid'] = $orderId;
	$ch_wt['subject'] = 'dmt_1';
	$ch_wt['credit_debit'] = 'debit';
	$checkentry = $this->dbl_model->countitem_dbs('dt_wallet',$ch_wt );
	$check_deduct_arr = $this->dbl_model->getSingle_dbs('dt_wallet',$ch_wt,'amount,id' ); 
	$refundamount = $check_deduct_arr['amount'];


	$ch_wtt['userid'] = $getagentid['userid'];
	$ch_wtt['usertype'] = 'AGENT';
	$ch_wtt['referenceid'] = $refundorderid;
	$ch_wtt['subject'] = 'dmt_1_refund';
	$ch_wtt['transctionid'] = 'NA';
	$ch_wtt['credit_debit'] = 'credit';
	$checkrefundentry = $this->dbl_model->countitem_dbs('dt_wallet',$ch_wtt );
   

				$wtsave['userid'] = $getagentid['userid'];
				$wtsave['usertype'] = $getagentid['usertype'];
				$wtsave['uniqueid'] = trim($getaddby['uniqueid']);  
				$wtsave['paymode'] = 'wallet';
				$wtsave['transctionid'] = 'NA';
				$wtsave['credit_debit'] = 'credit';
				$wtsave['upiid'] = '';
				$wtsave['bankname'] = ''; 
				$wtsave['remark'] = 'Refund for Orderid: '.$orderId;
				$wtsave['status'] = 'success'; 
				$wtsave['amount'] = $refundamount;
				$wtsave['subject'] = 'dmt_1_refund';
				$wtsave['addby'] = $getagentid['userid'];
				$wtsave['orderid'] = $refundorderid;
				$wtsave['odr'] = $refundamount;
				$wtsave['surch'] = '';
				$wtsave['comi'] = '';
				$wtsave['tds'] = '';
				$wtsave['flag'] = 1;

				$walleturl = ADMINURL.('webapi/wallet/Creditdebit');
				$waltwhere['dts'] = $wtsave;  
				$headerwt = array('auth: Access-Token='.WALLETOKEN );
				if( ($checkentry == 1 ) && !$checkrefundentry && ($refundamount > 0 )){
				$upwt = curlApis($walleturl,'POST', $waltwhere,$headerwt ); 
				}  
				 
   /*return amount to agent wallet*/
}
/*save response data in database end script */







		if(isset($buffer['status']) && ($buffer['status']) ){
		$response['status'] = true;
		$response['data'] = $buffer ;
		$response['message'] = isset($buffer['statusMessage'])?$buffer['statusMessage']:'Success!';
		}else{
		$response['status'] = false;
		$response['message'] = isset($buffer['statusMessage'])?$buffer['statusMessage']:'Paytm error!';
		}


}else{
	$response['status'] = false;
	$response['message'] = 'Order Id is blank!';
}



}else{
	$response['status'] = false;
	$response['message'] = 'Bad Request!';
}

header('Content-Type:application/json');
echo json_encode($response);
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

	
}
?>