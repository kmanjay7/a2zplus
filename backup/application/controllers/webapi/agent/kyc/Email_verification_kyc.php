<?php defined("BASEPATH") OR exit("No Direct Access Allowed!");

class Email_verification_kyc extends CI_Controller{
	
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
			
			
		$tableid 	= !empty($request['tableid']) ? trim($request['tableid']) : ''; 
		$uniqueid 	= !empty($request['mobileno']) ? trim($request['mobileno']) : '';
		$uniqueid 	= filter_var( $uniqueid, FILTER_SANITIZE_NUMBER_INT );
		$user_type 	= 'AGENT';
		$emailid 	= !empty($request['emailid']) ? trim($request['emailid']) : '';
		$emailid 	= filter_var( $emailid, FILTER_VALIDATE_EMAIL );


		if(!$uniqueid || (strlen($uniqueid) != 10 ) ){
			$response['status']  = false;
			$response['message'] = 'Please enter 10 digit mobile number!';
			header('Content-Type:application/json');
			echo json_encode( $response );
			exit;
		}else if(!$user_type ){
			$response['status']  = false;
			$response['message'] = 'User type is blank!';
			header('Content-Type:application/json');
			echo json_encode( $response );
			exit;
		}else if(!$tableid ){
			$response['status']  = false;
			$response['message'] = 'Tableid is blank!';
			header('Content-Type:application/json');
			echo json_encode( $response );
			exit;
		}else if(!$emailid ){
			$response['status']  = false;
			$response['message'] = 'Please enter valid email address!';
			header('Content-Type:application/json');
			echo json_encode( $response );
			exit;
		}

 

		 $otp = generateOtp();
		 $otp_time = date('Y-m-d H:i:s');
		 $update = false;
		 $querystatus = false; 
		 $dbarray = array();
		 
		
		
	     $checkuser['uniqueid'] 	= $uniqueid;
	     $checkuser['user_type'] 	= $user_type;
	     $checkuser['id'] 			= $tableid;  
		 $countuser = $this->c_model->countitem( $table, $checkuser );


		if( $countuser != 1 ){
			$response['status'] = FALSE;
			$response['message'] = 'Login credentials not matched!';
			header("Content-Type: application/json");
			echo json_encode($response);
			exit;
		}

		 $row = $this->c_model->getSingle( $table, $checkuser, ' * ' ); 

		 
		  		 
		if( $row['status'] != 'yes' ){
			$response['status'] = FALSE;
			$response['message'] = 'Profile is Inactive. Contact to your parent user!';
			header("Content-Type: application/json");
			echo json_encode($response);
			exit;
		}

 
		   
			$post['otp'] = $otp; 
			$post['otp_time'] = $otp_time;
			$post['emailid'] = $emailid;  
			
			$where['id'] = $row['id'];
			$update = $this->c_model->saveupdate( $table, $post, null , $where );
		  
$msgbody = 'Dear '.strtoupper($row['ownername']).', Your email verification OTP to your DigiCash India Account is - '.$otp.'. Never share your Login Password or OTP with anyone.
Regards,
DigiCash India.'; 
		   
			$sendmail = sendmail($emailid,'OTP Verification',$msgbody);				 
			$response['status'] = TRUE;
			$response['data'] = ['kyc_status'=>'email_verification'];
			$response['message'] = 'OTP send successfully at your registered email address.!';
			header("Content-Type: application/json");
			echo json_encode($response); 
	
}

		
}
?>