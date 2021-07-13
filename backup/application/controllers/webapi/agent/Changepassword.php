<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Changepassword extends CI_Controller{
	
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


$tableid = !empty($request['tableid']) ? trim($request['tableid']) : false;
$uniqueid = !empty($request['uniqueid']) ? trim($request['uniqueid']) : false;
$old_pass = !empty($request['old_pass']) ? trim($request['old_pass']) : false;
$new_pass = !empty($request['new_pass']) ? trim($request['new_pass']) : false;
$cfm_pass = !empty($request['cfm_pass']) ? trim($request['cfm_pass']) : false;



if(!$tableid && !$uniqueid ){
	$response['status'] = FALSE;
	$response['message'] = "User Details are blank";  
	header("Content-Type:application/json");
	echo json_encode( $response );
	exit;
}else if(!$old_pass ){
	$response['status'] = FALSE;
	$response['message'] = "Old Password is blank";  
	header("Content-Type:application/json");
	echo json_encode( $response );
	exit;
}else if(!$new_pass ){
	$response['status'] = FALSE;
	$response['message'] = "New Password is blank";  
	header("Content-Type:application/json");
	echo json_encode( $response );
	exit;
}else if(!$cfm_pass ){
	$response['status'] = FALSE;
	$response['message'] = "Confirm Password is blank";  
	header("Content-Type:application/json");
	echo json_encode( $response );
	exit;
}else if( $cfm_pass != $new_pass ){
	$response['status'] = FALSE;
	$response['message'] = "New Password and Confirm Password are not matched";  
	header("Content-Type:application/json");
	echo json_encode( $response );
	exit;
}
  

$where['id'] = $tableid;
$where['uniqueid'] = $uniqueid;
$where['status'] = 'yes'; 
$where['user_type'] = 'AGENT';
$checkuser = $this->c_model->countitem($table,$where);
  
			if( $checkuser != 1 ){
			    $response['status'] = FALSE;
				$response['message'] = "User not exists";  
				header("Content-Type:application/json");
				echo json_encode( $response );
				exit;
			}	

			
			if( $checkuser == 1 ){

				$up['password'] = $new_pass;
				$up['en_password'] = md5($new_pass);

				$up_where['id'] = $tableid;
				$up_where['uniqueid'] = $uniqueid; 
				$up_where['user_type'] = 'AGENT';
			
			  $update = $this->c_model->saveupdate($table, $up,null, $up_where );
			  
			  if($update){
				$response['status'] = TRUE;
				$response['message'] = "Password Changed Successfully";  
				header("Content-Type:application/json");
				echo json_encode( $response );
				exit;
			  }else{
				$response['status'] = FALSE;
				$response['message'] = "Some error occured";  
				header("Content-Type:application/json");
				echo json_encode( $response );
				exit;
			  }   	 
					
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