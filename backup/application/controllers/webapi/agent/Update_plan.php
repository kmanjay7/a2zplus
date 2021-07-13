<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Update_plan extends CI_Controller{
	
	 public function __construct() {
		parent::__construct();
	 }
		
	public function index() {

		header("Pragma: no-cache");
		header("Cache-Control: no-cache");
		header("Expires: 0");
			
			$response = array();
			    $data = array(); 
			   $table = 'dt_users';
			 $getdata = array();
			  $status = FALSE;
			
			 $request = requestJson();

			 $today = date('Y-m-d H:i:s');
			
			
			
			
	/****  check token  start ****/ 
    if( ($_SERVER['REQUEST_METHOD'] == 'POST') ){
			
	
  	 $userid = !empty($request['userid']) ? $request['userid'] : FALSE;
  	 $user_type = !empty($request['user_type']) ? $request['user_type'] : FALSE; 
  	$indays = isset($request['indays']) && !empty($request['indays']) ? $request['indays'] : FALSE; 
  	$amount = isset($request['amount']) && !empty($request['amount']) ? $request['amount'] : FALSE; 
  	$comission = isset($request['comission']) && !empty($request['comission']) ? $request['comission'] : FALSE; 
			
			if(!$userid ){
			$response['status'] = FALSE;
			$response['message'] = 'User ID is Blank!';
			header("Content-Type: application/json");
			echo json_encode($response);
			exit;
			}else if(!$user_type){
			$response['status'] = FALSE;
			$response['message'] = 'User Type is Blank!';
			header("Content-Type: application/json");
			echo json_encode($response);
			exit;
			}

			if($amount && !$indays){
			$response['status'] = FALSE;
			$response['message'] = 'Validity Days are Blank!';
			header("Content-Type: application/json");
			echo json_encode($response);
			exit;
			}
			

  $where['id'] = $userid;
  $where['user_type'] = $user_type;  
  $checkuser = $this->c_model->countitem($table,$where);
   
			
  if( $checkuser == 1 ){
  $userDt = $this->c_model->getSingle($table,$where,'id,uniqueid,parenttype,parentid,fromdate,todate,ownername'); 
  
 $user_uniqueid = $userDt['uniqueid'].'-'.$userDt['ownername'];
 $user_parentid = (int)$userDt['parentid'];
 /**************** fetch Plan Record start ************/
 if(!$amount){
 	$plan = $this->c_model->getSingle('dt_usertype',['type'=>strtoupper($user_type)], 'validity,amount,comision' );
		     $indays = $plan['validity'];
		     $amount = $plan['amount'];
		     $comission = $plan['comision'];
 }

	
			if($amount == 0){
			$response['status'] = FALSE;
		
			$response['message'] = 'No Plan Available!';
			header("Content-Type: application/json");
			echo json_encode($response);
			exit;
			}else if($indays == 0){
			   
			$response['status'] = FALSE;
			$response['message'] = 'No Plan Available!';
			header("Content-Type: application/json");
			echo json_encode($response);
			exit;
			}



 /*calculate Previous days*/
 	$calculatedays = 0;
	if(!empty($userDt['fromdate']) && !empty($userDt['todate']) ){
	$calculatedays = $this->dateDiff($today, $userDt['todate']);
	$indays = (int)( $indays + $calculatedays ); 
	}

 /*calculate Previous days*/
   
$save['fromdate'] = date('Y-m-d H:i:s');
$save['todate'] = date('Y-m-d H:i:s',strtotime( $today.' + '.$indays.' days ' ));


$orderid = 'PSC'.date('YmdHis').rand(100,999);
$com_order = 'PSC'.filter_var($orderid,FILTER_SANITIZE_NUMBER_INT );

		/*Deduct Amount From User main Wallet*/  
        $wtsave['userid'] = $userid;
        $wtsave['usertype'] = $user_type; 
        $wtsave['uniqueid'] = $userDt['uniqueid'];
        $wtsave['paymode'] = 'wallet';
        $wtsave['transctionid'] = 'NA';
        $wtsave['credit_debit'] = 'debit';
        $wtsave['upiid'] = NULL;
        $wtsave['bankname'] = NULL; 
        $wtsave['remark'] = 'Plan Subscription Charges'; 
        $wtsave['status'] = 'success'; 
        $wtsave['amount'] = (float)($amount); 
        $wtsave['subject'] = 'plan_sub';
        $wtsave['addby'] = $userid;
        $wtsave['orderid'] = $orderid;
        $wallet_apiurl = base_url('webapi/wallet/Creditdebit');
        $upwtwhere['dts'] = $wtsave;  
        $header = array('auth: Access-Token='.WALLETOKEN ); 
        $upwt = ($amount > 0) ? curlApis($wallet_apiurl,'POST', $upwtwhere,$header ):array();

        if(!$upwt['status']){
        	$response['status'] = FALSE;
        	$response['message'] = $upwt['message'];
        	header("Content-Type:application/json");
			echo json_encode( $response );
			exit;
        }

        /*Update Plan Data In User tupple*/
        $update = $this->c_model->saveupdate($table,$save,null,['id'=>$userid]);


/************give Comission to Parent Start  ***********************/
$is_deduct = false;
$parentdays = 0;
        if($update && !empty($comission) && ( $user_parentid != 1 ) ){

	$prwhere['id'] = $userDt['parentid'];
	$prwhere['user_type'] = $userDt['parenttype'];
	$prwhere['status'] = 'yes';
	$parentDt = $this->c_model->getSingle($table,$prwhere,'id,uniqueid,fromdate,todate');
 
	/*calculate parent Validity days*/
 	$parentdays = 0;
	if(!empty($parentDt['fromdate']) && !empty($parentDt['todate']) ){
	$parentdays = (int) $this->dateDiff( $today , $parentDt['todate']); 
	}

	//echo $parentdays; exit;

		$wtsave = [];
        $wtsave['userid'] = $parentDt['id'];
        $wtsave['usertype'] = $userDt['parenttype'];
        $wtsave['uniqueid'] = $parentDt['uniqueid'];
        $wtsave['paymode'] = 'wallet';
        $wtsave['transctionid'] = 'NA';
        $wtsave['credit_debit'] = 'credit';
        $wtsave['upiid'] = NULL;
        $wtsave['bankname'] = NULL; 
        $wtsave['remark'] = 'Plan Subscription Commission for User ID: '. $user_uniqueid; 
        $wtsave['status'] = 'success'; 
        $wtsave['amount'] = (float)($comission); 
        $wtsave['subject'] = 'plan_sub_com';
        $wtsave['addby'] = $parentDt['id'];
        $wtsave['orderid'] = $com_order;
        $wallet_apiurl = base_url('webapi/wallet/Creditdebit');
        $upwtwhere['dts'] = $wtsave;  
        $header = array('auth: Access-Token='.WALLETOKEN ); 
        
        if($parentdays > 0){
        	$upwt = ($comission > 0) ? curlApis($wallet_apiurl,'POST', $upwtwhere,$header ):array(); 
        	$is_deduct = true;
        }
        

        }

/************ give Comission to Parent end  ***********************/

  /*give Comission to Admin Start  */
  if($update){
  	$adwhere['id'] = 1;
	$adwhere['user_type'] = 'Admin';
	$adminDt = $this->c_model->getSingle($table,$adwhere,'id,uniqueid,fromdate,todate');
      
		if( $is_deduct ) {
			$addamount = ($amount - $comission) ;
		}else{
			$addamount = $amount;
		} 

		$wtsave = [];
        /*Give Commission To parent Wallet*/  
        $wtsave['userid'] = 1;
        $wtsave['usertype'] = 'Admin';
        $wtsave['uniqueid'] = $adminDt['uniqueid'];
        $wtsave['paymode'] = 'wallet';
        $wtsave['transctionid'] = 'NA';
        $wtsave['credit_debit'] = 'credit';
        $wtsave['upiid'] = NULL;
        $wtsave['bankname'] = NULL; 
        $wtsave['remark'] = 'Plan Subscription Commission for User ID: '. $user_uniqueid; 
        $wtsave['status'] = 'success'; 
        $wtsave['amount'] = (float)($addamount); 
        $wtsave['subject'] = 'plan_sub_com';
        $wtsave['addby'] = 1;
        $wtsave['orderid'] = $com_order;
        $wallet_apiurl = base_url('webapi/wallet/Creditdebit');
        $upwtwhere['dts'] = $wtsave;  
        $header = array('auth: Access-Token='.WALLETOKEN ); 
        $upwt = ($addamount > 0) ? curlApis($wallet_apiurl,'POST', $upwtwhere,$header ):array(); 
       
  }	
		 
	

 $response['status'] = true;
 $response['message'] = "Plan Subscribed Successfully !";




}else{
 $response['status'] = FALSE;
 $response['message'] = "No User Exists!";
}
		 
		
/*token check end*/	
}else{ 
	$response['status'] = FALSE;
    $response['message'] = "Bad Request!";
}
		
	    header("Content-Type:application/json");
		echo json_encode( $response );
		
	
	 }


public function dateDiff($date1, $date2){
	  $date1 =  date('Y-m-d',strtotime( $date1 ));
	  $date2 =  date('Y-m-d',strtotime( $date2 ));
	  $date1_ts = strtotime($date1);
	  $date2_ts = strtotime($date2);
	  $diff = $date2_ts - $date1_ts;
	  return round($diff / 86400);
}



public function plandetails() {

		header("Pragma: no-cache");
		header("Cache-Control: no-cache");
		header("Expires: 0");
			
			$response = array();
			    $data = array(); 
			   $table = 'dt_users';
			 $getdata = array();
			  $status = FALSE;
			
			 $request = requestJson();

	 if( ($_SERVER['REQUEST_METHOD'] == 'POST') ){
			
	
  	 $userid = !empty($request['userid']) ? $request['userid'] : FALSE;
  	 $user_type = !empty($request['user_type']) ? $request['user_type'] : FALSE; 
  	 
			
			if(!$userid ){
			$response['status'] = FALSE;
			$response['message'] = 'User ID is Blank!';
			header("Content-Type: application/json");
			echo json_encode($response);
			exit;
			}else if(!$user_type){
			$response['status'] = FALSE;
			$response['message'] = 'User Type is Blank!';
			header("Content-Type: application/json");
			echo json_encode($response);
			exit;
			}

			$where['id'] = $userid;
			$where['user_type'] = $user_type;
			$dbdata = $this->c_model->getSingle('dt_users',$where, 'fromdate,todate' );


			if(!empty($dbdata)){
			$data = [];
			$data['fromdate'] = date('d-M-Y',strtotime($dbdata['fromdate']));
			$data['todate'] = date('d-M-Y',strtotime($dbdata['todate']));


			 $gt = $this->c_model->getSingle('dt_usertype',['type'=>strtoupper('AGENT')], 'validity,amount,id' );
		     $pld['validity'] = $gt['validity'];
		     $pld['amount'] = $gt['amount'];
		     $pld['id'] = $gt['id'];

		    $active = 'inactive';
		    if(strtotime($dbdata['todate']) > strtotime( date('Y-m-d') ) ){
		    	$active = 'active';
		    }


			$response['status'] = true;
			$response['currentplan'] = $active;
			$response['data'] = $data;
			$response['plandetails'] = $pld;
			$response['message'] = "Success !";  
			}else{
			$response['status'] = FALSE;
			$response['message'] = "No User Exists!";
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