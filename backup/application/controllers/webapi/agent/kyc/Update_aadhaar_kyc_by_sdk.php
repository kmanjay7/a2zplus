<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Update_aadhaar_kyc_by_sdk extends CI_Controller{
	
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


		$req = $_POST;


		$tableid = !empty($req['tableid']) ? trim($req['tableid']) : FALSE;
		$uniqueid = !empty($req['uniqueid']) ? trim($req['uniqueid']) : FALSE;
		$usertype = 'AGENT'; 

		$date_of_birth = !empty($req['dob']) ? date('Y-m-d',strtotime(trim($req['dob']))) : FALSE;
		
		$mobile_hash = !empty($req['mobile_hash']) ? trim($req['mobile_hash']) : '';
		$email_hash = !empty($req['email_hash']) ? trim($req['email_hash']) : '';
		$gender = !empty($req['gender']) ? trim($req['gender']) : '';
		$name = !empty($req['name']) ? trim($req['name']) : '';
		$careof = !empty($req['careof']) ? trim($req['careof']) : '';
		$country = !empty($req['country']) ? trim($req['country']) : '';
		$district = !empty($req['district']) ? trim($req['district']) : '';
		$house = !empty($req['house']) ? trim($req['house']) : '';
		$location = !empty($req['location']) ? trim($req['location']) : '';
		$postal_code = !empty($req['postal_code']) ? trim($req['postal_code']) : '';
		$post_office = !empty($req['post_office']) ? trim($req['post_office']) : '';
		$street = !empty($req['street']) ? trim($req['street']) : '';
		$state = !empty($req['state']) ? trim($req['state']) : '';
		$sub_district = !empty($req['sub_district']) ? trim($req['sub_district']) : '';
		$virtual_town_centre = !empty($req['virtual_town_centre']) ? trim($req['virtual_town_centre']) : '';
		$text = !empty($req['text']) ? trim($req['text']) : '';
		$photo = !empty($req['photo']) ? trim($req['photo']) : '';
		$geotag = !empty($req['geotag']) ? trim($req['geotag']) : '';
		$passcode = !empty($req['passcode']) ? trim($req['passcode']) : '';
		$aadhaar_selfi_match = !empty($req['aadhaar_selfi_match']) ? trim($req['aadhaar_selfi_match']) : '';

		$aadhaarno = !empty($req['aadhaarno']) ? trim($req['aadhaarno']) : FALSE;
		$aadhaarno = filter_var($aadhaarno, FILTER_SANITIZE_NUMBER_INT );
		$is_validaadhaar = $this->aadhaar_validation($aadhaarno);
		$aadhaarmobile = !empty($req['aadhaarmobile']) ? trim($req['aadhaarmobile']) : ''; 
		$aadhaarmobile = filter_var($aadhaarmobile, FILTER_SANITIZE_NUMBER_INT );

		$aadhar_selphi_uri = !empty($req['aadhar_selphi_uri']) ? trim($req['aadhar_selphi_uri']) : '';

		$faceMatchPercentage= $this->c_model->getSingle('common_settings',['id'=>1],'*')['aadhaar_selfi_match'];

		$faceMatchPercentage=($faceMatchPercentage>0) ? $faceMatchPercentage : 60; 
		
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
		}else if(!$geotag){
			$response['status'] = FALSE;
			$response['message'] = "Geo Location is blank!"; 
			header("Content-Type:application/json");
			echo json_encode( $response );
			exit;
		}else if(!$date_of_birth){
			$response['status'] = FALSE;
			$response['message'] = "DOB is blank!"; 
			header("Content-Type:application/json");
			echo json_encode( $response );
			exit;
		}else if(!$passcode){
			$response['status'] = FALSE;
			$response['message'] = "Passcode is blank!"; 
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
		}else if($aadhaar_selfi_match<$faceMatchPercentage){
			$response['status'] = FALSE;
			$response['message'] = "Face not matched!"; 
			header("Content-Type:application/json");
			echo json_encode( $response );
			exit;
		}else if(!$aadhar_selphi_uri){
			$response['status'] = FALSE;
			$response['message'] = "Please upload Selfie!"; 
			header("Content-Type:application/json");
			echo json_encode( $response );
			exit;
		}

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

		$landmarkHash=$mobile_hash.'|'.$email_hash; 
		$hashval = explodeme($landmarkHash,'|',0 );
		$is_valid_mob = $this->validateemhash($aadhaarmobile,$passcode,$aadhaarno,$hashval);
		if(!$is_valid_mob){
			$response['status'] = FALSE;
			$response['message'] = "Enter mobile number doesn't match with aadhaar mobile number!"; 
			header("Content-Type:application/json");
			echo json_encode( $response );
			exit;
		}

