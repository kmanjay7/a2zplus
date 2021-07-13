<?php defined("BASEPATH") OR exit("No Direct Access Allowed!");

class Change_password extends CI_Controller{
	
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
		$tableid 	= filter_var( $tableid, FILTER_SANITIZE_NUMBER_INT );
		$uniqueid 	= !empty($request['uniqueid']) ? trim($request['uniqueid']) : '';
		$uniqueid 	= filter_var( $uniqueid, FILTER_SANITIZE_NUMBER_INT );
		$user_type 	= 'AGENT';
		$oldpassword = !empty($request['oldpassword']) ? trim($request['oldpassword']) : '';
		$newpassword = !empty($request['newpassword']) ? trim($request['newpassword']) : '';
		$cfmpassword = !empty($request['cfmpassword']) ? trim($request['cfmpassword']) : '';


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
		}else if(!$oldpassword ){
			$response['status']  = false;
			$response['message'] = 'Please enter old password!';
			header('Content-Type:application/json');
			echo json_encode( $response );
			exit;
		}else if(!$newpassword ){
			$response['status']  = false;
			$response['message'] = 'Please enter new password!';
			header('Content-Type:application/json');
			echo json_encode( $response );
			exit;
		}else if(!$cfmpassword ){
			$response['status']  = false;
			$response['message'] = 'Please enter confirm password!';
			header('Content-Type:application/json');
			echo json_encode( $response );
			exit;
		}else if( $cfmpassword != $newpassword ){
			$response['status']  = false;
			$response['message'] = 'New password and Confirm password did not match!';
			header('Content-Type:application/json');
			echo json_encode( $response );
			exit;
		}
		
		
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


		if( $row['en_password'] != md5($oldpassword) ){
			$response['status'] = FALSE;
			$response['message'] = "Old password didn't match!";
			header("Content-Type: application/json");
			echo json_encode($response);
			exit;
		}

 
		   
			$post['en_password'] = md5($newpassword);
			$post['password'] = $newpassword;
			if($row['kyc_status'] == 'changepassword'){
			$post['kyc_status'] = 'register_pan';
			   /* Change old mobile number with aadhaar regisetred mobile number start */
			   $kyc_where = [];
			   $kyc_where['txntype'] = 'aadhaar';
			   $kyc_where['usertype'] = 'AGENT';
			   $kyc_where['usertableid'] = $row['id'];
			   $kyc_data = $this->c_model->getSingle('dt_kycdata',$kyc_where,'mobile,id ' ); 
			   if(!empty($kyc_data)){
			   $post['uniqueid'] = $kyc_data['mobile'];
			   }
			   /* Change old mobile number with aadhaar regisetred mobile number start */
			}
			$post['loginstatus'] = 'no';
			$post['imeidevice'] = '';
			   
			$where = [];
			$where['id'] = $row['id'];
			$update = $this->c_model->saveupdate( $table, $post, null , $where );
		  
  			 
			$response['status'] = TRUE; 
			$response['message'] = 'Password Successfully Changed!';
			header("Content-Type: application/json");
			echo json_encode($response); 
	
}

		
}
?>