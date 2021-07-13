<?php defined("BASEPATH") OR exit("No Direct Access Allowed!");

class Registration extends CI_Controller{
	var $token ;
    public function __construct(){
	parent::__construct(); 
	$this->token = INSTANTPAY_TOKEN;  
	}
		
public function index(){

		header("Pragma: no-cache");
		header("Cache-Control: no-cache");
		header("Expires: 0");

		$response = array();
		$data = array();
		$request = requestJson();

if( ($_SERVER['REQUEST_METHOD'] == 'POST') ){

				 
				$userid = isset($request['userid'])?$request['userid']:null; 
				$user_type = isset($request['user_type'])?$request['user_type']:null;
				$mobile = isset($request['uniqueid'])?$request['uniqueid']:null;
				$email = isset($request['email'])?$request['email']:null;
				$company = isset($request['company'])?$request['company']:null;
				$name = isset($request['name'])?$request['name']:null;
				$pan = isset($request['pan'])?$request['pan']:null;
				$pincode = isset($request['pincode'])?$request['pincode']:null;
				$address = isset($request['address'])?$request['address']:null;
				$otp = isset($request['otp'])?$request['otp']:null;  
            
			if( !$userid ){
				$response['status'] = FALSE;
				$response['message'] = 'User Id is blank!';
				header("Content-Type: application/json");
				echo json_encode( $response );
				exit;
			}else if( !$user_type ){
				$response['status'] = FALSE;
				$response['message'] = 'User type is blank!';
				header("Content-Type: application/json");
				echo json_encode( $response );
				exit;
			}else if( (strlen($mobile) != 10)  && !$mobile ){
				$response['status'] = FALSE;
				$response['message'] = 'Please enter 10 digit uniqueid number!';
				header("Content-Type: application/json");
				echo json_encode( $response );
				exit;
			}else if( !filter_var($email,FILTER_VALIDATE_EMAIL)){
				$response['status'] = FALSE;
				$response['message'] = 'Email id not valid/blank!';
				header("Content-Type: application/json");
				echo json_encode( $response );
				exit;
			}else if( !$company){
				$response['status'] = FALSE;
				$response['message'] = 'Company name is blank!';
				header("Content-Type: application/json");
				echo json_encode( $response );
				exit;
			}else if( !$name){
				$response['status'] = FALSE;
				$response['message'] = 'Name is blank!';
				header("Content-Type: application/json");
				echo json_encode( $response );
				exit;
			}else if( !$pan && ( strlen($pan) != 10 ) ){
				$response['status'] = FALSE;
				$response['message'] = 'Enter valid pan number!';
				header("Content-Type: application/json");
				echo json_encode( $response );
				exit;
			}else if( !$pincode && ( strlen($pincode) != 6 ) ){
				$response['status'] = FALSE;
				$response['message'] = 'Enter 6 digit Pincode!';
				header("Content-Type: application/json");
				echo json_encode( $response );
				exit;
			}else if( !$address ){
				$response['status'] = FALSE;
				$response['message'] = 'Address is blank!';
				header("Content-Type: application/json");
				echo json_encode( $response );
				exit;
			}else if( !$otp ){
				$response['status'] = FALSE;
				$response['message'] = 'OTP is blank!';
				header("Content-Type: application/json");
				echo json_encode( $response );
				exit;
			}


			$where['uniqueid'] = $mobile;
			$where['id'] = $userid;
			$where['user_type'] = $user_type;

			$countitem = 1;//$this->c_model->countitem('dt_users',$where);

			if( $countitem != 1 ){
				$response['status'] = FALSE;
				$response['message'] = 'User not exists!';
				header("Content-Type: application/json");
				echo json_encode( $response );
				exit;
			} 


				   
				   

$posturl = "https://www.instantpay.in/ws/outlet/registration"; 

$post_data['token'] = $this->token;
$post_data['request']['mobile'] = $mobile;
$post_data['request']['email'] = $email;
$post_data['request']['company'] = $company;
$post_data['request']['name'] = $name;
$post_data['request']['pan'] = $pan;
$post_data['request']['pincode'] = $pincode; 
$post_data['request']['address'] = $address;
$post_data['request']['otp'] = $otp; 

$post_data = json_encode($post_data, JSON_UNESCAPED_SLASHES);
 
$ch = curl_init($posturl);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
$buffer_data = curl_exec($ch);
$json = xmltojson($buffer_data);
$buffer = json_decode($json,TRUE);
//print_r($buffer);


				if(!empty($buffer) && isset($buffer['statuscode']) && ($buffer['statuscode']=='TXN')){ 
					/* update in db */
					$sav['aeps'] = $buffer['data']['mobile_number'];
					$sav['outlet_id'] = $buffer['data']['outlet_id'];
					$sav['aeps_kyc'] = $buffer['data']['kyc_status'];
					$sav['outlet_status'] = $buffer['data']['outlet_status'];
					$sav['aepspan_no'] = $buffer['data']['pan_no'];
					$sav['aeps_updateon'] = $buffer['timestamp'];
					$userid ? $this->c_model->saveupdate('dt_users',$sav,null,['id'=>$userid]):''; 

$whereupload['usertype'] = $user_type;
$whereupload['tableid'] = $userid; 
$whereupload['documenttype'] = 'Aadhaar Card';

$aadhaar = $this->c_model->getSingle('dt_uploads',$whereupload,'documentorimage');



					$response['status']= TRUE; 
					$response['data'] = $buffer;
					$response['aadhaar'] = $aadhaar ? ADMINURL.'uploads/'.$aadhaar : '';
					$response['message'] = $buffer['status'];
				}else if( ($buffer['statuscode']=='ERR') && $buffer['status']){
					$response['status']= FALSE;
					$response['message']= $buffer['status'];
				}else if( ($buffer['statuscode']=='EOP') && $buffer['status']){
					$response['status']= FALSE;
					$response['message']= $buffer['status'];
				}else{
					$response['status']= FALSE;
					$response['data'] = $buffer;
					$response['message']= 'Something went wrong!';
				}

			
}else{ 
	$response['status'] = FALSE;
	$response['message']= 'Bad request!'; 
}

   		header("Content-Type: application/json");
		echo json_encode( $response );
}

 
 
}
?>