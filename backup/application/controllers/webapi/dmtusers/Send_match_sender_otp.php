<?php defined("BASEPATH") OR exit("No Direct Access Allowed!");

class Send_match_sender_otp extends CI_Controller{
	
public function __construct(){
	parent::__construct();   
	}
		

public function index(){

		header("Pragma: no-cache");
		header("Cache-Control: no-cache");
		header("Expires: 0");

		$response = array();
		$output = array();
		$request = requestJson();
		$table = 'sender';

	if( ($_SERVER['REQUEST_METHOD'] == 'POST') ){

			
			$mobile	= isset($request['mobile'])?trim($request['mobile']):null;
			$mobile = $mobile ? filter_var($mobile,FILTER_SANITIZE_NUMBER_INT) : null;
			$action	= isset($request['action'])?trim($request['action']):null;
			$otp	= isset($request['otp'])?trim($request['otp']):null; 
			 
			$where['mobile'] = trim($mobile); 
			if($action == 'match'){
			$where['otp'] = $otp;
            $where['otpdatetime <='] = date('Y-m-d H:i:s');  
            $where['otpdatetime >='] = date('Y-m-d H:i:s',strtotime( date('Y-m-d H:i:s').'-4 minutes' )); 
            }
	     $checkuser = (strlen($mobile) == 10)?$this->c_model->countitem($table,$where):false;
        
        if( strlen($mobile) != 10 && !is_numeric($mobile)){
			$response['status'] = FALSE;
			$response['message']= 'Please enter 10 digit mobile number!';
        }else if( $checkuser == 1 ){

        	
        	if($action == 'resend'){
          
	        $otp = generateOtp(); 
	        $keys = '*';
	        $checkuser = $this->c_model->getSingle($table,$where,$keys);

	        $save['otp'] = $otp;
	        $save['otpdatetime'] = date('Y-m-d H:i:s'); 
	        $update = $this->c_model->saveupdate($table,$save, null, $where );

	        
	        $agentfirmname = $this->c_model->getSingle('dt_users',array('id'=>$checkuser['add_by']), 'firmname' );
	        $agentfirmname = trim(substr($agentfirmname,0,23));
			$sendername = trim(substr($checkuser['name'],0,29));
$msgstr = "Dear ".strtoupper($sendername).", ".$otp." is your OTP to verify your mobile number at ".strtoupper($agentfirmname)." for Money Transfer.
Regards,
DigiCash India.";
	       

	            if( $update ){ 
	            	$sendsms = simplesms($mobile,$msgstr); 
	            	$response['status'] = TRUE;
				    $response['message'] = 'OTP send successfully at your registered mobile number!';
	            }else{ 
	            	$response['status'] = FALSE;
				    $response['message'] = 'Some error found!';
	            }

            }else if($action == 'match'){


            $save['otpverify'] = 'yes';	  
	        $update = $this->c_model->saveupdate($table,$save,null,$where);
	        
	        if( $update ){

	        $keys = ' * ';
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

				$response['status'] = TRUE;
				$response['data'] = $output;
				$response['message'] = 'OTP matched!';
            }else{
	            $response['status'] = FALSE; 
				$response['message'] = 'OTP expired or not matched!';
            }	


            }


        }else{ 
			$response['status'] = FALSE;
			$response['message'] = 'OTP expired or not matched!';
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