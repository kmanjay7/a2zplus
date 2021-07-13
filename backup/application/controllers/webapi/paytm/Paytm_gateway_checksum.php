<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Paytm_gateway_checksum extends CI_Controller{
	
	  function __construct() {
         parent::__construct();  
      }


function index(){

 header("Pragma: no-cache");
 header("Cache-Control: no-cache");
 header("Expires: 0");

	$response = array();
	$data = array();
	$orderid = '';
	$request = requestJson(); 
	$today = date('Y-m-d');

        
if( ($_SERVER['REQUEST_METHOD'] == 'POST') ){


/*to down service start script*/
	//$response['status'] = false;
	//$response['message'] = 'Service is Temporarily Down!';
	///header('Content-Type:application/json');
   // echo json_encode($response);
   // exit;
/*to down service start script*/



/****  check post param ****/ 

$user_id = isset($request['user_id']) ? trim($request['user_id']) : false;
$user_type = isset($request['user_type']) ? trim($request['user_type']) : false;
$amount = isset($request['amount']) ? trim($request['amount']) : false; 
$apptype = isset($request['apptype']) ? trim($request['apptype']) : 'web';
$callback = isset($request['callback']) ? trim($request['callback']) : 'web';
$email = isset($request['email']) ? trim($request['email']) : null;
$mobile = isset($request['mobile']) ? trim($request['mobile']) : null; 
$amount = (float)$amount;
$minlimit = (float)'299';
$maxlimit = (float)'10000';
$onl_limit = (float)'25000';

if( !$user_id ){
	$response['status'] = false;
	$response['message'] = 'User Id is blank!';
	header('Content-Type:application/json');
    echo json_encode($response);
    exit;
}else if( !$user_type ){
	$response['status'] = false;
	$response['message'] = 'User type is blank!';
	header('Content-Type:application/json');
    echo json_encode($response);
    exit;
}else if( !$amount ){
	$response['status'] = false;
	$response['message'] = 'Amount is blank!';
	header('Content-Type:application/json');
    echo json_encode($response);
    exit;
}else if( !$apptype ){
	$response['status'] = false;
	$response['message'] = 'App type is blank!';
	header('Content-Type:application/json');
    echo json_encode($response);
    exit;
}






$where['id'] = $user_id;
$where['user_type'] = $user_type; 
$where['status'] = 'yes'; 
$userdata = $this->c_model->getSingle('dt_users',$where,'id,onl_fund,onl_limit,dmt_limit,aeps_limit');
//print_r($userdata);
if( empty($userdata) ){
	$response['status'] = false;
	$response['message'] = 'User not exists in our database!';
	header('Content-Type:application/json');
    echo json_encode($response);
    exit;
}else if(!empty($userdata)){
 $maxlimit = ($userdata['onl_fund']>0)?(float)$userdata['onl_fund']:$maxlimit;
 $onl_limit = ($userdata['onl_limit']>0)?(float)$userdata['onl_limit']:$onl_limit;

 
 $totaltrans = $this->c_model->getSingle('dt_paytmlog',['userid'=>$user_id,'usertype'=>$user_type,'DATE(add_date)'=>$today],'SUM(amount)',null,null,'status','PENDING,SUCCESS');
 $restlimit = ($onl_limit > $totaltrans) ? ((float)$onl_limit - (float)$totaltrans):$onl_limit;
}


/*maintain limit start here*/
if(($totaltrans >= $onl_limit)){
	$response['status'] = false;
	$response['message'] = 'Your Today Transaction Limit is Over!';
	header('Content-Type:application/json');
    echo json_encode($response);
    exit;
}else if(($amount > $restlimit)){
	$response['status'] = false;
	$response['message'] = 'Your Today Available Transaction Limit is: ₹'.$restlimit.' !';
	header('Content-Type:application/json');
    echo json_encode($response);
    exit;
}else if(($amount < $minlimit) || ($amount > $maxlimit) ){
	$response['status'] = false;
	$response['message'] = 'Minimum Amount limit is ₹299 and Maximum Amount limit is ₹'.$maxlimit.' per transaction!';
	header('Content-Type:application/json');
    echo json_encode($response);
    exit;
}
/*maintain limit end here*/
 
$orderid = 'PAYTM'.date('YmdHis').$user_id.rand(10,99);
$save['userid'] = $user_id;
$save['usertype'] = $user_type;
$save['orderid'] = $orderid;
$save['credit_debit'] = 'credit';

$save['amount'] = $amount;
$save['status'] = 'PENDING'; 
$save['add_date'] = date('Y-m-d H:i:s');
$save['nextupdate'] = date('Y-m-d H:i:s',strtotime(date('Y-m-d H:i:s').' +45 seconds'));
$save['noofround'] = 0;

$dupli_check = [];
$dupli_check['orderid'] = $orderid; 

$update = $this->c_model->saveupdate('dt_paytmlog',$save,$dupli_check );

if( !$update ){
	$response['status'] = false;
	$response['message'] = 'Some error Occured!';
	header('Content-Type:application/json');
    echo json_encode($response);
    exit;
}


$data['insertid'] = (string)$update;
$data['orderid'] = $save['orderid'];


/****** generate checksum start here***********/

	require_once(APPPATH . "/third_party/paytmgateway/config_paytm.php");
	require_once(APPPATH . "/third_party/paytmgateway/PaytmChecksum.php");

	$checkSum = "";
	$signature = true;
	$paramList = array();

	$ORDER_ID = '';
	 

	$paramList["MID"] = PAYTM_MERCHANT_MID;
	$paramList["ORDER_ID"] = $save['orderid'];
	$paramList["CUST_ID"] = $user_id;
	$paramList["INDUSTRY_TYPE_ID"] = INDUSTRY_TYPE_ID;
	$paramList["CHANNEL_ID"] = ($apptype=='app') ? CHANNEL_ID_APP : CHANNEL_ID;
	$paramList["TXN_AMOUNT"] = $amount;
	$paramList["WEBSITE"] = ($apptype=='app') ? PAYTM_MERCHANT_WEBSITE_APP : PAYTM_MERCHANT_WEBSITE;
	
	if( $apptype == 'app' ){
	$paramList["MOBILE_NO"] = $mobile;
	$paramList["EMAIL"] = $email;
	$paramList["CALLBACK_URL"] = $callback.$save['orderid'];
	}else{
	$paramList["CALLBACK_URL"] = ADMINURL.'gotopaytm/response';
	}

	$checkSum = PaytmChecksum::generateSignature($paramList, PAYTM_MERCHANT_KEY);
	$signature = PaytmChecksum::verifySignature($paramList, PAYTM_MERCHANT_KEY , $checkSum); 


$data['checkSum'] = $checkSum;
$data['signature'] = $signature;
$data['paramList'] = $paramList;
/****** generate checksum end  here***********/


    $response['status'] = true;
    $response['data'] = $data;
	$response['message'] = 'Request was successfull!';


}else{
	$response['status'] = false;
	$response['message'] = 'Bad Request!';
}

header('Content-Type:application/json');
echo json_encode($response);
} 

}
?>