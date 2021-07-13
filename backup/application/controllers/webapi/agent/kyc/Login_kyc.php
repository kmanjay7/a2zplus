<?php defined("BASEPATH") OR exit("No Direct Access Allowed!");

class Login_kyc extends CI_Controller{
	
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
			
			
		$uniqueid 	= !empty($request['mobileno']) ? trim($request['mobileno']) : '';
		$uniqueid 	= filter_var( $uniqueid,FILTER_SANITIZE_NUMBER_INT );
		$user_type 	= 'AGENT';
		$password 	= !empty($request['password']) ? trim($request['password']) : '';
		$firebaseid = !empty($request['firebaseid']) ? trim($request['firebaseid']) : '';
		$imeidevice = !empty($request['imeidevice']) ? trim($request['imeidevice']) : '';


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
		}else if(!$password ){
			$response['status']  = false;
			$response['message'] = 'Password is blank!';
			header('Content-Type:application/json');
			echo json_encode( $response );
			exit;
		}else if(!$firebaseid){
			$response['status'] = FALSE;
			$response['message'] = 'Please enter firebase id!';
			header("Content-Type: application/json");
			echo json_encode($response);
			exit;
		 }else if(!$imeidevice){
			$response['status'] = FALSE;
			$response['message'] = 'Please enter device id!';
			header("Content-Type: application/json");
			echo json_encode($response);
			exit;
		 }  


		/*block numbers check start */
		$checkwhere['mobileno'] = $uniqueid;
		$blocked = $this->c_model->countitem('dt_blocked',['mobileno'=>$uniqueid] );
		if( $blocked ){
			$response['status'] = FALSE;
			$response['message'] = 'Your account has been blocked by Administrator!';
			header("Content-Type: application/json");
			echo json_encode($response);
			exit;
		}
		/*block numbers check end */



		 $otp = generateOtp();
		 $otp_time = date('Y-m-d H:i:s');
		 $update = false;
		 $querystatus = false; 
		 $dbarray = array();
		 
		
		
	     $checkuser['uniqueid'] 	= $uniqueid;
	     $checkuser['user_type'] 	= $user_type;
	     $checkuser['en_password'] 	= md5($password);  
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
		}else if( in_array($row['kyc_status'],['reject','no']) ){
			$response['status'] = FALSE;
			$response['message'] = $row['comment'].'. Contact to your parent user!';
			header("Content-Type: application/json");
			echo json_encode($response);
			exit;
	    } 

 
		   
			$post['otp'] = $otp; 
			$post['otp_time'] = $otp_time;
			$post['firebaseid'] = $firebaseid;
			if( $row['status'] == 'yes' ){
			$post['imeidevice'] = $imeidevice;
			}
			$post['loginstatus'] = 'no'; 

			$mobileno = !empty($row['mobileno']) ? $row['mobileno'] : $uniqueid;

			$where['id'] = $row['id'];
			$update = $this->c_model->saveupdate( $table, $post, null , $where );
		  
$msgbody = 'Dear '.strtoupper($row['ownername']).', Your OTP to login your DigiCash India Account is - '.$otp.'. Never share your Login Password or OTP with anyone.
Regards,
DigiCash India.'; 
		   
			$sendsms = simplesms($mobileno,$msgbody);				 
			$response['status'] = TRUE;
			$response['data'] = ['action'=>'otp_verification'];
			$response['message'] = 'OTP send successfully at your registered mobile no.!';
			header("Content-Type: application/json");
			echo json_encode($response); 
	
}

		
}
?>