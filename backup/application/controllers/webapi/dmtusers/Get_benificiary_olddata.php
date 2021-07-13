<?php defined("BASEPATH") OR exit("No Direct Access Allowed!");

class Get_benificiary_olddata extends CI_Controller{
	
public function __construct(){
	parent::__construct();   
	}
		

public function index(){

		header("Pragma: no-cache");
		header("Cache-Control: no-cache");
		header("Expires: 0");

		$response = array();
		$data = array();
		$request = requestJson();
		$table = 'dt_benificiary';

	if( ($_SERVER['REQUEST_METHOD'] == 'POST') ){

			
			$bankname = isset($request['bankid'])?trim($request['bankid']):null;
			$ac_number = isset($request['accountno'])?trim($request['accountno']):null;
			 
		$where['bankname'] = $bankname;
		$where['ac_number'] = $ac_number;
		$where['acc_verification'] = 'yes';	 
	    $getdata = $this->c_model->getSingle($table,$where ,'name,ifsc_code,acc_verification',null,1);
        
        if( !is_null( $getdata ) && !empty($getdata) ){ 
        	 
	        $output['name'] 			= trim($getdata['name']); 
	        $output['ifsc_code'] 		= strtoupper($getdata['ifsc_code']); 
	        $output['ac_verify'] 		= strtoupper($getdata['acc_verification']);  


            	$response['status'] = TRUE;
				$response['data'] = $output;
			    $response['message'] = 'Done!'; 

        }else{ 
			$response['status'] = FALSE;
			$response['message']= 'User not found in our database!';
        }
     
        
	}else{ 
		$response['status']= FALSE;
		$response['message']= 'Bad request!'; 
	}

   		header("Content-Type: application/json");
		echo json_encode( $response );
	}
 
}
?>