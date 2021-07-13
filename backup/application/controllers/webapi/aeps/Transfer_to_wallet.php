<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Transfer_to_wallet extends CI_Controller{
	 
	 public function __construct() {
		parent::__construct(); 
	 }
	 
		
	public function index() {
			
			$response = array();
			    $data = array(); 
			$request = requestJson();
		 
			
 /****  check Request  Status start ****/ 
 if( ($_SERVER['REQUEST_METHOD'] == 'POST') ){
			
	           
	           $userid = $request['user_id'];
	           $user_type = $request['usertype'];
	           $amount = $request['amount'];
	           $uniqueid = $request['uniqueid'];


			 			  
            
if( !$userid ){
	$response['status'] = FALSE;
	$response['message'] = 'User Id is blank!';
	header("Content-Type: application/json");
	echo json_encode( $response ); exit;
}else if( !$user_type ){
	$response['status'] = FALSE;
	$response['message'] = 'User type is blank!';
	header("Content-Type: application/json");
	echo json_encode( $response ); exit;
}else if( !$amount ){
	$response['status'] = FALSE;
	$response['message'] = 'Amount is blank!';
	header("Content-Type: application/json");
	echo json_encode( $response ); exit;
}else if( !$uniqueid ){
	$response['status'] = FALSE;
	$response['message'] = 'Uniqueid is blank!';
	header("Content-Type: application/json");
	echo json_encode( $response ); exit;
}

			 
			$where['id'] = $userid;
			$where['user_type'] = $user_type;
			$where['uniqueid'] = $uniqueid;

			$countitem = $this->c_model->countitem('dt_users',$where);

			if( $countitem != 1 ){
				$response['status'] = FALSE;
				$response['message'] = 'User not exists!';
				header("Content-Type: application/json");
				echo json_encode( $response );
				exit;
			} 


			/*check aeps wallet balance */
			$chwt['userid'] = $userid;
			$chwt['user_type'] = $user_type;  

			$wturl = APIURL.('webapi/wallet/walletstatus'); 
			$buffer = curlApis($wturl,'POST',$chwt );
			$aepswt = '0.000'; 
			if($buffer['status'] && !empty($buffer['data'])){
			$aepswt = $buffer['data']['wallet_aeps']; 

			} 

			if( $aepswt < $amount ){
				$response['status'] = FALSE;
				$response['message'] = 'Wallet balance is low!';
				header("Content-Type: application/json");
				echo json_encode( $response );
				exit;
			} 
 
			 


				/*********deduct from aeps wallet********/
                $wt['userid'] = $userid;
				$wt['usertype'] = $user_type;
				$wt['uniqueid'] = $uniqueid;  
				$wt['paymode'] = 'wallet';
				$wt['transctionid'] = 'NA';
				$wt['credit_debit'] = 'debit';
				$wt['upiid'] = '';
				$wt['bankname'] = ''; 
				$wt['remark'] = 'Transfer to main wallet';
				$wt['status'] = 'success'; 
				$wt['amount'] = $amount;
				$wt['subject'] = 'aeps_tr_wt';
				$wt['addby'] = $userid; 
				$wt['orderid'] = 'M2W'.date('YmdHis').$userid;

				$wt['odr'] = $amount;
				$wt['surch'] = '0';
				$wt['comi'] = '0';
				$wt['tds'] = '0';
				$wt['flag'] = 1;

				$walleturl = ADMINURL.('webapi/wallet/Creditdebit_aeps'); 
				$headerwt = array('auth: Access-Token='.AEPS_FUND_TXN_TOKEN );  
				$waltwhere['dts'] = $wt; 
			    $upwt = curlApis($walleturl,'POST', $waltwhere,$headerwt );


			    /*********Add to main wallet********/
                if($upwt['status']){
                $wtt['userid'] = $userid;
				$wtt['usertype'] = $user_type;
				$wtt['uniqueid'] = $uniqueid;  
				$wtt['paymode'] = 'wallet';
				$wtt['transctionid'] = 'NA';
				$wtt['credit_debit'] = 'credit';
				$wtt['upiid'] = '';
				$wtt['bankname'] = ''; 
				$wtt['remark'] = 'Transfer from AEPS wallet Order ID:'.$wt['orderid'];
				$wtt['status'] = 'success'; 
				$wtt['amount'] = $amount;
				$wtt['subject'] = 'fill_wt_aeps';
				$wtt['addby'] = $userid; 
				$wtt['orderid'] = 'WLT'.date('YmdHis').$userid;
				$wtt['odr'] = $amount;
				$wtt['surch'] = '0';
				$wtt['comi'] = '0';
				$wtt['tds'] = '0';
				$wtt['flag'] = 1;


				$walletapiurl = ADMINURL.('webapi/wallet/Creditdebit'); 
				$headerwtt = array('auth: Access-Token='.WALLETOKEN );  
				$wttwhere['dts'] = $wtt; 
			    $upwtt = curlApis($walletapiurl,'POST', $wttwhere,$headerwtt );
				    if($upwtt['status']){
				    	$response['status'] = true;
    					$response['message'] = 'Amount has been successfully transferred to your Main Wallet';
				    }else{
				    	$response['status'] = FALSE;
    					$response['message'] = $upwtt['message'];
				    }
			    }else{
			    	$response['status'] = FALSE;
    				$response['message'] = 'Some Error Occured!';
			    }
					 
			 
			
		 
		
/*token check end*/	
}else{ 
	$response['status'] = FALSE;
    $response['message'] = "Bad Request!";
}
		
	    header("Content-Type:application/json");
		echo json_encode( $response );
		
	
	 }

			
}
?>