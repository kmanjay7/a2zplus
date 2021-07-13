<?php defined("BASEPATH") OR exit("No Direct Access Allowed!");

class Change_mobile extends CI_Controller{
	var $token ;
    public function __construct(){
	parent::__construct(); 
	$this->token = INSTANTPAY_TOKEN;  
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
				$new_mobile = isset($request['new_mobile'])?$request['new_mobile']:null;
				$old_mobile = isset($request['old_mobile'])?$request['old_mobile']:null; 
				  
            
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
			}else if( (strlen($new_mobile) != 10)  && !$new_mobile ){
				$response['status'] = FALSE;
				$response['message'] = 'Please enter 10 digit new_mobile number!';
				header("Content-Type: application/json");
				echo json_encode( $response );
				exit;
			}else if( (strlen($old_mobile) != 10)  && !$old_mobile ){
				$response['status'] = FALSE;
				$response['message'] = 'Please enter 10 digit new_mobile number!';
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

			 

				   
				   

$posturl = "https://www.instantpay.in/ws/outlet/change_mobile"; 

$post_data['token'] = $this->token;
$post_data['request']['old_mobile'] = $old_mobile;
$post_data['request']['new_mobile'] = $new_mobile;

$post_data = json_encode($post_data, JSON_UNESCAPED_SLASHES);
 
$ch = curl_init($posturl);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json",
    "Accept: application/json"));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
$json = curl_exec($ch);

$buffer = json_decode($json,TRUE);
//print_r($buffer);


				if(!empty($buffer) && isset($buffer['statuscode']) && ($buffer['statuscode']=='TXN')){ 
					$up['old_aeps'] = $old_mobile;
					$up['aeps'] = $new_mobile;
					$this->c_model->saveupdate('dt_users',$up,null,$where ); 
					
					$response['status']= TRUE; 
					$response['data'] = $buffer;
					$response['message'] = $buffer['status'];
				}else if( ($buffer['statuscode']=='ERR') && $buffer['status']){
					$response['status']= FALSE;
					$response['message']= $buffer['status'];
				}else{
					$response['status']= FALSE;
					$response['message']= 'No match found!';
				}

			
}else{ 
	$response['status'] = FALSE;
	$response['message']= 'Bad request!'; 
}

   		header("Content-Type: application/json");
		echo json_encode( $response );
}

 
 
}
?>