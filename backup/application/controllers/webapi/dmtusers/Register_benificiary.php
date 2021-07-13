<?php defined("BASEPATH") OR exit("No Direct Access Allowed!");

class Register_benificiary extends CI_Controller{
	
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
		$table = 'benificiary';
		$insert_id = '';

	if( ($_SERVER['REQUEST_METHOD'] == 'POST') ){

			$add_by 	= $request['add_by'];
			$sender_id 	= isset($request['sender_id'])?$request['sender_id']:null;
			$name   	= isset($request['name'])?$request['name']:null;
			$mobile		= isset($request['mobile'])?trim($request['mobile']):null;
			$mobile = $mobile ? filter_var($mobile,FILTER_SANITIZE_NUMBER_INT) : ''; 
			$bankname	= isset($request['bankname'])?trim($request['bankname']):'';
			$ac_number	= isset($request['ac_number'])?trim($request['ac_number']):'';
			$ifsc_code	= isset($request['ifsc_code'])?trim($request['ifsc_code']):'';
			$acc_verification	= isset($request['acc_verification'])?trim($request['acc_verification']):'no';
			


	 $checkaddby = $this->c_model->countitem('users',array('id'=>$add_by));
        
        if( $checkaddby == 1 ){
 
        	if( $add_by && $sender_id && $name && $bankname && $ac_number && $ifsc_code){

	        $where['ac_number'] = trim($ac_number); 
	        $where['sender_id'] = trim($sender_id);
	        $where['status !='] = 'no'; 
	        $checkuser = $this->c_model->countitem($table,$where);
	        $save['name'] = $name;
	        $save['mobile'] = $mobile;
	        $save['sender_id'] = $sender_id;
	        $save['bankname'] = $bankname;
	        $save['ac_number'] = $ac_number;
	        $save['ifsc_code'] = strtoupper($ifsc_code);
	        $save['acc_verification'] = $acc_verification;



	            if( $checkuser == 1 ){
	            	//$this->c_model->saveupdate($table,$save,null,array('mobile'=>$mobile));
	            	//$response['status'] = TRUE;
				   // $response['message'] = 'Updation done successfully!';
	            	$response['status'] = FALSE;
				    $response['message'] = 'This benificiary details already added in our database!';
	            }else{
	            	$save['add_by'] = $add_by; 
	            	$save['add_date'] = date('Y-m-d H:i:s');
	            	$insert_id = $this->c_model->saveupdate($table,$save,null,null);
	            	$response['status'] = TRUE;
	            	$response['tableid'] = $insert_id;
				    $response['message'] = 'Registration done successfully!';
	            }


            }else if( !$add_by ){ 
			$response['status'] = FALSE;
			$response['message'] = 'Please fill add by name!';
            }else if( !$sender_id ){ 
			$response['status'] = FALSE;
			$response['message'] = 'Please fill sender name!';
            }else if( !$name ){ 
			$response['status'] = FALSE;
			$response['message'] = 'Please fill benificiary name!';
            }else if( !$bankname ){ 
			$response['status'] = FALSE;
			$response['message'] = 'Please fill bank name!';
            }else if( !$ac_number ){ 
			$response['status'] = FALSE;
			$response['message'] = 'Please fill account number!';
            }else if( !$ifsc_code ){ 
			$response['status'] = FALSE;
			$response['message'] = 'Please fill account number!';
            }


        }else{ 
			$response['status']= FALSE;
			$response['message']= 'This logged user not found in our database!';
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