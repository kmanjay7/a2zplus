<?php defined("BASEPATH") OR exit("No Direct Access Allowed!");

class Aadhaar_otp_kyc extends CI_Controller{
	
	public function __construct(){
		parent::__construct();
		}
		
	
	
	public function index(){

		header("Pragma: no-cache");
		header("Cache-Control: no-cache");
		header("Expires: 0");
				
				$response = array();
				    $data = array();  
				   $table = 'dt_users';
				 $request = requestJson();
		
		
		if( ($_SERVER['REQUEST_METHOD'] != 'POST') ){ 
			$response['status']  = false;
			$response['message'] = 'Bad Request!';
			header('Content-Type:application/json');
			echo json_encode( $response );
			exit;
		}
			
			
		$tableid = !empty($request['tableid']) ? trim($request['tableid']) : '';
		$uniqueid = !empty($request['uniqueid']) ? trim($request['uniqueid']) : '';
		$user_type = 'AGENT';
		$action = !empty($request['action']) ? trim($request['action']) : ''; 
		$otp = !empty($request['otp']) ? trim($request['otp']) : ''; 
		
		$uniqueid = filter_var( $uniqueid,FILTER_SANITIZE_NUMBER_INT );

		 if( !$tableid ){
			$response['status'] = FALSE;
			$response['message'] = 'User table id is blank!';
			header("Content-Type: application/json");
		    echo json_encode($response);
		    exit;
		 }else if( strlen($uniqueid) != 10 ){
			$response['status'] = FALSE;
			$response['message'] = 'Please enter valid 10 digit mobile number!';
			header("Content-Type: application/json");
		    echo json_encode($response);
		    exit;
		 }else if(!$action ){
			$response['status'] = FALSE;
			$response['message'] = 'Please enter action!';
			header("Content-Type: application/json");
		    echo json_encode($response);
		    exit;
		 }else if(!$otp && ($action =='match')){
			$response['status'] = FALSE;
			$response['message'] = 'Please enter 4 digit OTP!';
			header("Content-Type: application/json");
		    echo json_encode($response);
		    exit;
		 }





		 $sendotpat = date('Y-m-d H:i:s');
		 $update = false;
		
		
		$where['id']  =  $tableid;
		$where['uniqueid']  =  $uniqueid;
		$where['user_type'] =  'AGENT';
		$checkuser = $this->c_model->countitem($table,$where);

		if( $checkuser != 1 ){
			$response['status']  = FALSE;
			$response['message'] = "User not exists!";
			header("Content-Type: application/json");
			echo json_encode($response);
			exit;
		}
			
			
			
		  
/************fetch user data start script *************/
$getdata = $this->c_model->getSingle('dt_users',$where,'id,ownername,otp,otp_time,mobileno'); 	 	
/************fetch user data end script *************/			    
			          



		if($action == 'resend'){
			$otp = generateOtp();	
            $insert['otp'] = $otp;
            $insert['otp_time'] = $sendotpat; 
	        $update = $this->c_model->saveupdate($table,$insert,null,$where) ;
			
			$mobileno = trim( $getdata['mobileno'] ) ;
$msgbody = 'Dear '.strtoupper($getdata['ownername']).', Your OTP to login your DigiCash India Account is - '.$otp.'. Never share your Login Password or OTP with anyone.
Regards,
DigiCash India.';
			$sendsms = simplesms( $mobileno , $msgbody ); 			
			$response['status'] = TRUE;
			$response['message'] = "OTP send successfully at your aadhaar registered mobile number!";
			header("Content-Type: application/json");
			echo json_encode($response);
			exit; 
		}




		if($action == 'match'){ 
			 $timedeff = gettimedeffrence($getdata['otp_time'],$sendotpat);
			 if( $getdata['otp'] != $otp  ){
				$response['status']  = FALSE;
				$response['message'] = "OTP not matched!";
				header("Content-Type: application/json");
				echo json_encode($response);
				exit;
			 }else if( $timedeff > 4 ){
				$response['status']  = FALSE;
				$response['message'] = "OTP expired!";
				header("Content-Type: application/json");
				echo json_encode($response);
				exit;
			 }  
		}
					
		

		 
		$updatearray = [];
	   	$updatearray['kyc_status'] = 'changepassword'; 
		$update = $this->c_model->saveupdate($table,$updatearray, NULL, $where) ;
									 
		$response['status'] = TRUE;
		$response['data'] = ['kyc_status'=>'changepassword']; 
		$response['message'] = "OTP verification done successfully!"; 
		header("Content-Type: application/json");
		echo json_encode($response);
	
	}
		
}
?>