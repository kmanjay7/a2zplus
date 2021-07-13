<?php defined("BASEPATH") OR exit("No Direct Access Allowed!");

class Register_aacount extends CI_Controller{
	var $agentid ;
	var $token ;
	var $outletid ;
	var $agentmobile ;
    public function __construct(){
	parent::__construct(); 
	  $this->agentid = '94';  //App ID
	  $this->token = INSTANTPAY_TOKEN; 
	  $this->outletid = 14702; 
	  $this->agentmobile = '7651961364';
	}
		
public function index(){

		header("Pragma: no-cache");
		header("Cache-Control: no-cache");
		header("Expires: 0");

		$response = array();
		$data = array();
		$request = requestJson();
		$table = 'dt_aeps_bank_details';

if( ($_SERVER['REQUEST_METHOD'] == 'POST') ){

				 
				$uid = isset($request['uid'])?$request['uid']:null; 
				$utype = isset($request['utype'])?$request['utype']:null;
  
				$bankid = isset($request['bankid'])?$request['bankid']:false;
				$bankid = is_numeric($bankid) ? $bankid :false;
				$accountno = isset($request['accountno'])?$request['accountno']:null;
				$accountno = is_numeric($accountno) ? $accountno :false;
				$ifsccode = isset($request['ifsccode'])?$request['ifsccode']:null; 
				$orderid = 'ACVRI'.date('YmdHis').$uid;
				
				  
            
if( !$uid ){
	$response['status'] = FALSE;
	$response['message'] = 'User Id is blank!';
	header("Content-Type: application/json");
	echo json_encode( $response ); exit;
}else if( !$utype ){
	$response['status'] = FALSE;
	$response['message'] = 'User type is blank!';
	header("Content-Type: application/json");
	echo json_encode( $response ); exit;
}else if( !$bankid ){
	$response['status'] = FALSE;
	$response['message'] = 'bank id is blank!';
	header("Content-Type: application/json");
	echo json_encode( $response ); exit;
}else if( !$accountno ){
	$response['status'] = FALSE;
	$response['message'] = 'Account number is blank!';
	header("Content-Type: application/json");
	echo json_encode( $response ); exit;
}else if( !$ifsccode ){
	$response['status'] = FALSE;
	$response['message'] = 'IFSC Code is blank!';
	header("Content-Type: application/json");
	echo json_encode( $response ); exit;
}


			 
			$where['id'] = $uid;
			$where['user_type'] = $utype; 

			$countitem = $this->c_model->countitem('dt_users',$where);

			if( $countitem != 1 ){
				$response['status'] = FALSE;
				$response['message'] = 'User not exists!';
				header("Content-Type: application/json");
				echo json_encode( $response );
				exit;
			} 


			/*check wallet balance start here */
			$checkwt['userid'] = $uid; 
			$checkwt['user_type'] = $utype; 
			$walletbalance = checkwallet( $checkwt );

			if( $walletbalance < 4 ){
				$response['status'] = FALSE;
				$response['message'] = 'Your Wallet Balance is low for this transaction!';
				header("Content-Type: application/json");
				echo json_encode( $response );
				exit;
			}
 

			/*fetch Account Details from bank start */

			$post['user_id'] = $uid;
			$post['user_type'] = $utype; 
			$count_item = $this->c_model->countitem($table,$post );

			if( $count_item >= 5 ){
				$response['status'] = FALSE;
				$response['message'] = 'Only five accounts can be add!';
				header("Content-Type: application/json");
				echo json_encode( $response );
				exit;
			} 


			$post['user_id'] = $uid;
			$post['user_type'] = $utype;
			$post['bankid'] = $bankid;
			$post['account_no'] = $accountno;
			$post['ifsccode'] = $ifsccode;
			$count_old = $this->c_model->countitem($table,$post );

			if( $count_old ){
				$response['status'] = FALSE;
				$response['message'] = 'Account number already exists!';
				header("Content-Type: application/json");
				echo json_encode( $response );
				exit;
			}  

			$post['account_name'] = '';
			$post['branch'] = '';
			$post['add_date'] = date('Y-m-d H:i:s');
			$post['status'] = 'yes';





			/*fetch Account Details from bank start */
$url = "https://www.instantpay.in/ws/imps/account_validate"; 

$post_data['token'] = $this->token;
$post_data['request']['remittermobile'] = $this->agentmobile;
$post_data['request']['account'] = $accountno;
$post_data['request']['ifsc'] = $ifsccode;
$post_data['request']['agentid'] = $this->agentid;
$post_data['request']['outletid'] = $this->outletid;

$post_data = json_encode($post_data, JSON_UNESCAPED_SLASHES);
$log = $this->pushlog($orderid,'acv','I',$post_data);
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
$buffer = curl_exec($ch);

$json = xmltojson($buffer);
$log = $this->pushlog($orderid,'acv','O',$json);
$buffer_data = json_decode($json,TRUE);
if(!is_null($buffer_data) && !empty($buffer)){

		$account_name = isset($buffer_data['data']['benename'])?$buffer_data['data']['benename']:''; 
		$apistatus = isset($buffer_data['data']['verification_status'])?$buffer_data['data']['verification_status']:'';
		


		if($apistatus == 'VERIFIED'){  
			$post['account_name'] = $account_name; 
	         
	        /**//*deduct amount from wallet start */
$getaddby = $this->c_model->getSingle('dt_users',array('id'=>$uid),'uniqueid,user_type,ownername,firmname');
$in_array = [];
!empty($getaddby['ownername'])?($in_array[] = strtoupper(trim($getaddby['ownername']))):null;
!empty($getaddby['firmname'])?($in_array[] = strtoupper(trim($getaddby['firmname']))):null;

			$save['userid'] = $uid;
			$save['usertype'] = $getaddby['user_type'];
			$save['uniqueid'] = trim($getaddby['uniqueid']);  
			$save['paymode'] = 'wallet';
			$save['transctionid'] = date('YmdHis').$uid;
			$save['credit_debit'] = 'debit'; 
			$save['upiid'] = '';
			$save['bankname'] = ''; 
			$save['remark'] = 'Account Verification AC:'.$accountno;
			$save['status'] = 'success'; 
			$save['amount'] = 4; 
			$save['subject'] = 'account_verify'; 
			$save['addby'] = $uid; 
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
            //if( in_array($account_name,$in_array) ){//comment for mannual check
            if( $upwt['status']){
            	$post['status'] = 'no'; // for mannual approval
            	$update = $this->c_model->saveupdate($table, $post ); 
				$response['status'] = true; 
				$response['data'] = $buffer_data['data']; 
				$response['message'] = 'Account Added Successfully!';
            }else if( !in_array($account_name,$in_array) ){
            	$response['status'] = false; 
	            $response['message']='You can only link Bank Account in the name of "'.strtoupper($getaddby['ownername']).'" OR "'.strtoupper($getaddby['firmname']).'"!';
            }

	       
		}else{
			$response['status'] = false; 
	        $response['message'] = 'Account Verification Failed! Order ID: '.$orderid;
		}
 
}else{ 
	$response['status'] = false; 
    $response['message'] = 'Account Verification Failed/Api Issue! Order ID: '.$orderid;
}
/*fetch Account Details from bank end */

		    

			
}else{ 
	$response['status'] = FALSE;
	$response['message'] = 'Bad request!'; 
}

   		header("Content-Type: application/json");
		echo json_encode( $response );
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