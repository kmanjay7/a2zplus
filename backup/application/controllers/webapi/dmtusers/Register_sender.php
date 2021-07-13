<?php defined("BASEPATH") OR exit("No Direct Access Allowed!");

class Register_sender extends CI_Controller{
	
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

			$add_by 	= $request['add_by']; 
			$name   	= isset($request['name'])?$request['name']:null;
			$mobile		= isset($request['mobile'])?trim($request['mobile']):null;
			$mobile = $mobile ? filter_var($mobile,FILTER_SANITIZE_NUMBER_INT) : null;
			$pincode  = isset($request['pincode'])?trim($request['pincode']):null;
			$pincode = $pincode ? filter_var($pincode,FILTER_SANITIZE_NUMBER_INT):null;
			$bankname	= isset($request['bankname'])?trim($request['bankname']):null;
			$ac_number	= isset($request['ac_number'])?trim($request['ac_number']):null;
			$ifsc_code	= isset($request['ifsc_code'])?trim($request['ifsc_code']):null;
			


	 $checkaddby = $this->c_model->countitem('users',array('id'=>$add_by));
        
        if( $checkaddby == 1 ){

        	if( strlen($mobile) != 10 && !is_numeric($mobile)){
			$response['status'] = FALSE;
			$response['message'] = 'Please enter 10 digit mobile number!';
            }else{
          
	        $where['mobile'] = trim($mobile); 
	        $checkuser = $this->c_model->countitem($table,$where);

	        $save['name'] = $name;
	        $save['mobile'] = $mobile;
	        $save['pincode'] = $pincode;
	        $save['bankname'] = $bankname;
	        $save['ac_number'] = $ac_number;
	        $save['ifsc_code'] = $ifsc_code;


            if( $checkuser == 1 ){
            	$this->c_model->saveupdate($table,$save,null,array('mobile'=>$mobile));
            	$response['status'] = TRUE;
			    $response['message'] = 'Updation done successfully!';
            }else{
            	$otp = generateOtp(); 
            	$save['otp'] = $otp;
	        	$save['otpdatetime'] = date('Y-m-d H:i:s');
            	$save['add_by'] = $add_by;
            	$save['kyc_status'] = 'pending'; 
            	$save['add_date'] = date('Y-m-d H:i:s'); 
            	$this->c_model->saveupdate($table,$save,null,null);
				/*send otp start */
				 $agentfirmname = $this->c_model->getSingle('dt_users',array('id'=>$add_by), 'firmname' );
				 $agentfirmname = trim(substr($agentfirmname,0,23));
				 $sendername = trim(substr($save['name'],0,29));
$msgstr = "Dear ".strtoupper($sendername).", ".$otp." is your OTP to verify your mobile number at ".strtoupper($agentfirmname)." for Money Transfer.
Regards,
DigiCash India.";
				$sendsms = simplesms($mobile,$msgstr);
				/*send otp end */ 
            	$response['status'] = TRUE;
			    $response['message'] = 'Registration done successfully!';
            }


            }


        }else{ 
			$response['status']= FALSE;
			$response['message']= 'This user not found in our database!';
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