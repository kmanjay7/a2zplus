<?php defined("BASEPATH") OR exit("No Direct Access Allowed!");

class Check_sender extends CI_Controller{
	
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
		$table = 'sender';

	if( ($_SERVER['REQUEST_METHOD'] == 'POST') ){

			
			$mobile = isset($request['mobile'])?trim($request['mobile']):null;
			$mobile = $mobile ? filter_var($mobile,FILTER_SANITIZE_NUMBER_INT) : null; 
		$where['mobile'] = $mobile;	 
	    $checkaddby = $this->c_model->countitem($table,$where );
        
        if( strlen($mobile) != 10 && !is_numeric($mobile)){
			$response['status'] = FALSE;
			$response['message']= 'Please enter 10 digit mobile number!';
        }else if( $checkaddby ){ 
        	$keys = '*';
	        $data = $this->c_model->getSingle($table,$where,$keys);	

	        $output['senderid'] 		= $data['id'];
	        $output['add_by'] 			= $data['add_by'];
	        $output['name'] 			= $data['name'];
	        $output['mobile'] 			= $data['mobile'];
	        $output['pincode'] 			= $data['pincode'];
	        $output['bankname'] 		= $data['bankname'];
	        $output['ac_number'] 		= $data['ac_number'];
	        $output['ifsc_code'] 		= $data['ifsc_code'];
	        $output['transfer_limit'] 	= $data['transfer_limit'];
	        $output['kyc_status'] 		= $data['kyc_status'];
	        $output['add_date'] 		= $data['add_date'];
	        $output['otpverify'] 		= $data['otpverify'];

	        /*check otp verification start*/
	        if($data['otpverify']!=='yes'){
 				$otp = generateOtp();
				$save['otp'] = $otp;
				$save['otpdatetime'] = date('Y-m-d H:i:s'); 
				$update = $this->c_model->saveupdate($table,$save, null, $where );
				$agentfirmname = $this->c_model->getSingle('dt_users',array('id'=>$data['add_by']), 'firmname' );
				$agentfirmname = trim(substr($agentfirmname,0,23));
				$sendername = trim(substr($data['name'],0,29));
$msgstr = "Dear ".strtoupper($sendername).", ".$otp." is your OTP to verify your mobile number at ".strtoupper($agentfirmname)." for Money Transfer.
Regards,
DigiCash India.";
				 
				$sendsms = simplesms($mobile,$msgstr); 
	        }
	        /*check otp verification end*/

            	$response['status'] = TRUE;
				$response['data'] = $output;
			    $response['message'] = 'This mobile number registered in our database!'; 

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