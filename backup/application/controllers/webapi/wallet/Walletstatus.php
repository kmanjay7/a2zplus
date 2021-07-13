<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Walletstatus extends CI_Controller{
	
	 public function __construct() {
		parent::__construct();
		$this->load->model('Wallet_status_model', 'wt_status');
	 }
		
	public function index() {

		header("Pragma: no-cache");
		header("Cache-Control: no-cache");
		header("Expires: 0");
			
			$response = array();
			    $data = array(); 
			   $table = 'wallet';
			$usertype = 'users';
			 $getdata = array();
			   $today = date('Y-m-d');
			
			 $request = requestJson();
			
			
			
			
/*******  check method start   *********/	
if( ($_SERVER['REQUEST_METHOD'] == 'POST') ){
			
	 
  $user_type = !empty($request['user_type']) ? trim($request['user_type']) : '';
  $userid  =  !empty($request['userid']) ? trim($request['userid']) : NULL ;
			  
			
	$checkwhere['id'] = $userid;
	$checkwhere['user_type'] = $user_type; 
	if($user_type != 'admin'){
		//$checkwhere['status'] = 'yes';
	} 
	//$checkwhere['kyc_status'] = 'yes'; 
	$checkuser = $this->wt_status->countitem_wt($usertype,$checkwhere);
  
	  
			
if( $checkuser == 1 ){

/* get main wallet data start */ 
	$checklast['userid'] = $userid;
	$checklast['usertype'] = $user_type; 
	$checklast['status !='] = 'failed'; 
	$inkey = 'credit_debit';
	$invalue = 'credit,debit';
	$lastentry = $this->wt_status->getSingle_wt( $table ,$checklast, 'id,finalamount','id DESC' , 1, $inkey, $invalue ); 
/* get main wallet data end */

/* get aeps wallet data start */ 
	$ae_checklast['userid'] = $userid;
	$ae_checklast['usertype'] = $user_type; 
	$ae_checklast['status !='] = 'failed'; 
	$ae_inkey = 'credit_debit';
	$ae_invalue = 'credit,debit';
	$ae_lastentry = $this->wt_status->getSingle_wt('dt_wallet_aeps' ,$ae_checklast, 'id,finalamount','id DESC' , 1, $ae_inkey, $ae_invalue ); 
/* get aeps wallet data end */

			 
	$data = array('id'=>(string) $lastentry['id'],  
	   	'wallet'=>(string) twoDecimal($lastentry['finalamount']),
	   	'id_aeps'=>(string) $ae_lastentry['id'], 
	   	'wallet_aeps'=>(string) twoDecimal($ae_lastentry['finalamount']), 
	 );
    $status = 1;
						 
}else{ $status = 2; }

			 
			
			if($status == 1 ){ 
			$response['status'] = TRUE;
			$response['data'] = $data; 
		    $response['message'] = "Status was successful!";
			}else if($status == 2 ){
			
			$response['status'] = FALSE;
		    $response['message'] = "User not exists!";		
			}
			
		 
		
/*******  check method end   *********/	
}else{ 
	$response['status'] = FALSE;
    $response['message'] = "Bad Request!";
}
		
	    header("Content-Type:application/json");
		echo json_encode( $response );
		
	
	 }
		
}
?>