/*
		$filepath = 'uploads/';
		$foldername = 'uploads';
		$filename = 'panfilename';
		$target_file = $filepath.$panfilename;
		$ext = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
		$rawfile = strtolower(pathinfo($target_file,PATHINFO_FILENAME)); 
		$newfile = $tableid.'_'.$uniqueid.'_'.$usertype.'_PAN.'.$ext;


		if(!in_array($ext,['png','jpg','jpeg'])){
			$response['status'] = FALSE;
			$response['message'] = "Only PNG,JPEG,JPG Files Allowed!"; 
			header("Content-Type:application/json");
			echo json_encode( $response );
			exit;
		}


    //delete old record start script
	$doc_where['tableid'] = $tableid;
	$doc_where['usertype'] = $usertype;
	$doc_where['documenttype'] = 'Pan Card'; 
	$doc_olddata = $this->c_model->getSingle('dt_uploads',$doc_where,'documentorimage,id');
	if(!empty($doc_olddata)){ 
		$deletimagepath = ("uploads/".$doc_olddata['documentorimage'] );
	    if(is_file($deletimagepath) && file_exists($deletimagepath)){ 
	        $unlink = unlink( $deletimagepath );
	    } 
    $delete = $this->c_model->delete('dt_uploads',$doc_where ) ;
    } 
	//delete old record end script
*/
		//$this->pushlog('KYCAADHAR','kyc'.$uniqueid,'I', json_encode($req) );


	
	$saverec['kyc_status'] = 'aadhaar_details'; 
	$saverec['passcode'] = $passcode;
	$update = $this->c_model->saveupdate($table, $saverec, null, $where );
	
	$save = []; 
	$save['dob'] = $date_of_birth;
	$save['firstname'] = strtoupper($name); 
	$save['gender'] = strtolower( trim($gender) ); 
	$save['careof'] = $careof;
	$save['country'] = $country;
	$save['dist'] = $district;
	$save['house'] = $house; 
	$save['location'] = $location;
	$save['pincode'] = $postal_code;
	$save['postoffice'] = $post_office;
	$save['state'] = $state;
	$save['street'] = $street;
	$save['pan_aadhaar']=$aadhaarno;
	$save['subdist'] = $sub_district;
	$save['vtc'] = $virtual_town_centre;
	$save['landmark'] = $mobile_hash.'|'.$email_hash;
	$base64_string = $photo;
	$photoname = $tableid.'_'.md5($uniqueid).'_AADHAAR_PHOTO.jpg';
	$photopath = 'uploads/'.$photoname; 
	$convert   = file_put_contents($photopath,base64_decode($base64_string));
	$save['photo'] =  $photoname ;  
	$save['geotag'] = $geotag;
	


    $checkupl['usertableid'] = $tableid;
    $checkupl['usertype'] = $usertype;
    $checkupl['txntype'] = 'aadhaar';
    $old_record = $this->c_model->getSingle('dt_kycdata',$checkupl,'id,usertableid'); 
    $where = null;
    if(!empty($old_record) && $old_record['id']){
		$where['id'] = $old_record['id'];
    }

    if(empty($old_record)){
    $save['orderid'] = 'PV'.date('YmdHis').rand(10,99);
    $save['usertableid'] = $tableid;
	$save['usertype'] = $usertype;
	$save['txntype'] = 'aadhaar'; 
    }
    $save['add_date'] = date('Y-m-d H:i:s'); 
	$update2=$this->c_model->saveupdate('dt_kycdata',$save,null,$where);



	$address = $save['house'].','.$save['street'].','.$save['location'].','.$save['subdist'].','.$save['dist'].','.$save['pincode'].','.$save['state'].','.$save['country'];
	$address = preg_replace("/,+/", ",", $address);
	$address = preg_replace("/,+/", ", ", $address);
	$address = ltrim($address,',');
	$address = rtrim($address,',');

        $userwhere['id'] = $tableid;
		$userwhere['uniqueid'] = $uniqueid;
		$userwhere['user_type'] = $usertype; 

		$saveuser['ownername']=$name;
		$saveuser['uniqueid']=$aadhaarmobile;
		$saveuser['mobileno']=$aadhaarmobile;
		$saveuser['aadharno']=$aadhaarno;
		$saveuser['pincode']=$postal_code;
		$saveuser['address']=$address;
		$saveuser['dob']=$date_of_birth;
		$saveuser['aadhaar_selfi_match']=$aadhaar_selfi_match;
        $saveuser['kyc_status'] = 'pan_kyc';
        $update3 = $this->c_model->saveupdate($table, $saveuser, null, $userwhere);


     	//delete old record start script
		$doc_where['tableid'] = $tableid;
		$doc_where['usertype'] = $usertype;
		$doc_where['documenttype'] = 'Aadhaar Selfie'; 
		$doc_olddata = $this->c_model->getSingle('dt_uploads',$doc_where,'documentorimage,id');
		if(!empty($doc_olddata)){ 
			$deletimagepath = ("uploads/".$doc_olddata['documentorimage'] );
		    if(is_file($deletimagepath) && file_exists($deletimagepath)){ 
		        $unlink = unlink( $deletimagepath );
		    } 
	    	$delete = $this->c_model->delete('dt_uploads',$doc_where ) ;
	    } 
		//delete old record end script


	    $aadhaarSel=[];
	    $aadhaarSel['tableid'] = $tableid;
		$aadhaarSel['usertype'] = $usertype;
		$base64_stringSel = $aadhar_selphi_uri;
		$photonameSel = $tableid.'_'.md5($uniqueid).'_AADHAAR_SELFIE.jpg';
		$photopathSel = 'uploads/'.$photonameSel; 
		$convert   = file_put_contents($photopathSel,base64_decode($base64_stringSel));
		$aadhaarSel['documentorimage'] = $photonameSel; 
		$aadhaarSel['documenttype'] = 'Aadhaar Selfie';
		$aadhaarSel['verifystatus'] = 'yes';
		$aadhaarSel['status'] = 'yes';
		$aadhaarSel['geotag'] = $geotag; 
		$aadhaarSel['uploaddate	'] = date('Y-m-d H:i:s');
		$update=$this->c_model->saveupdate('dt_uploads', $aadhaarSel, null, null );
        

        $special_msg=($uniqueid==$aadhaarmobile) ? '' : 'Please login with your Aadhaar registered mobile number.';

        if($update && $update2 && $update3)
        {
        	$response['status'] = true;
	        $response['data'] = ['kyc_status' => 'pan_kyc','special_msg' => $special_msg];
	        $response['message'] = 'Request was Successfull';
	        header("Content-Type:application/json");
	        echo json_encode($response);
	        exit;
        }else{
        	$response['status'] = FALSE;
            $response['message'] = "Some Technical Issue, Please try again!";
            header("Content-Type:application/json");
            echo json_encode($response);
            exit;
        }		
	}

public function pushlog($odr,$type,$io,$payload){
	$insert = [];
	$insert['odr'] = $odr;
	$insert['type'] = $type;
	$insert['io'] = $io;
	$insert['req_res'] = $payload;
	$insert['timeon'] = date('Y-m-d H:i:s'); 
	return $this->c_model->saveupdate('dt_pushlog',$insert );
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
	  	  if($last_aadhar>1)
	  	  {
    		  for ($i=1; $i <= $last_aadhar; $i++) {  
    		    	$hash = hash('SHA256', $hash );
    		  }
	  	  }

		  if($last_aadhar==1 || $last_aadhar==0)
		  {
		  		$hash = hash('SHA256', $hash );
		  }
		  
		    if($hash == $hashval){
		      $output = true;
		    }
	    
	    return  $output;
	}

}
?>