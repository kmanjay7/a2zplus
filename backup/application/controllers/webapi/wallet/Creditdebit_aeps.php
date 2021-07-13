<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Creditdebit_aeps extends CI_Controller{
	
	  function __construct() {
         parent::__construct();  
         $this->load->model('Creditdebit_aeps_model','cr_aeps_model');  
      }
	

 public function index() {

  	    header("Pragma: no-cache");
		header("Cache-Control: no-cache");
		header("Expires: 0");
	  
	     		$response = array();
	     	   $duplicate = array();
				    $data = array();
				  $update = ''; 
				   $runquery = 4;
              $statuschek = array('success','pending','failed','request');
              $pmodechek = array('credit','debit');

					
				   $table = 'wallet_aeps';
			   $usertable = 'users'; 
				 
				 $request = requestJson('dts');
		

 
		if( ($_SERVER['REQUEST_METHOD'] == 'POST') ){
			
/****  check token  start ****/ 
if( checktoken( AEPS_FUND_TXN_TOKEN ) ){ 

			 $uniqueid = !empty($request['uniqueid']) ? $request['uniqueid'] : '' ;
			 $userid  =  !empty($request['userid']) ? trim($request['userid']) : NULL ; 
			 $credit_debit  =  !empty($request['credit_debit']) ? trim($request['credit_debit']) : NULL ; 
		     $wherearray['id'] = $userid;
		     $wherearray['uniqueid'] = $uniqueid;
		     if($credit_debit == 'debit'){
				$wherearray['status'] = 'yes';
		     }
		     
			 $checkuser = $this->cr_aeps_model->countitem_aeps_cr($usertable,$wherearray  );
			
			
		 
		  /* Add Booking Data start script*/
		  
		  
		   
		    $usertype = !empty($request['usertype']) ? trim($request['usertype']) : NULL ;
			$paymode  = !empty($request['paymode']) ? trim($request['paymode']) : NULL ;
			
			$transctionid  =  !empty($request['transctionid']) ? trim($request['transctionid']) : NULL ;
			$credit_debit  =  !empty($request['credit_debit']) ? trim($request['credit_debit']) : NULL ; 
			$upiid  =  !empty($request['upiid']) ? ($request['upiid']) : NULL ; 
			$bankname  =  !empty($request['bankname']) ? ($request['bankname']) : NULL ; 
			$remark  =  !empty($request['remark']) ? ($request['remark']) : NULL ; 
			$status  =  !empty($request['status']) ? trim($request['status']) : NULL ; 
			$referenceid =!empty($request['orderid']) ? trim($request['orderid']): NULL ; 
			$amount  = !empty($request['amount']) ? trim($request['amount']) : NULL ;
			$subject  = !empty($request['subject']) ? ($request['subject']) : NULL ;
			$addby  = isset($request['addby']) ? trim($request['addby']) : NULL ;
			$sendsms  = isset($request['sendsms']) &&($request['sendsms']=='no') ? 'no' : 'yes' ;  
			$amount  = (float)$amount; 

			$coments  = isset($request['coments']) ? trim($request['coments']) : '' ;
			$borrow  = isset($request['borrow']) ? trim($request['borrow']) : '' ;
			$sendto  = isset($request['sendto']) ? trim($request['sendto']) : '' ;

			$odr  = isset($request['odr']) ? trim($request['odr']) : '0' ;
			$surch  = isset($request['surch']) ? trim($request['surch']) : '0' ;
			$comi  = isset($request['comi']) ? trim($request['comi']) : '0' ;
			$tds  = isset($request['tds']) ? trim($request['tds']) : '0' ;
			
            
  if(!$uniqueid){
			$response['status'] = FALSE; 
			$response['message'] = 'Uniqueid is blank!';
  }else if(!$usertype){
			$response['status'] = FALSE; 
			$response['message'] = 'User Type is blank!';
  }else if(!$paymode){
			$response['status'] = FALSE; 
			$response['message'] = 'Payment Mode is blank!';
  }else if(!$credit_debit){
	        $response['status'] = FALSE; 
			$response['message'] = 'Credit/Debit is blank!';
  }else if(!$status){
			$response['status'] = FALSE; 
			$response['message'] = 'Status is blank!';
  }else if(!$amount){
			$response['status'] = FALSE; 
			$response['message'] = 'Amount is blank!';
  }else if(!$addby){
			$response['status'] = FALSE; 
			$response['message'] = 'Add By is blank!!';
  }else if(!$checkuser){ 
			$response['status'] = FALSE;
			$response['message'] = 'User not exists!';
  }else if(!$userid){ 
			$response['status'] = FALSE;
			$response['message'] = 'User table is blank!';
  }else if(!in_array($status, $statuschek)){ 
			$response['status'] = FALSE;
			$response['message'] = 'Use only success,pending,failed,request in small character!'; 
  }else if(!in_array($credit_debit, $pmodechek)){
			$response['status'] = FALSE;
			$response['message'] = 'Use only credit/debit in small character!';
  }else if(!$transctionid){
			$response['status'] = FALSE;
			$response['message'] = 'Transaction id is blank!';
  }else if( $checkuser == 1 ){



			$duplicate['userid'] = $userid ;
			$duplicate['usertype'] = $usertype ;
			//$duplicate['paymode'] = $paymode ;
			if( $transctionid ){ $duplicate['transctionid'] = $transctionid ; }
			$duplicate['referenceid'] = $referenceid ;
			$duplicate['credit_debit'] = $credit_debit ; 
			$duplicate['amount'] = fourDecimal($amount) ; 
			if( $subject ){ $duplicate['subject'] = $subject ; }

   /********** check duplicate  wallet entry start***************/
   $checkduplicate = $this->cr_aeps_model->countitem_aeps_cr($table, $duplicate);
		   
    if( empty($checkduplicate ) ) {  
    
    /* wallet deduction/addition start*/
    $inkey = null;
	$invalue = null;

	$checklast['userid'] = $userid;
	$checklast['usertype'] = $usertype; 
	$inkey = 'credit_debit';
	$invalue = 'credit,debit';
    

	$lastentry = $this->cr_aeps_model->getSingle_aeps_cr( $table,$checklast, 'finalamount, beforeamount','id DESC' , 1, $inkey, $invalue );
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
			$post['transctionid'] = $transctionid ? $transctionid : time().rand(10,99) ;
			$post['credit_debit'] = $credit_debit ;
			$post['upiid'] = $upiid ;
			$post['bankname'] = $bankname ;
			$post['add_date'] = date('Y-m-d H:i:s') ;
			$post['remark'] = $remark ;
			$post['status'] = $status ;
			$post['referenceid'] = $referenceid ;
			$post['amount'] = $amount ;
			$post['finalamount'] = $newtotalamount ;
			$post['subject'] = $subject ;
			$post['addby'] = $addby ;
			$post['beforeamount'] = $beforeamount ;

			$post['coments'] = $coments ;
			$post['borrow'] = $borrow ;
			$post['sendto'] = $sendto ;

			$post['odr'] = (float)$odr ;
			$post['surch'] = (float)$surch ;
			$post['comi'] = (float)$comi ;
			$post['tds'] = (float)$tds ;
			$post['flag'] = 1;
			
	

	
		    $post = $this->security->xss_clean($post);
		 
			if( $amount && $userid && $credit_debit && $execute ){
			 $update = $this->cr_aeps_model->saveupdate_aeps_cr( $table, $post, $duplicate ); 
			 $runquery = $update ? 1 : 2; 
			}else if(!$execute){ $runquery = 4;}
	}else if ( $checkduplicate) {
		$runquery =  3; 
	}	

 /********** check duplicate  wallet entry end***************/

	
			if( $runquery == 1 ){

				$response['status'] = TRUE;
				$response['data'] = array('currentamount'=>$newtotalamount );
				$response['message'] = 'Wallet updated Successfully!';

			/* create sms format start */ 
			if( $addby != $userid ){
			$userdata = $this->cr_aeps_model->getSingle_aeps_cr('dt_users',array('id'=>$userid),'mobileno,ownername,parentid');
			$byuser = 'DigiCash India';  
			if($userdata['parentid'] == $addby && $userdata['parentid']){
				$parntdata = $this->cr_aeps_model->getSingle_aeps_cr('dt_users',array('id'=>$userdata['parentid']),'mobileno,ownername');
				$byuser = 'Your Parent('.$parntdata['ownername'].' ['.$parntdata['mobileno'].'])'; 
			}elseif(!is_numeric($addby)){ $byuser = 'Payment Gatway';  }
$msgbody = "Dear ".strtoupper($userdata['ownername']).", Your AEPS wallet has been ".$credit_debit."ed with Rs. ".$amount." and new balance is ".$newtotalamount."; By- ".$byuser.".
Regards,
DigiCash India"; 
			$sendsmsresponse = $userdata['mobileno'] &&($sendsms=='yes')?simplesms($userdata['mobileno'],$msgbody):null;
		    }
			/* create sms format end */ 

				 
			}else if( $runquery == 2 ){
				$response['status'] = FALSE; 
				$response['message'] = 'Some error occured!';
			}else if( $runquery == 3 ){
				$response['status'] = FALSE; 
				$response['message'] = 'Duplicate entry!';
			}else if( $runquery == 4 ){
				$response['status'] = FALSE; 
				$response['message'] = 'Wallet balance is low. Please refill wallet!';
			}else{
				$response['status'] = FALSE;
				$response['message'] = 'Please fill proper data!';
			} 
		 
  /********** check duplicate  wallet entry end***************/	    
		
		
	}



}else{ 
		        $response['status'] = FALSE;
				$response['message'] = 'Authentication failed!';
				}


		}else{ 
		        $response['status'] = FALSE;
				$response['message'] = 'Bad Request!';
				}
		
		
	    header("Content-Type: application/json");
		echo json_encode($response);
	
	}
	
}
?>