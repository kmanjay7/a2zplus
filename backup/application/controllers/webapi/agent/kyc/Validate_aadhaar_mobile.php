<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Validate_aadhaar_mobile extends CI_Controller{
	
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
			
	    /****  check Method  start ****/ 
	    if( ($_SERVER['REQUEST_METHOD'] != 'POST') ){ 
			$response['status'] = FALSE;
			$response['message'] = "Bad Request!"; 
			header("Content-Type:application/json");
			echo json_encode( $response );
			exit;
		}	 
		/****  check Method  start ****/ 


		$req = requestJson();

		$tableid = !empty($req['tableid']) ? trim($req['tableid']) : FALSE;
		$uniqueid = !empty($req['uniqueid']) ? trim($req['uniqueid']) : FALSE;
		$usertype = 'AGENT';
		$aadhaarno = !empty($req['aadhaarno']) ? trim($req['aadhaarno']) : FALSE;
		$aadhaarno = filter_var($aadhaarno, FILTER_SANITIZE_NUMBER_INT );
		$is_validaadhaar = $this->aadhaar_validation($aadhaarno);
		$aadhaarmobile = !empty($req['aadhaarmobile']) ? trim($req['aadhaarmobile']) : ''; 
		$aadhaarmobile = filter_var($aadhaarmobile, FILTER_SANITIZE_NUMBER_INT );
		 

		if(!$tableid){
			$response['status'] = FALSE;
			$response['message'] = "User ID is Blank!"; 
			header("Content-Type:application/json");
			echo json_encode( $response );
			exit;
		}else if(!$uniqueid){
			$response['status'] = FALSE;
			$response['message'] = "Unique ID is Blank!"; 
			header("Content-Type:application/json");
			echo json_encode( $response );
			exit;
		}else if(!$usertype){
			$response['status'] = FALSE;
			$response['message'] = "User Type is Blank!"; 
			header("Content-Type:application/json");
			echo json_encode( $response );
			exit;
		}else if(!$is_validaadhaar){
			$response['status'] = FALSE;
			$response['message'] = "Please enter 12 digit valid Aadhaar no!"; 
			header("Content-Type:application/json");
			echo json_encode( $response );
			exit;
		}else if(strlen($aadhaarmobile) != 10 ){
			$response['status'] = FALSE;
			$response['message'] = "Please enter Aadhaar linked mobile number!"; 
			header("Content-Type:application/json");
			echo json_encode( $response );
			exit;
		}


		/*CHECK EXISTING USER RECORD START*/
		$where['id'] = $tableid;
		$where['uniqueid'] = $uniqueid;
		$where['user_type'] = $usertype; 
		$check = $this->c_model->countitem('dt_users',$where);
		/*CHECK EXISTING USER RECORD END*/
		if($check != 1){
			$response['status'] = FALSE;
			$response['message'] = "User not exists!"; 
			header("Content-Type:application/json");
			echo json_encode( $response );
			exit;
		}


	$getdata = $this->c_model->getSingle('dt_users',$where,'id,ownername,passcode');

	$passcode = $getdata['passcode']; 
	
	
	/*check duplicate entry for this aadhaar mobile number start */
	$duplicat = [];
	$duplicat['uniqueid'] = $aadhaarmobile;
	$duplicat['id !='] = $tableid;
	$duplicat['user_type'] = $usertype;
	$countitem = $this->c_model->countitem('dt_users', $duplicat );
	if(!empty($countitem)){
		$response['status'] = FALSE;
		$response['message'] = "This mobile number already regisetred with another account!"; 
		header("Content-Type:application/json");
		echo json_encode( $response );
		exit;
	}
	/*check duplicate entry for this aadhaar mobile number end */
	


	/*get aadhaar file data start here */  
		$checkupl['usertableid'] = $tableid;
		$checkupl['usertype'] = $usertype;
		$checkupl['txntype'] = 'aadhaar';
		$fetchdata = $this->c_model->getSingle('dt_kycdata',$checkupl,'*');
		if(empty($fetchdata)){
			$response['status'] = FALSE;
			$response['message'] = "No KYC Data Available!"; 
			header("Content-Type:application/json");
			echo json_encode( $response );
			exit;
		}else if(empty($fetchdata['landmark'])){
			$response['status'] = FALSE;
			$response['message'] = "No Hash Data Available!"; 
			header("Content-Type:application/json");
			echo json_encode( $response );
			exit;
		}


	/*validate aadhaar Mobile start here */
	$hashval = explodeme($fetchdata['landmark'],'|',0 );
	$is_valid_mob = $this->validateemhash($aadhaarmobile,$passcode,$aadhaarno,$hashval);
	if(!$is_valid_mob){
		$response['status'] = FALSE;
		$response['message'] = "Enter mobile number doesn't match with aadhaar mobile number!"; 
		header("Content-Type:application/json");
		echo json_encode( $response );
		exit;
	}
	/*validate aadhaar Mobile end here */



		$data = []; 
		$dob = $fetchdata['dob'];
		$name = $fetchdata['firstname'];
		$gender = $fetchdata['gender'];
		$gender = (strtolower( trim($gender) ) == 'm') ? 'Male' : 'Female';
		$careof = $fetchdata['careof'];
		$country = $fetchdata['country'];
		$district = $fetchdata['dist'];
		$houseno = $fetchdata['house'];
		$landmark = $fetchdata['landmark'];
		$location = $fetchdata['location'];
		$pincode = $fetchdata['pincode'];
		$postoffice = $fetchdata['postoffice'];
		$state = $fetchdata['state'];
		$street = $fetchdata['street'];
		$subdist = $fetchdata['subdist'];
		$vtc = $fetchdata['vtc'];  
	/*get aadhaar file data end here */


  /* start save record data in kyc table*/
	$sv = [];
	$sv['pan_aadhaar'] = $aadhaarno;
	$sv['mobile'] = $aadhaarmobile;
	$sv['email'] = '';
	$sv['status'] = 'success';
	$sv['add_date'] = date('Y-m-d H:i:s'); 
	$sv['pan_aadhar_status'] = 'pending';  

	$check_kyc_where['id'] = $fetchdata['id'];
    

    $this->c_model->saveupdate('dt_kycdata',$sv,null,$check_kyc_where);
  /*end save record data in kyc table*/


    
    $otp = generateOtp();
	$saverec = [];	 
	
	$saverec['dob'] = date('Y-m-d',strtotime($dob));
	$saverec['pincode'] = $pincode; 
	$address = $houseno.','.$street.','.$location.','.$subdist.','.$district.','.$pincode.','.$state.','.$country;
	$address = preg_replace("/,+/", ",", $address);
	$address = preg_replace("/,+/", ", ", $address);
	$address = ltrim($address,',');
	$address = rtrim($address,',');
	$saverec['address'] =  $address;
	$saverec['ownername'] = ucwords($name);  
	$saverec['kyc_status'] = 'aadhaar_details'; 
	$saverec['aadharno'] = $aadhaarno; 
	$saverec['mobileno'] = $aadhaarmobile;  
	$saverec['otp'] = $otp;
	$saverec['otp_time'] = date('Y-m-d H:i:s');
	$update = $this->c_model->saveupdate($table, $saverec, null, $where );
	
 
 
$msgbody = 'Dear '.strtoupper($getdata['ownername']).', Your OTP to login your DigiCash India Account is - '.$otp.'. Never share your Login Password or OTP with anyone.
Regards,
DigiCash India.';

		$sendsms = simplesms( $aadhaarmobile , $msgbody); 
	    $response['status'] = true;
	    $response['data'] = ['kyc_status'=>'aadhaar_details'];
		$response['message'] = 'OTP sent at your Aadhaar registered Mobile Number'; 
		header("Content-Type:application/json");
		echo json_encode( $response );
		exit; 	
		
	}



