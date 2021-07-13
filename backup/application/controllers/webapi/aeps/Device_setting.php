<?php defined('BASEPATH') OR exit('No direct script access allowed');



class Device_setting extends CI_Controller{
     
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

		if( ($_SERVER['REQUEST_METHOD'] == 'POST') ){

		$userid = isset($request['userid'])?$request['userid']:null; 
		$user_type = isset($request['user_type'])?$request['user_type']:null;
		$devicename = isset($request['devicename'])?$request['devicename']:null;

			if( !$userid ){
				$response['status'] = FALSE;
				$response['message'] = 'User Id is blank!';
				header("Content-Type: application/json");
				echo json_encode( $response );
				exit;
			}else if( !$user_type ){
				$response['status'] = FALSE;
				$response['message'] = 'User type is blank!';
				header("Content-Type: application/json");
				echo json_encode( $response );
				exit;
			}else if( !$devicename ){
				$response['status'] = FALSE;
				$response['message'] = 'Devicename is blank!';
				header("Content-Type: application/json");
				echo json_encode( $response );
				exit;
			}



			$where['id'] = $userid;
			$where['user_type'] = $user_type; 

			$countitem = $this->c_model->countitem('dt_users',$where);

			if( $countitem != 1 ){
				$response['status'] = FALSE;
				$response['message'] = 'User not exists!';
				header("Content-Type: application/json");
				echo json_encode( $response );
				exit;
			} 
 
		$save['devicename'] = $devicename;
        $update = $this->c_model->saveupdate('dt_users', $save , null , $where );




	$response['status'] = FALSE; 
	$response['message']= 'Device set successfully!';



		}else{ 
		$response['status'] = FALSE;
		$response['message']= 'Bad request!'; 
		}

   		//header("Content-Type: application/json");
		echo json_encode( $response );


	}

}
?>