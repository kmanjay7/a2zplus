<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Forgotpassword extends CI_Controller{
	
	 public function __construct() {
		parent::__construct();
	 }
		
	public function index() {

		header("Pragma: no-cache");
		header("Cache-Control: no-cache");
		header("Expires: 0");
			
			$response = array();
			    $data = array();
			   $table = 'dt_users';
			 $getdata = array(); 
			
			 $request = requestJson();
			
			
			
			
/****  check token  start ****/ 
if( ($_SERVER['REQUEST_METHOD'] == 'POST') ){


$uniqueid = !empty($request['mobileno']) ? trim($request['mobileno']) : '';
  

$where['uniqueid'] = $uniqueid;
$where['status'] = 'yes'; 
$where['user_type'] = 'AGENT'; 
$checkuser = $this->c_model->countitem($table,$where);
  


			
			if( $checkuser == 1 ){
			
			  $getdata = $this->c_model->getSingle($table, $where, 'ownername,password,mobileno' );
			  $password = $getdata['password'];
			  $mobileno = $getdata['mobileno'];
			  $msgbody = 'Dear '.strtoupper($getdata['ownername']).', Your Password to login your DigiCash India Account is - '.$password.'. Never share your Login Password or OTP with anyone.
Regards,
DigiCash India.';  
 		      $send = simplesms( $mobileno , $msgbody );
			
			  $status = 1;
						 
					
			}else{ $status = 2; }
			 

			
			if($status == 1 ){ 
			$response['status'] = TRUE; 
		    $response['message'] = "Password sent successfully at your registered mobile no!";
			}else if($status == 2 ){
			
			$response['status'] = FALSE;
		    $response['message'] = "User not exists!";		
			}
			
		 
		
		/*token check end*/	
		}else{ 
			$response['status'] = FALSE;
		    $response['message'] = "Bad Request!";
	    }
		
	    header("Content-Type:application/json");
		echo json_encode( $response );
		
	
	 }
		
}
?>