<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Fundtransfer_aeps extends CI_Controller{
	
	  function __construct() {
         parent::__construct();  
         $this->load->model('Fundtransfer_aeps_model','fps_model');
      }


function postparam(){

 header("Pragma: no-cache");
 header("Cache-Control: no-cache");
 header("Expires: 0");

	$response = array();
	$data = array();
	$request = requestJson('dts'); 

        
if( ($_SERVER['REQUEST_METHOD'] == 'POST') ){

/****  check token  start ****/ 
if( checktoken( AEPS_FUND_TXN_TOKEN ) ){  

$loggedin_id = isset($request['loggedin_id']) ? trim($request['loggedin_id']) : false;
$user_type = isset($request['user_type']) ? trim($request['user_type']) : false;
$uniqueid = isset($request['uniqueid']) ? trim($request['uniqueid']) : false;
$scheme_type = isset($request['scheme_type']) ? trim($request['scheme_type']) : false;
$bankid = isset($request['bankid']) ? trim($request['bankid']) : false;
$amount = isset($request['amount']) ? trim($request['amount']) : false;
$amount = (float)$amount;
$paymode = isset($request['paymode']) ? trim($request['paymode']) : false; 
$purpose = isset($request['purpose']) ? trim($request['purpose']) : false;

$debitamount = isset($request['debitamount']) ? trim($request['debitamount']):false; 
$orderid = 'M2B'.date('YmdHis').rand(10,99);


if( !$loggedin_id ){
	$response['status'] = FALSE;
	$response['message'] = 'loggedin Id is blank!';
	header("Content-Type: application/json");
	echo json_encode( $response ); exit;
}if( !$uniqueid ){
	$response['status'] = FALSE;
	$response['message'] = 'Uid is blank!';
	header("Content-Type: application/json");
	echo json_encode( $response ); exit;
}else if( !$user_type ){
	$response['status'] = FALSE;
	$response['message'] = 'User type is blank!';
	header("Content-Type: application/json");
	echo json_encode( $response ); exit;
}else if( !$scheme_type ){
	$response['status'] = FALSE;
	$response['message'] = 'Scheme Type is blank!';
	header("Content-Type: application/json");
	echo json_encode( $response ); exit;
}else if( !$bankid ){
	$response['status'] = FALSE;
	$response['message'] = 'Bank Id is blank!';
	header("Content-Type: application/json");
	echo json_encode( $response ); exit;
}else if( !$paymode ){
	$response['status'] = FALSE;
	$response['message'] = 'Paymode is blank!';
	header("Content-Type: application/json");
	echo json_encode( $response ); exit;
}else if( !$amount ){
	$response['status'] = FALSE;
	$response['message'] = 'Amount is blank!';
	header("Content-Type: application/json");
	echo json_encode( $response ); exit;
}else if( $amount > $debitamount ){
	$response['status'] = FALSE;
	$response['message'] = 'Enter proper Amount!';
	header("Content-Type: application/json");
	echo json_encode( $response ); exit;
}else if( !$purpose ){
	$response['status'] = FALSE;
	$response['message'] = 'Purpose is blank!';
	header("Content-Type: application/json");
	echo json_encode( $response ); exit;
}



			$where['id'] = $loggedin_id;
			$where['user_type'] = $user_type;
			$where['uniqueid'] = $uniqueid;

			$countitem = $this->fps_model->countitem_fnd_aeps('dt_users',$where);

			if( $countitem != 1 ){
				$response['status'] = FALSE;
				$response['message'] = 'User not exists!';
				header("Content-Type: application/json");
				echo json_encode( $response );
				exit;
			} 



/************get bank data*************/
			$bankwh['id'] = $bankid;
			$bankwh['user_id'] = $loggedin_id;
			$bankwh['user_type'] = $user_type;
			$bankkey = 'account_no,ifsccode,status';
			$bankarr = $this->fps_model->getSingle_fnd_aeps('dt_aeps_bank_details',$bankwh,$bankkey );
			$account_no = isset($bankarr['account_no'])?$bankarr['account_no']:false;
			$ifsccode = isset($bankarr['ifsccode'])?$bankarr['ifsccode']:false;
			$acc_status = isset($bankarr['status'])?$bankarr['status']:'no'; 

			if( $acc_status != 'yes' ){
				$response['status'] = FALSE;
				$response['message'] = 'Account Verification is Pending!';
				header("Content-Type: application/json");
				echo json_encode( $response );
				exit;
			}

			if( !$account_no && !$ifsccode ){
				$response['status'] = FALSE;
				$response['message'] = 'Invalid Bank Details!';
				header("Content-Type: application/json");
				echo json_encode( $response );
				exit;
			}

/*check active service status*/
$apistatus = $this->fps_model->getSingle_fnd_aeps('dt_recharge_api',['id'=>10],'status' );
if( $apistatus != 'yes' ){
	$response['status'] = FALSE;
	$response['message'] = 'Service is Temporarily Down!';
	header("Content-Type: application/json");
	echo json_encode( $response );
	exit;
}

 
/*check aeps wallet balance */
			
			$aepswt = $this->get_bal($loggedin_id,$user_type);
			

			if( $aepswt < $debitamount ){
				$response['status'] = FALSE;
				$response['message'] = 'Wallet balance is low for this transaction!';
				header("Content-Type: application/json");
				echo json_encode( $response );
				exit;
			}


/*check transfer surcharge balance */
			$chsurch['amount'] = $amount;
			$chsurch['schemetype'] = $scheme_type;  
			//$chsurchurl = APIURL.('webapi/aeps/check_transfer_range'); 
			//$surch_buffer = curlApis($chsurchurl,'POST',$chsurch );
			$surch_buffer = $this->check_transfer_range($chsurch);
			$surcharge = '0.000'; 
			$operatorid = '';
			$operatorname = ''; 
			if( $surch_buffer['status'] ){ 
			$surcharge = $surch_buffer['dataarray'][0]['surcharge'];
			$operatorid = $surch_buffer['dataarray'][0]['operatorid'];
			$operatorname = $surch_buffer['dataarray'][0]['operatorname'];
			$debitamount = $surch_buffer['debitamount'];
			} 
 


/*prepare data to insert into table */ 
		
		
		$insert['userid'] = $loggedin_id;
		$insert['usertype'] = $user_type;
		$insert['orderid'] = $orderid;
		$insert['bank_id'] = $bankid; 
		$insert['credit_debit'] = 'debit'; 
		$insert['respmsg'] = '';
		$insert['subject'] = 'Money Transfer'; 
		$insert['add_date'] = date('Y-m-d H:i:s');
		$insert['amount'] = $amount;
		$insert['gatewayname'] = ''; 
		$insert['status'] = 'PENDING';
		$insert['mode'] = strtoupper($paymode);
		$insert['apiname'] = 'paytm';
		$insert['sur_charge'] = $surcharge;
		$insert['operatorid'] = $operatorid;
		$insert['operatorname'] = $operatorname; 
		$insert['status_update'] = date('Y-m-d H:i:s');
		$insert['nextupdate'] = date('Y-m-d H:i:s',strtotime(date('Y-m-d H:i:s').' +45 seconds'));
		$insert['noofround'] = 0;

		$insertid = $this->fps_model->saveupdate_fnd_aeps('dt_dmtlog_aeps',$insert,$insert); 
		$orderid = $orderid.$insertid;   
        $insertid ? $this->fps_model->saveupdate_fnd_aeps('dt_dmtlog_aeps',['orderid'=>$orderid],null,array('id'=>$insertid)) : null; 

	
	     require_once(APPPATH . "/third_party/PaytmKit/lib/config_paytm.php");
		 require_once(APPPATH . "/third_party/PaytmKit/lib/encdec_paytm.php");
	        /* initialize an array */
			$paytmParams = array();
			$paytmParams["subwalletGuid"] = SUBWALLET_GUID_AEPS;
			$paytmParams["orderId"] = $orderid ;
			$paytmParams["beneficiaryAccount"] = $account_no;
			$paytmParams["beneficiaryIFSC"] = $ifsccode;
			$paytmParams["amount"] = $amount;

			/* Enter Purpose of transfer, possible values are: SALARY_DISBURSEMENT, REIMBURSEMENT, BONUS, INCENTIVE, OTHERS */
			$paytmParams["purpose"] = $purpose;
			$paytmParams["date"] = date('Y-m-d');
			$paytmParams["comments"] = 'AEPS Cashout';
			$paytmParams["transferMode"] = strtoupper($paymode);

			$post_data = json_encode($paytmParams, JSON_UNESCAPED_SLASHES);
			$checksum = getChecksumFromString($post_data, PAYTM_MERCHANT_KEY );
 
			$x_mid = PAYTM_MERCHANT_MID; 
			$x_checksum = $checksum; 
			$url = PAYTM_FUND_TXN_URL;  

			/*Deduct Amount from aeps wallet */ 
            	$wt['userid'] = $loggedin_id;
				$wt['usertype'] = $user_type;
				$wt['uniqueid'] = $uniqueid;  
				$wt['paymode'] = 'wallet';
				$wt['transctionid'] = $insertid;
				$wt['credit_debit'] = 'debit';
				$wt['upiid'] = $account_no;
				$wt['bankname'] = ''; 
				$wt['remark'] = 'Transfer to bank';
				$wt['status'] = 'success'; 
				$wt['amount'] = (float)($amount + $surcharge);
				$wt['subject'] = 'aeps_tr_bk';
				$wt['addby'] = $loggedin_id; 
				$wt['orderid'] = $orderid; 
				$wt['odr'] = $amount;
				$wt['surch'] = $surcharge;
				$wt['comi'] = '0';
				$wt['tds'] = '0';
				$wt['flag'] = 1;

				$walleturl = ADMINURL.('webapi/wallet/Creditdebit_aeps'); 
				$headerwt = array('auth: Access-Token='.AEPS_FUND_TXN_TOKEN );  
				$waltwhere['dts'] = $wt; 
			    $upwt = curlApis($walleturl,'POST', $waltwhere,$headerwt );
				/****** check Agent user's wallet end***********/

				if(!$upwt['status']){
					$tblup['final_status'] = 'yes';
					$tblup['status'] = 'FAILURE';
				$this->fps_model->saveupdate_fnd_aeps('dt_dmtlog_aeps',$tblup,null,array('id'=>$insertid));
				$response['status'] = FALSE;
				$response['message'] = 'Wallet balance is low for this transaction!';
				header("Content-Type: application/json");
				echo json_encode( $response );
				exit;
				}


$log = $this->pushlog($orderid,'dmtaeps','I',$post_data);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "x-mid: " . $x_mid, "x-checksum: " . $x_checksum)); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
$jsonresponse = curl_exec($ch);
curl_close($ch);
$log = $this->pushlog($orderid,'dmtaeps','O',$jsonresponse);
$buffer['status'] = 'FAILURE';
$buffer = $jsonresponse ? json_decode( $jsonresponse, true ) : array();


if( isset($buffer['status']) && in_array($buffer['status'],['SYSTEM_ERROR']) ){
$buffer['status'] = 'PENDING';
}


if(isset($buffer['status']) && ($buffer['status'] != 'FAILURE') ){

				/*update amount from wallet start */
				$updt['paymode'] = isset($buffer['PAYMENTMODE'])?$buffer['PAYMENTMODE']:'';
				$updt['transctionid'] = isset($buffer['TXNID'])?$buffer['TXNID']:'';
				$updt['bankname'] = isset($buffer['BANKNAME'])?$buffer['BANKNAME']:'';
				$updt['respmsg'] = isset($buffer['RESPMSG'])?$buffer['RESPMSG']:'';
				$updt['gatewayname'] = isset($buffer['GATEWAYNAME'])?$buffer['GATEWAYNAME']:'';
				$updt['banktxnid'] = isset($buffer['BANKTXNID'])?$buffer['BANKTXNID']:'';
				$updt['paytmchecksum'] = isset($buffer['CHECKSUMHASH'])?$buffer['CHECKSUMHASH']:'';
				$updt['status'] = $buffer['status']; 
				$this->fps_model->saveupdate_fnd_aeps('dt_dmtlog_aeps',$updt,null,array('id'=>$insertid));
				/*update amount from wallet end */ 
				 
				
				$response['status'] = true;
				$response['data'] = $buffer ;
				$response['message'] = 'Please Wait for a moment! Transaction is Under Process...';

				}else if(isset($buffer['status']) && ($buffer['status'] == 'FAILURE') ){
					
	$updt['paymode'] = isset($buffer['PAYMENTMODE'])?$buffer['PAYMENTMODE']:'';
	$updt['transctionid'] = isset($buffer['TXNID'])?$buffer['TXNID']:'';
	$updt['bankname'] = isset($buffer['BANKNAME'])?$buffer['BANKNAME']:'';
	$updt['respmsg'] = isset($buffer['RESPMSG'])?$buffer['RESPMSG']:'';
	//$updt['paymode'] = isset($buffer['ORDERID'])?$buffer['ORDERID']:'';
	$updt['gatewayname'] = isset($buffer['GATEWAYNAME'])?$buffer['GATEWAYNAME']:'';
	$updt['banktxnid'] = isset($buffer['BANKTXNID'])?$buffer['BANKTXNID']:'';
	$updt['paytmchecksum'] = isset($buffer['CHECKSUMHASH'])?$buffer['CHECKSUMHASH']:'';
	$updt['status'] = 'PENDING';//$buffer['status'];
	$updt['final_status'] = 'no';
	$updatetbl = $this->fps_model->saveupdate_fnd_aeps('dt_dmtlog_aeps',$updt,null,array('id'=>$insertid));
	if(!$updatetbl){ 
		$response['status'] = false;
		$response['message'] = 'Some Error Occured!';
		echo json_encode($response);
	exit; }
				    /*refund deduct money to main wallet start */
						/*if( $orderid && $upwt ){
						$wt['credit_debit'] = 'credit';
			        	$wt['amount'] = (float)($amount + $surcharge);
						$wt['subject'] = 'aeps_tr_bk_refund' ;
						$wt['orderid'] = 'RFND'.filter_var($orderid,FILTER_SANITIZE_NUMBER_INT );
						$wt['transctionid'] = 'NA';
						$wt['remark'] = 'Refund for Orderid: '.$orderid;
						$rfwaltwhere['dts'] = $wt;
						$upwt = curlApis($walleturl,'POST', $rfwaltwhere,$headerwt );
					    } */
				    /*refund deduct money AEPS wallet END */

}else{
$response['status'] = false;
$response['message'] = isset($buffer['statusMessage'])?$buffer['statusMessage']:'Service is down!';
}
/****** check Agent user's wallet end***********/


}else{
	$response['status'] = false;
	$response['message'] = 'Authentication Failed!';
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

public function get_bal($uid,$utype){
		$where['userid'] = $uid;
		$where['usertype'] = $utype; 
		$where['status !='] = 'failed'; 
		$inkey = 'credit_debit';
		$invalue = 'credit,debit';
		$res = $this->c_model->getSingle('dt_wallet_aeps' ,$where, 'id,finalamount','id DESC', 1, $inkey, $invalue );
		$amt = !empty($res)?$res['finalamount']: 0;
		return (float)$amt;
}



public function check_transfer_range($request){ 

		$response = array();
		$data = array(); 
		$table = 'operators'; 
		$pertransaction = 5000;
		$total = false; 
			
			$amount = $request['amount']; 
			$schemetype = $request['schemetype']; 
			$amountinput = $amount;   
        if( $amount > 0 ){  

        	  

        $output = array();

					$checkamt['min <='] = $amount;
					$checkamt['max >='] = $amount;
					$checkamt['service'] = 1;
					$checkamt['currentapiid'] = 10;
					$dbdata = $this->c_model->getSingle($table,$checkamt,'*');

					$comwhere['user_type'] = 'AGENT';
					$comwhere['operatorid'] = $dbdata['id'];
					$comwhere['serviceid'] = 1;
					if($schemetype){
						$comwhere['scheme_type'] = $schemetype;
					} 
					$commssionarray = $this->c_model->getSingle('dt_set_commission', $comwhere, 'surcharge_fixed, surcharge_percent');

					$arr['operatorid'] = $dbdata['id'];
					$arr['operatorname'] = $dbdata['operator'];

							if(!is_null($dbdata) && !empty($dbdata)){

							  if( $commssionarray['surcharge_fixed'] > 0 ){
								$total = $total + $commssionarray['surcharge_fixed'];
								$arr['surcharge'] = $commssionarray['surcharge_fixed'];
							  }else{

							    $percent = percentage($amount,$commssionarray['surcharge_percent'] );
							  	$total = $total + $percent;
							  	$arr['surcharge'] = $percent;
							  } 

							}

							$arr['amount'] = $amount;
						array_push($output, $arr );	 
		 

            $response['status'] = TRUE;
            $response['debitamount'] = ( ( $total + $amountinput ));
            $response['totalsurcharge'] = ( $total );
            $response['dataarray'] = $output;  
		    
		    return $response ;
	}

}

}
?>