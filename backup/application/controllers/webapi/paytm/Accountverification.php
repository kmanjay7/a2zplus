<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Accountverification extends CI_Controller{
	var $agentid ;
	var $agentname ;
	var $agentmobile ;
	var $token ;
	  function __construct() {
         parent::__construct();  
         $this->agentid = '94';  //App ID
         $this->agentname = 'SHAILENDRI VERMA';
         $this->agentmobile = '7651961364';
         $this->token = INSTANTPAY_TOKEN;
      }

public function get_bal($uid,$utype){
		$where['userid'] = $uid;
		$where['usertype'] = $utype; 
		$where['status !='] = 'failed'; 
		$inkey = 'credit_debit';
		$invalue = 'credit,debit';
		$res = $this->c_model->getSingle('wallet' ,$where, 'id,finalamount','id DESC', 1, $inkey, $invalue );
		$amt = !empty($res)?$res['finalamount']: 0;
		return $amt;
	}	



function ac_check(){

 header("Pragma: no-cache");
 header("Cache-Control: no-cache");
 header("Expires: 0");

	$response = array();
	$data = array();
	$request = requestJson('dts');

        
if( ($_SERVER['REQUEST_METHOD'] == 'POST') ){

/****  check token  start ****/ 
if( checktoken( WALLETOKEN ) ){   

$loginuser_id = isset($request['loginuser_id']) ? trim($request['loginuser_id']) : false;
$sender_id = isset($request['sender_id']) ? trim($request['sender_id']) : false;
$benif_id = isset($request['benif_id']) ? trim($request['benif_id']) : false;
$accountno = isset($request['accountno']) ? trim($request['accountno']) : false;
$ifsc_code = isset($request['ifsc_code']) ? trim($request['ifsc_code']) : false;
$remittermobile = $this->agentmobile;
$orderid = 'ACVRI'.date('YmdHis').$benif_id;


/*check wallet balance start here */ 
$wallet = $this->get_bal($loginuser_id,'AGENT');
/*check wallet balance end here */
if( $wallet < 4 ){ 
$response['status'] = false;
$response['message'] = 'Wallet balance is low for this transaction!';
header('Content-Type:application/json');
echo json_encode($response);
exit;
}

$ckwhere['id'] = $sender_id; 
$checkuser = $this->c_model->countitem('dt_sender', $ckwhere );

if( $accountno && $ifsc_code && ( $checkuser == 1 ) && $loginuser_id){ 

$url = "https://www.instantpay.in/ws/imps/account_validate"; 

$post_data['token'] = $this->token;
$post_data['request']['remittermobile'] = $remittermobile;
$post_data['request']['account'] = $accountno;
$post_data['request']['ifsc'] = $ifsc_code;
$post_data['request']['agentid'] = $this->agentid;
$post_data['request']['outletid'] = 14702;

$post_data = json_encode($post_data, JSON_UNESCAPED_SLASHES);
$log = $this->pushlog($orderid,'acv','I',$post_data);
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
$buffer = curl_exec($ch);
//echo $buffer; 

$json = xmltojson($buffer);
$log = $this->pushlog($orderid,'acv','O',$json);
$buffer_data = json_decode($json,TRUE);
if(!is_null($buffer_data) && !empty($buffer)){

		$benifi_name = isset($buffer_data['data']['benename'])?$buffer_data['data']['benename']:'';
		$charged_amt = isset($buffer_data['data']['charged_amt'])?$buffer_data['data']['charged_amt']:'';
		$bankrefno = isset($buffer_data['data']['bankrefno'])?$buffer_data['data']['bankrefno']:'';
		$ipay_id = isset($buffer_data['data']['ipay_id'])?$buffer_data['data']['ipay_id']:'';
		$apistatus = isset($buffer_data['data']['verification_status'])?$buffer_data['data']['verification_status']:'';
		$timestamp = isset($buffer_data['timestamp'])?$buffer_data['timestamp']:'';


		if($apistatus == 'VERIFIED'){

			if($benif_id && $sender_id){
			$where['sender_id'] = $sender_id;
			$where['id'] = $benif_id; 
			$up['acc_verification'] = 'yes';
			$up['verifyon'] = $timestamp;
			$up['name'] = $benifi_name;
	        $update = $this->c_model->saveupdate('benificiary', $up, null, $where); 
	        }
	        /**//*deduct amount from wallet start */
$getaddby = $this->c_model->getSingle('users',array('id'=>$loginuser_id),'uniqueid,user_type');
			$save['userid'] = $loginuser_id;
			$save['usertype'] = $getaddby['user_type'];
			$save['uniqueid'] = trim($getaddby['uniqueid']);  
			$save['paymode'] = 'wallet';
			$save['transctionid'] = $loginuser_id.$accountno.$benif_id;
			$save['credit_debit'] = 'debit';
			$save['upiid'] = '';
			$save['bankname'] = ''; 
			$save['remark'] = 'Account Verification AC:'.$accountno;
			$save['status'] = 'success'; 
			$save['amount'] = (float) 4; 
			$save['subject'] = 'account_verify'; 
			$save['addby'] = $loginuser_id; 
			$save['orderid'] = $orderid;

			$save['odr'] = (float) 4;
			$save['surch'] = '0';
			$save['tds'] = '0';
			$save['comi'] = '0';
			$save['flag'] = 1;


            $walleturl = ADMINURL.('webapi/wallet/Creditdebit');
    		$waltwhere['dts'] = $save;  
    		$headerwt = array('auth: Access-Token='.WALLETOKEN );
    		$upwt = curlApis($walleturl,'POST', $waltwhere,$headerwt ); 
             /**//*deduct amount from wallet end */
	        $response['status'] = true; 
	        $response['data'] = $buffer_data['data']; 
	        $response['message'] = 'Account Verified Successfully!';
		}else{
			$response['status'] = false; 
	        $response['message'] = 'Account Verification Failed!';
		}
 
}else{ 
	$response['status'] = false; 
    $response['message'] = 'Account Verification Failed/Api Issue!';
}


 




	}else if(!$checkuser && $sender_id ){
	    $response['status'] = false;
		$response['message'] = 'User not exists!';
	}else{
	$response['status'] = false;
	$response['message'] = 'Please fill all required fields!';
	}



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

}
?>