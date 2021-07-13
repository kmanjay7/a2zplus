<?php defined("BASEPATH") OR exit("No Direct Access Allowed!");

class Login_new_status extends CI_Controller{
	
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
	 
		
if( ($_SERVER['REQUEST_METHOD'] == 'POST') ){
			
			
$uniqueid = !empty($request['mobileno']) ? trim($request['mobileno']) : '';
$firebaseid = !empty($request['firebaseid']) ? trim($request['firebaseid']) : '';
$imeidevice = !empty($request['imeidevice']) ? trim($request['imeidevice']) : '';
$password = !empty($request['password']) ? trim($request['password']) : '';
		 
		 $otp = generateOtp();
		 $otp_time = date('Y-m-d H:i:s');
		 $update = false;
		 $querystatus = false; 
		 $dbarray = array();

		 /*check valid mobile number*/
		 $uniqueid = filter_var( $uniqueid,FILTER_SANITIZE_NUMBER_INT );
		 if(strlen($uniqueid) != 10){
			$response['status'] = FALSE;
			$response['message'] = 'Please enter valid 10 digit mobile number!';
			header("Content-Type: application/json");
		    echo json_encode($response);
		    exit;
		 }else if(!$password){
			$response['status'] = FALSE;
			$response['message'] = 'Please enter valid password!';
			header("Content-Type: application/json");
			echo json_encode($response);
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
		 }else{

  /* else function start here*/


		/*block numbers check start */
		$blocked = $this->c_model->countitem('dt_blocked',['mobileno'=>$uniqueid] );
		if( $blocked ){
			$response['status'] = FALSE;
			$response['message'] = 'Your account has been blocked by Administrator!';
			header("Content-Type: application/json");
			echo json_encode($response);
			exit;
		}
		/*block numbers check end */
		 
		
		
	     $postarray['uniqueid'] = $uniqueid;
	     $postarray['user_type'] = 'AGENT';
	     $postarray['en_password'] = md5($password); 
		 $countuser = $this->c_model->countitem( $table, $postarray );


		 if( $countuser != 1 ){
			$response['status'] = FALSE;
			$response['message'] = 'Login credentials not matched!';
			header("Content-Type: application/json");
			echo json_encode($response);
			exit;
		}


		 $row = $this->c_model->getSingle( $table, $postarray,' * ' );
		//print_r( $row ); 	
		  		
		/* Check User  start*/
		if( empty($row) ){
			$response['status'] = FALSE;
			$response['message'] = 'Login credentials not matched!';
			header("Content-Type: application/json");
			echo json_encode($response);
			exit;
		}else if( !empty($row) && ($row['status'] !='yes') ){
			$response['status'] = FALSE;
			$response['message'] = 'Profile is Inactive. Contact to your parent user!';
			header("Content-Type: application/json");
			echo json_encode($response);
			exit;
		}else if( !empty($row) && ($row['kyc_status'] =='pending') ){
			$response['status'] = FALSE;
			$response['message'] = 'Your KYC is on Screening';
			header("Content-Type: application/json");
			echo json_encode($response);
			exit;
		}else if( !empty($row) && ($countuser == 1) ){
	 
		   
		   $post['otp'] = $otp; 
		   $post['otp_time'] = $otp_time;
		   $post['firebaseid'] = $firebaseid;
		   $post['imeidevice'] = $imeidevice; 
		   $post['loginstatus'] = 'no';  
		    
		   $querystatus = true ;
	       $mobileno = !empty($row['mobileno']) ? $row['mobileno'] : $uniqueid;
		   
		  $where['id'] = $row['id'];
		  $update = $row['id'] ? $this->c_model->saveupdate( $table, $post, null , $where, null ) : false;
		  
$msgbody = 'Dear '.strtoupper($row['ownername']).', Your OTP to login your DigiCash India Account is - '.$otp.'. Never share your Login Password or OTP with anyone.
Regards,
DigiCash India.'; 

		
		    if( ($querystatus ) && ( $update ) ){
			    $sendsms = simplesms($mobileno,$msgbody);				 
				$response['status'] = TRUE;
				$response['message'] = 'OTP send successfully at your registered mobile no.!';
				header("Content-Type: application/json");
				echo json_encode($response);
			}

		}} /* Else Function End Here*/
			
		
		
		
}else{ 
$response['status'] = FALSE;
$response['message'] = 'Bad Request!';
header("Content-Type: application/json");
echo json_encode($response);
}
		
	
}
		
}
?>