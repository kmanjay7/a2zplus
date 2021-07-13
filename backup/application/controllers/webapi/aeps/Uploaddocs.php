<?php defined("BASEPATH") OR exit("No Direct Access Allowed!");

class Uploaddocs extends CI_Controller{
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
			} 

			 
			$where['id'] = $userid;
			$where['user_type'] = $user_type;

			$countitem = $this->c_model->countitem('dt_users',$where);

			if( $countitem != 1 ){
				$response['status'] = FALSE;
				$response['message'] = 'User not exists!';
				header("Content-Type: application/json");
				echo json_encode( $response );
				exit;
			} 

$userdb = $this->c_model->getSingle('dt_users',$where,'outlet_id,aepspan_no,uniqueid');
$outlet_id = $userdb['outlet_id'];
$pan_no = $userdb['aepspan_no'];
$uniqueid = $userdb['uniqueid'];


			$whereupload['usertype'] = $user_type;
			$whereupload['tableid'] = $userid;
			$whereupload['documenttype'] = 'eaadhaar';//'Aadhaar Card';

			$aadhaar = $this->c_model->getSingle('dt_uploads',$whereupload,'documentorimage');

			if( !$aadhaar ){
				$response['status'] = FALSE;
				$response['message'] = 'E-Aadhaar Photo is Missing!';
				header("Content-Type: application/json");
				echo json_encode( $response );
				exit;
			} 

		   

$posturl = "https://www.instantpay.in/ws/outlet/uploadDocs"; 

$post_data['token'] = $this->token;
$post_data['request']['outletid'] = $outlet_id;
$post_data['request']['pan_no'] = $pan_no;
$post_data['request']['document']['id'] = 14;
$post_data['request']['document']['link'] = ADMINURL.'uploads/'.$aadhaar;
$post_data['request']['document']['filename'] = $uniqueid.'_aadhaar.jpeg';
 
$post_data = json_encode($post_data, JSON_UNESCAPED_SLASHES);
 
$ch = curl_init($posturl);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json",
    "Accept: application/json"));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
$json = curl_exec($ch); 
$buffer = json_decode($json,TRUE);
//print_r($buffer);


				if(!empty($buffer) && isset($buffer['statuscode']) && ($buffer['statuscode']=='TXN')){ 
					$this->c_model->saveupdate('dt_users',['aeps_status'=>'pending'],null,$where);
					$response['status']= TRUE; 
					$response['data'] = $buffer;
					$response['message'] = 'Your AEPS activation is in Proccess';
				}else if( ($buffer['statuscode']=='ERR') && ($buffer['status']=='Document Already Approved')){
					$response['status']= TRUE;
					$response['message']= 'gotoaeps';
				}else if( ($buffer['statuscode']=='ERR') && $buffer['status']){
					$response['status']= FALSE;
					$response['message']= $buffer['status'];
				}else{
					$response['status']= FALSE;
					$response['message']= 'AEPS Registration is Pending!';
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