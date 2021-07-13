<?php defined("BASEPATH") OR exit("No Direct Access Allowed!");

class Require_docs extends CI_Controller{
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
				$mobile = isset($request['uniqueid'])?$request['uniqueid']:null;
				$outletid = isset($request['outletid'])?$request['outletid']:null;
				$pan_no = isset($request['pan_no'])?$request['pan_no']:null;
				  
            
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
			}else if( (strlen($mobile) != 10)  && !$mobile ){
				$response['status'] = FALSE;
				$response['message'] = 'Please enter 10 digit uniqueid number!';
				header("Content-Type: application/json");
				echo json_encode( $response );
				exit;
			}else if( !$outletid){
				$response['status'] = FALSE;
				$response['message'] = 'Outletid is blank!';
				header("Content-Type: application/json");
				echo json_encode( $response );
				exit;
			}else if( !$pan_no && ( strlen($pan_no) != 10 ) ){
				$response['status'] = FALSE;
				$response['message'] = 'Enter valid pan number!';
				header("Content-Type: application/json");
				echo json_encode( $response );
				exit;
			}


			$where['uniqueid'] = $mobile;
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


				   
				   

$posturl = "https://www.instantpay.in/ws/outlet/requiredDocs"; 

$post_data['token'] = $this->token;
$post_data['request']['outletid'] = $outletid;
$post_data['request']['pan_no'] = $pan_no;

$post_data = json_encode($post_data, JSON_UNESCAPED_SLASHES);
 
$ch = curl_init($posturl);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json",
    "Accept: application/json"));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
$json = curl_exec($ch);
//$json = xmltojson($buffer_data);
$buffer = json_decode($json,TRUE);
//print_r($buffer);


				if(!empty($buffer) && isset($buffer['statuscode']) && ($buffer['statuscode']=='TXN')){ 
                    
					/*check Approved Document Status*/
					if(!empty($buffer['data']['APPROVED'])){
					$up['aeps_status'] = 'yes'; 
					$this->c_model->saveupdate('dt_users',$up,null,$where );
					} 
					/*check Approved Document Status*/ 

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