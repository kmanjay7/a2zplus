<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Fundtransfer extends CI_Controller{
	
	  function __construct() {
         parent::__construct();  
      }


function paytmpost(){

 header("Pragma: no-cache");
 header("Cache-Control: no-cache");
 header("Expires: 0");

	$response = array();
	$data = array();
	$request = requestJson('dts');
	$transferlimit = 25000;
	$pertransaction = 5000;

        
if( ($_SERVER['REQUEST_METHOD'] == 'POST') ){

/****  check token  start ****/ 
if( checktoken( DIGI_FUND_TXN_TOKEN ) ){   

$loggedin_id = isset($request['loggedin_id']) ? trim($request['loggedin_id']) : false;
$user_type = isset($request['user_type']) ? trim($request['user_type']) : false;
$sender_id = isset($request['sender_id']) ? trim($request['sender_id']) : false;
$benif_id = isset($request['benif_id']) ? trim($request['benif_id']) : false;
$accountno = isset($request['account_number']) ? trim($request['account_number']) : false;
$ifsc_code = isset($request['ifsc_code']) ? trim($request['ifsc_code']) : false;
$purpose = isset($request['purpose']) ? trim($request['purpose']) : false;
$amount = isset($request['amount']) ? trim($request['amount']) : false; 
$debitamount = isset($request['debitamount']) ? trim($request['debitamount']) : false; 
$paymode = isset($request['paymode']) ? trim($request['paymode']) : false;
$bankname = isset($request['bankid']) ? trim($request['bankid']) : false; 
$comments = 'Money Transfer';
$system_orderid = 'DMT'.date('Ymd');
$randonorderid = time().time();
//$where['add_by'] = $add_by;
$where['sender_id'] = $sender_id;
$where['id'] = $benif_id;
$keys = '*';
$checkuser = $sender_id && $this->c_model->countitem('benificiary', $where );



if( $accountno && $ifsc_code && $benif_id && $amount && $debitamount && ($checkuser == 1) && $loggedin_id && $user_type && $amount <= $transferlimit ){ 


/****** check Agent user's wallet start***********/
/****** check Agent user's wallet start***********/
    $checkwt['userid'] = $loggedin_id; 
    $checkwt['user_type'] = $user_type; 
    $walletbalance = checkwallet( $checkwt );

	if( $walletbalance >= $debitamount ){

 

				/* get sender monthly limit start */ 
				$limitarr = checksenderLimit($sender_id); 
		        $availablelimit = $transferlimit; 

		        if( $limitarr['status'] ){ 
		        $availablelimit =  $limitarr['available_limit']; 
		        }  
		        /* get sender monthly limit start */



         if( $amount <= $availablelimit ){  /*check sender available limit start*/

         require_once(APPPATH . "/third_party/PaytmKit/lib/config_paytm.php");
		 require_once(APPPATH . "/third_party/PaytmKit/lib/encdec_paytm.php");

    

$check_transfer_limit_url = ADMINURL.('webapi/dmtusers/Check_transfer_range');
$ck_t_l_p['amount'] = $amount;
$ck_lm_arr = curlApis($check_transfer_limit_url,'POST', $ck_t_l_p );

if(isset($ck_lm_arr['status']) && $ck_lm_arr['status'] && !empty($ck_lm_arr['dataarray']) ){

	$ploted_list = $ck_lm_arr['dataarray'];
 

        		/**//*deduct amount from wallet bind data start */
				$getaddby = $this->c_model->getSingle('users',array('id'=>$loggedin_id),'uniqueid,user_type');
				$save['userid'] = $loggedin_id;
				$save['usertype'] = $user_type;
				$save['uniqueid'] = trim($getaddby['uniqueid']);  
				$save['paymode'] = 'wallet';
				$save['transctionid'] = $randonorderid;
				$save['credit_debit'] = 'debit';
				$save['upiid'] = '';
				$save['bankname'] = ''; 
				$save['remark'] = 'Deduction against money transfer';
				$save['status'] = 'success'; 
				$save['amount'] = $debitamount; 
				$save['subject'] = str_replace(' ', '_', strtolower($comments)) ;
				$save['addby'] = $loggedin_id; 
				$save['orderid'] = '';
				$walleturl = ADMINURL.('webapi/wallet/Creditdebit');
				$waltwhere['dts'] = $save;  
				$headerwt = array('auth: Access-Token='.WALLETOKEN ); 
				$runwallet = 'no';
				/**//*deduct amount from wallet bind data end */
        

	/*---------------------------------------------------------------------*/
	$firstinsertid = '';
	foreach($ploted_list as $key=>$pvalue){ //start foreach loop

		$amount = $pvalue['amount'];

		$orderid = txnid();
		$insert['userid'] = $loggedin_id;
		$insert['usertype'] = $user_type;
		$insert['sender_id'] = $sender_id;
		$insert['benifi_id'] = $benif_id;
		//$insert['paymode'] = '';
		$insert['credit_debit'] = 'debit';
		$insert['transctionid'] = $randonorderid;
		//$insert['upiid'] = '';
		$insert['bankname'] = $bankname;
		$insert['respmsg'] = '';
		$insert['subject'] = $comments;
		$insert['orderid'] = $orderid;
		$insert['add_date'] = date('Y-m-d H:i:s');
		$insert['amount'] = $amount;
		$insert['gatewayname'] = '';
		//$insert['banktxnid'] = '';
		//$insert['paytmchecksum'] = '';
		$insert['status'] = 'REQUEST';
		$insert['mode'] = strtoupper($paymode);
		$insert['apiname'] = 'paytm';
		$insert['sur_charge'] = $pvalue['surcharge'];
		$insert['operatorid'] = $pvalue['operatorid'];
		$insert['operatorname'] = $pvalue['operatorname']; 
		$insert['sys_orderid'] = $randonorderid;

		$insertid = $this->c_model->saveupdate('dmtlog',$insert,$insert); 

			/* initialize an array */
			$paytmParams = array();
			$paytmParams["subwalletGuid"] = SUBWALLET_GUID;
			$paytmParams["orderId"] = $orderid ;
			$paytmParams["beneficiaryAccount"] = $accountno;
			$paytmParams["beneficiaryIFSC"] = $ifsc_code;
			$paytmParams["amount"] = $amount;

			/* Enter Purpose of transfer, possible values are: SALARY_DISBURSEMENT, REIMBURSEMENT, BONUS, INCENTIVE, OTHERS */
			$paytmParams["purpose"] = $purpose;
			$paytmParams["date"] = date('Y-m-d');
			$paytmParams["comments"] = $comments;
			$paytmParams["transferMode"] = strtoupper($paymode);

			$post_data = json_encode($paytmParams, JSON_UNESCAPED_SLASHES);
			$checksum = getChecksumFromString($post_data, PAYTM_MERCHANT_KEY );

			/* Find your MID in your Paytm Dashboard at https://dashboard.paytm.com/next/apikeys */
			$x_mid = PAYTM_MERCHANT_MID; 
			$x_checksum = $checksum; 
			$url = PAYTM_FUND_TXN_URL;  
            
            if( $insertid ){
            	if( !$firstinsertid ){ $firstinsertid = $insertid; } 
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "x-mid: " . $x_mid, "x-checksum: " . $x_checksum)); 
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
			$jsonresponse = curl_exec($ch);
			//echo $jsonresponse = ''; 
			$buffer['status'] = 'FAILURE';
			//$buffer['status'] = 'SUCCESS';
			$buffer = $jsonresponse ? json_decode( $jsonresponse, true ) : array();

				if(isset($buffer['status']) && ($buffer['status'] != 'FAILURE') ){




				/*update amount from wallet start */
				$updt['paymode'] = isset($buffer['PAYMENTMODE'])?$buffer['PAYMENTMODE']:'';
				$updt['transctionid'] = isset($buffer['TXNID'])?$buffer['TXNID']:'';
				$updt['paymode'] = isset($buffer['BANKNAME'])?$buffer['BANKNAME']:'';
				$updt['respmsg'] = isset($buffer['RESPMSG'])?$buffer['RESPMSG']:'';
				$updt['paymode'] = isset($buffer['ORDERID'])?$buffer['ORDERID']:'';
				$updt['gatewayname'] = isset($buffer['GATEWAYNAME'])?$buffer['GATEWAYNAME']:'';
				$updt['banktxnid'] = isset($buffer['BANKTXNID'])?$buffer['BANKTXNID']:'';
				$updt['paytmchecksum'] = isset($buffer['CHECKSUMHASH'])?$buffer['CHECKSUMHASH']:'';
				$updt['status'] = $buffer['status'];
				$this->c_model->saveupdate('dmtlog',$updt,null,array('id'=>$insertid));
				/*update amount from wallet end */


				/* run wallet deduct script start */
				if( $runwallet == 'no' ){
				$upwt = curlApis($walleturl,'POST', $waltwhere,$headerwt );
				$runwallet = isset($upwt['status']) && $upwt['status'] ?'yes':'no';
			    }
				/* run wallet deduct script end */

				
				$response['status'] = true;
				$response['data'] = $buffer ;
				$response['message'] = 'Suceess!';

				}else{
				$response['status'] = false;
				$response['message'] = isset($buffer['statusMessage'])?$buffer['statusMessage']:'Paytm error!';
				}
		    }else{
		    $response['status'] = false;
		    $response['message'] = 'Database Error!';
		    }

     }//end foreach loop

                        if( $runwallet == 'yes' ){
                        	$ordup['sys_orderid'] = $system_orderid.$firstinsertid;
                        	$this->c_model->saveupdate('dmtlog',$ordup,null,array('sys_orderid'=>$randonorderid)); 
                        	$wtuptxn['referenceid'] = $ordup['sys_orderid'];
                        	$this->c_model->saveupdate('wallet',$wtuptxn,null,array('transctionid'=>$randonorderid)); 
                        }else if( $firstinsertid ){
                        	$ordup['sys_orderid'] = $system_orderid.$firstinsertid;
                        	$this->c_model->saveupdate('dmtlog',$ordup,null,array('sys_orderid'=>$randonorderid)); 
                        }
    }else{
    	$response['status'] = false;
    	$response['message'] = 'Some Server Error!';
    }            
   /*---------------------------------------------------------------------*/
          }else{
          	$response['status'] = false;
			$response['message'] = 'Available transfer limit is '.$availablelimit.' for this sender!';
          }	

	}else {
	$response['status'] = false;
	$response['message'] = 'Wallet Balance is low for this transaction!';
	}

/****** check Agent user's wallet end***********/
/****** check Agent user's wallet end***********/








	}else if(!$checkuser && $sender_id && $loggedin_id && $amount > $transferlimit){
	$response['status'] = false;
	$response['message'] = 'User not exists!';
	}else if( $amount > $transferlimit ) {
	$response['status'] = false;
	$response['message'] = 'Transaction amount should not be greater than 25000 thousands!';
	}else{
	$response['status'] = false;
	$response['message'] = 'Please fill all required fields!';
	}
/****** check Agent user's wallet end***********/
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

}
?>