public function aadhaar_validation($AadharNo){ 
		$dihedral = array(
		array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9),
		array(1, 2, 3, 4, 0, 6, 7, 8, 9, 5),
		array(2, 3, 4, 0, 1, 7, 8, 9, 5, 6),
		array(3, 4, 0, 1, 2, 8, 9, 5, 6, 7),
		array(4, 0, 1, 2, 3, 9, 5, 6, 7, 8),
		array(5, 9, 8, 7, 6, 0, 4, 3, 2, 1),
		array(6, 5, 9, 8, 7, 1, 0, 4, 3, 2),
		array(7, 6, 5, 9, 8, 2, 1, 0, 4, 3),
		array(8, 7, 6, 5, 9, 3, 2, 1, 0, 4),
		array(9, 8, 7, 6, 5, 4, 3, 2, 1, 0)
		);
		$permutation = array(
		array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9),
		array(1, 5, 7, 6, 2, 8, 3, 0, 9, 4),
		array(5, 8, 0, 3, 7, 9, 6, 1, 4, 2),
		array(8, 9, 1, 6, 0, 4, 3, 5, 2, 7),
		array(9, 4, 5, 3, 1, 2, 6, 8, 7, 0),
		array(4, 2, 8, 6, 5, 7, 3, 9, 0, 1),
		array(2, 7, 9, 3, 8, 0, 6, 4, 1, 5),
		array(7, 0, 4, 6, 9, 1, 3, 2, 5, 8)
		);

		$inverse = array(0, 4, 3, 2, 1, 5, 6, 7, 8, 9);

		settype($AadharNo, "string");
    	$expectedDigit = substr($AadharNo, -1);
    	$partial = substr($AadharNo, 0, -1);

    	settype($partial, "string");
        $partial = strrev($partial);
    	$digitIndex = 0;
	    for ($i = 0; $i < strlen($partial); $i++) {
	        $digitIndex = $dihedral[$digitIndex][$permutation[($i + 1) % 8][$partial[$i]]];
	    }
        
        $actualDigit = $inverse[$digitIndex];

        $valid = ($expectedDigit == $actualDigit) ? $expectedDigit == $actualDigit : 0;
		 
		return $valid ? true : false; 
	}	


private function validateemhash($email_mob,$passcode,$aadhar,$hashval){
  $output = false;
  $last_aadhar = substr($aadhar,-1);
  $hash = $email_mob.$passcode;
  for ($i=1; $i <= $last_aadhar; $i++) {  
      $hash = hash('SHA256', $hash );
  }

    if($hash == $hashval ){
      $output = true;
    }
    
    return  $output;
}

		
}
?>