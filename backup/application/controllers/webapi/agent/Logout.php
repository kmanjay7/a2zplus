<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Logout extends CI_Controller{
	
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
				
			 $request = requestJson();
			
	   /****  check token  start ****/ 
	   if( ($_SERVER['REQUEST_METHOD'] == 'POST') ){ 
			
	
	        $id = !empty($request['id']) ? $request['id'] : '';			
			 			
	         $where['id'] = $id;
	         $where['status'] = 'yes'; 
			 $checkuser = $this->c_model->countitem($table,$where  );
			
			if( $checkuser == 1 ){
			
			if( $id ){ 
			$post  = array('imeidevice'=>'0','loginstatus'=>'no'); 	
			$updatedata = $this->c_model->saveupdate($table,$post,null,$where) ;
			}
			
			$status = 1;
			
			}else{ $status = 2; }
			 
			
			if($status == 1 ){ 
			$response['status'] = TRUE;
		    $response['message'] = "You logged out successfully!";
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