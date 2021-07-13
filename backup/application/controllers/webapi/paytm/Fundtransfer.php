<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Fundtransfer extends CI_Controller{
	  var $apiid ;
	  function __construct() {
         parent::__construct(); 
         $this->load->model('Fundtransfer_model','fnd_model');
         $this->apiid = 3; 
      }


public function get_bal($uid,$utype){
		$where['userid'] = $uid;
		$where['usertype'] = $utype; 
		$where['status !='] = 'failed'; 
		$inkey = 'credit_debit';
		$invalue = 'credit,debit';
		$res = $this->fnd_model->getSingle_fnd('wallet' ,$where, 'id,finalamount','id DESC', 1, $inkey, $invalue );
		$amt = !empty($res)?$res['finalamount']: 0;
		return $amt;
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
$scheme_type = isset($request['scheme_type']) ? trim($request['scheme_type']) : false;
$sender_id = isset($request['sender_id']) ? trim($request['sender_id']) : false;
$benif_id = isset($request['benif_id']) ? trim($request['benif_id']) : false;
$accountno = isset($request['account_number']) ? trim($request['account_number']) : false;
$ifsc_code = isset($request['ifsc_code']) ? trim($request['ifsc_code']) : false;
$purpose = isset($request['purpose']) ? trim($request['purpose']) : false;
$amount = isset($request['amount']) ? trim($request['amount']) : false; 
$amount = (float)$amount;
$debitamount = isset($request['debitamount']) ? trim($request['debitamount']) : false; 
$paymode = isset($request['paymode']) ? trim($request['paymode']) : false;
$bankname = isset($request['bankid']) ? trim($request['bankid']) : false;
$apptype = isset($request['apptype']) ? trim($request['apptype']) : 'A'; 
$comments = 'Money Transfer';
$system_orderid = 'DMT'.date('Ymd');
$randonorderid = time().time();
//$where['add_by'] = $add_by;
$where['sender_id'] = $sender_id;
$where['id'] = $benif_id;
$keys = '*';
$checkuser = $sender_id && $this->fnd_model->countitem_fnd('benificiary', $where );

/*check get deduct amount accrding to user scheme type start script*/
//this is c
            //$ddt_post_url = APIURL.('webapi/dmtusers/Check_transfer_range');  
            $ddt_post['amount'] = $amount; 
            $ddt_post['scheme_type'] = $scheme_type;
            //$ddt_buffer = curlApis($ddt_post_url,'POST',$ddt_post );
            $ddt_buffer = $this->check_transfer_range($ddt_post);
            $ddt_amount = false;
             if(isset($ddt_buffer['status']) && $ddt_buffer['status']){ 
               $ddt_amount = $ddt_buffer['debitamount']; 
               $debitamount = $ddt_amount;
            }  
 $ck_lm_arr = $ddt_buffer;           
/*check get deduct amount accrding to user scheme type start end*/


if( $accountno && $ifsc_code && $benif_id && $amount && $debitamount && ($checkuser == 1) && $loggedin_id && $user_type && $amount <= $transferlimit && $amount >= 100 && $scheme_type && $ddt_amount >= $amount ){ 


/****** check Agent user's wallet start***********/
/****** check Agent user's wallet start***********/
    $walletbalance = $this->get_bal($loggedin_id,$user_type);

	if( $walletbalance >= $debitamount ){

 

				/* get sender monthly limit start */
				$checklimit = []; 
				$checklimit['sender_id'] = $sender_id;
				$limitarr = $this->checksender_limit($checklimit); 
				//checksenderLimit($sender_id); 
		        $availablelimit = $transferlimit; 

		        if( $limitarr['status'] ){ 
		        $availablelimit =  $limitarr['available_limit']; 
		        }  
		        /* get sender monthly limit start */



         if( (float)$amount <= (float)$availablelimit ){/*check sender available limit start*/

         require_once(APPPATH . "/third_party/PaytmKit/lib/config_paytm.php");
		 require_once(APPPATH . "/third_party/PaytmKit/lib/encdec_paytm.php");

 

if(isset($ck_lm_arr['status']) && $ck_lm_arr['status'] && !empty($ck_lm_arr['dataarray']) ){

	$ploted_list = $ck_lm_arr['dataarray'];
 

        		/**//*deduct amount from wallet bind data start */
				$getaddby = $this->fnd_model->getSingle_fnd('users',array('id'=>$loggedin_id),'uniqueid,user_type');
				$save['userid'] = $loggedin_id;
				$save['usertype'] = $user_type;
				$save['uniqueid'] = trim($getaddby['uniqueid']);  
				$save['paymode'] = 'wallet';
				$save['transctionid'] = '';
				$save['credit_debit'] = 'debit';
				$save['upiid'] = '';
				$save['bankname'] = ''; 
				$save['remark'] = 'Money transfer';
				$save['status'] = 'success'; 
				$save['amount'] = ''; 
				$save['subject'] = '' ;
				$save['addby'] = $loggedin_id; 
				$save['orderid'] = '';
				$walleturl = ADMINURL.('webapi/wallet/Creditdebit'); 
				$headerwt = array('auth: Access-Token='.WALLETOKEN ); 
				$runwallet = 'no';
				/**//*deduct amount from wallet bind data end */
        

	/*---------------------------------------------------------------------*/
	$firstinsertid = false; $increament = 1; $successed_ord = array();
	foreach($ploted_list as $key=>$pvalue){ //start foreach loop

		$amount = $pvalue['amount'];
		$ag_comi = $pvalue['ag_comi'];
		$ag_tds = $pvalue['ag_tds'];
		$surcharge = $pvalue['surcharge'];
		$wallet_entry = false;

		$wallet_entry = ( ((float)$amount +(float)$surcharge ) - (float)$ag_comi );

		$orderid = txnid();
		$insert['userid'] = $loggedin_id;
		$insert['usertype'] = $user_type;
		$insert['sender_id'] = $sender_id;
		$insert['benifi_id'] = $benif_id;
		//$insert['paymode'] = '';
		$insert['credit_debit'] = 'debit';
		$insert['transctionid'] = '';
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
		$insert['status'] = 'PENDING'; //
		$insert['mode'] = strtoupper($paymode);
		$insert['apiname'] = 'paytm';
		$insert['sur_charge'] = $surcharge;
		$insert['operatorid'] = $pvalue['operatorid'];
		$insert['operatorname'] = $pvalue['operatorname']; 
		$insert['sys_orderid'] = $randonorderid;
		$insert['status_update'] = date('Y-m-d H:i:s'); 
		$insert['ag_comi'] = $ag_comi;
		$insert['ag_tds'] = $ag_tds;
		$insert['apptype'] = $apptype; 
		$insert['nextupdate'] = date('Y-m-d H:i:s',strtotime(date('Y-m-d H:i:s').' +31 seconds'));
		$insert['noofround'] = 0;

		$insertid = $this->fnd_model->saveupdate_fnd('dmtlog',$insert,$insert); 
		$orderid = $system_orderid.$insertid;
		$perentryorderid['orderid'] = $orderid;
		if( !$firstinsertid ){ $firstinsertid = $orderid; } 
		$perentryorderid['sys_orderid'] = $firstinsertid;
        $insertid ? $this->fnd_model->saveupdate_fnd('dmtlog',$perentryorderid,null,array('id'=>$insertid)) : null;
        $runwallet = true;

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


            	/* run wallet deduct for amount script start */
				if( $orderid ){
            	$save['amount'] = $wallet_entry;
				$save['subject'] = 'dmt_1' ;
				$save['orderid'] = $orderid;
				$save['transctionid'] = $increament;
				$save['remark'] = 'Money transfer';
				$save['odr'] = $amount;
				$save['surch'] = $surcharge;
				$save['comi'] = $ag_comi;
				$save['tds'] = $ag_tds;
				$save['flag'] = 1; 
				$waltwhere['dts'] = $save;
				$upwt = curlApis($walleturl,'POST', $waltwhere,$headerwt );

				if(!$upwt['status']){ /*if wallet balance status is false*/
				$response['status'] = false;
				$response['message'] = $upwt['message'];
				header('Content-Type:application/json');
				echo json_encode($response);
				exit;
				}
				
			    }


				

            $log = $this->pushlog($orderid,'dmt','I',$post_data);
            	
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "x-mid: " . $x_mid, "x-checksum: " . $x_checksum)); 
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
			$jsonresponse = curl_exec($ch); 
			curl_close($ch); 
			$buffer['status'] = 'FAILURE';
			$log = $this->pushlog($orderid,'dmt','O',$jsonresponse);

			/*$jsonresponse = false; 
			$buffer = [];
			$buffer['status'] = 'SUCCESS';*/
			
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
				$this->fnd_model->saveupdate_fnd('dmtlog',$updt,null,array('id'=>$insertid));
				/*update amount from wallet end */ 
				 
				
				$response['status'] = true;
				$response['data'] = $buffer ;
				$response['message'] = 'Please Wait for a moment! Transaction is Under Process...';
				$response['printSlip'] = $firstinsertid; 
				

				}else if(isset($buffer['status']) && ($buffer['status'] == 'FAILURE') ){
					
	$updt['paymode'] = isset($buffer['PAYMENTMODE'])?$buffer['PAYMENTMODE']:'';
	$updt['transctionid'] = isset($buffer['TXNID'])?$buffer['TXNID']:'';
	$updt['paymode'] = isset($buffer['BANKNAME'])?$buffer['BANKNAME']:'';
	$updt['respmsg'] = isset($buffer['RESPMSG'])?$buffer['RESPMSG']:'';
	$updt['paymode'] = isset($buffer['ORDERID'])?$buffer['ORDERID']:'';
	$updt['gatewayname'] = isset($buffer['GATEWAYNAME'])?$buffer['GATEWAYNAME']:'';
	$updt['banktxnid'] = isset($buffer['BANKTXNID'])?$buffer['BANKTXNID']:'';
	$updt['paytmchecksum'] = isset($buffer['CHECKSUMHASH'])?$buffer['CHECKSUMHASH']:'';
	$updt['status'] = 'PENDING';//$buffer['status'];
	$this->fnd_model->saveupdate_fnd('dmtlog',$updt,null,array('id'=>$insertid));
				    /*refund deduct money to main wallet start */
						/*if( $orderid && $upwt ){
						$save['credit_debit'] = 'credit';
			        	$save['amount'] = $wallet_ent
			        	ry;;
						$save['subject'] = 'dmt_1_refund' ;
						$save['orderid'] = 'RFND'.filter_var($orderid,FILTER_SANITIZE_NUMBER_INT );
						$save['transctionid'] = 'NA';
						$save['remark'] = 'Refund for Orderid: '.$orderid;
						$waltwhere['dts'] = $save;
						$upwt = curlApis($walleturl,'POST', $waltwhere,$headerwt );
					    } */
				    /*refund deduct money to main wallet start */

				}else{
				$response['status'] = false;
				$response['message'] = isset($buffer['statusMessage'])?$buffer['statusMessage']:'Paytm error!';
				}
		    }else{
		    $response['status'] = false;
		    $response['message'] = 'Database Error!';
		    }

     $increament++; }//end foreach loop

                         
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








	}else if(!$checkuser && $sender_id && $loggedin_id && $amount > $transferlimit && $amount >= 100 && $ddt_amount >= $amount && $scheme_type ){
	$response['status'] = false;
	$response['message'] = 'User not exists!';
	}else if( !$scheme_type ){
	$response['status'] = false;
	$response['message'] = 'User Scheme is blank!';
	}else if( $ddt_amount < $amount ){
	$response['status'] = false;
	$response['message'] = 'Something went wrong!';
	}else if($amount < 100){
	$response['status'] = false;
	$response['message'] = 'Transaction amount should be equal to or greater than 100 Rupees!';
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


public function pushlog($odr,$type,$io,$payload){
	$insert = [];
	$insert['odr'] = $odr;
	$insert['type'] = $type;
	$insert['io'] = $io;
	$insert['req_res'] = $payload;
	$insert['timeon'] = date('Y-m-d H:i:s'); 
	return $this->c_model->saveupdate('dt_pushlog',$insert );
}


public function checksender_limit($request){

		$response = array();
		$data = array(); 
		$table = 'dt_sender'; 
			 
		$senderid = $request['sender_id']; 
		$ckwhere['id'] = $senderid;	 
	    $checkaddby = $this->fnd_model->countitem_fnd($table,$ckwhere );
        
        if( $checkaddby == 1 ){ 
        	 
 
	        $keys = 'SUM(`amount`) AS total, sender_id ';
	        $where['sender_id'] = $senderid;
	        $where["DATE_FORMAT(add_date, '%Y-%m')="] = date('Y-m');
	       // $where['status !='] = 'FAILURE';
	        $not_inkey = 'status';
	        $not_invalue = 'FAILURE,PENDING';
	        $data = $this->fnd_model->getSingle_fnd('dmtlog',$where,$keys , null,null,null,null,$not_inkey,$not_invalue ); 

	        $total = $data['total'];
	       
            	$response['status'] = TRUE;
				$response['total_limit'] = (string) 25000;
				$response['available_limit'] = (string) ( 25000 - $total );
				$response['used_limit'] = (string) $total;
			    $response['message'] = 'Data matched in our database!'; 

        }else{ 
			$response['status'] = FALSE;
			$response['message'] = 'User not found in our database!';
        }
      
	  
		return $response;
	}



public function check_transfer_range($request){ 
		$response = array();
		$data = array(); 
		$table = 'operators'; 
		$pertransaction = 5000;
		$total = false; 
			
			$amount = $request['amount']; 
			$schemetype = $request['scheme_type']; 
			$apiid = isset($request['apiid'])?trim($request['apiid']):false; 
			$amountinput = $amount;   
           

        	    $runloop = 1;
				$totalrunloop = $runloop;
				$restamount = 0; 

				if( $amount > $pertransaction){ 
					$runloop = floor( $amount / $pertransaction );
					$totalrunloop = $runloop;
					$restamount = $amount - ($runloop*$pertransaction) ;
					if( $restamount > 0 ){ $totalrunloop += 1;  }
					$amount = $pertransaction;
				} 

$output = array();
$commision = false;

		for ($i=1; $i <= $totalrunloop; $i++) { //start for loop

				if( ($i > $runloop) && $restamount ) { $amount = $restamount; } 

					$checkamt['min <='] = $amount;
					$checkamt['max >='] = $amount;
					$checkamt['service'] = '6';
					$dbdata = $this->fnd_model->getSingle_fnd($table,$checkamt,'*');

					$comwhere['user_type'] = 'AGENT';
					$comwhere['operatorid'] = $dbdata['id'];
					$comwhere['serviceid'] = '6';
					if($schemetype){
						$comwhere['scheme_type'] = $schemetype;
					}
					if($apiid){
						$comwhere['apiid'] = $apiid;
					} 
					$commssionarray = $this->fnd_model->getSingle_fnd('dt_set_commission', $comwhere, 'surcharge_fixed, surcharge_percent, commision_fixed, commision_percent');
//print_r($commssionarray); exit;
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

	  /*start of calculate Agent comission */
	  if( $commssionarray['commision_fixed'] > 0 ){
		$commision =(float)$commssionarray['commision_fixed']; 
	  }else{

	    $percent_comi = percentage($arr['surcharge'],$commssionarray['commision_percent'] );
	  	$commision = (float)$percent_comi; 
	  }

	  $tds = (float) percentage($commision,TDS );
	  $arr['total_ag_commision'] = $commision;
	  $arr['ag_comi'] = ($commision - $tds);
	  $arr['ag_tds'] = $tds;
	  /*end of calculate Agent comission */

							}

						$arr['amount'] = $amount;
						array_push($output, $arr );	
		        
		} //end for loop

            $response['status'] = TRUE;
            $response['debitamount'] = ( ( $total + $amountinput ));
            $response['totalsurcharge'] = ( $total );
            $response['dataarray'] = $output;
			$response['message'] = 'Amount calculated!'; 
	  
	    return $response;
	}



}
?>