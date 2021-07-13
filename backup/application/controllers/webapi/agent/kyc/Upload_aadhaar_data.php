<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Upload_aadhaar_data extends CI_Controller{
	
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

		$dob = !empty($req['date_of_birth']) ? trim($req['date_of_birth']) : FALSE;
		$date_of_birth = explodeme($dob,'-',2).'-'.explodeme($dob,'-',1).'-'.explodeme($dob,'-',0);
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
		}

		$this->pushlog('KYCAADHAR','kyc'.$uniqueid,'I', json_encode($req) );


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
    $save['add_date'] = date('Y-m-d H:i:s'); 
    $save['usertableid'] = $tableid;
	$save['usertype'] = $usertype;
	$save['txntype'] = 'aadhaar'; 
    }
	$this->c_model->saveupdate('dt_kycdata',$save,null,$where);


	$address = $save['house'].','.$save['street'].','.$save['location'].','.$save['subdist'].','.$save['dist'].','.$save['pincode'].','.$save['state'].','.$save['country'];
	$address = preg_replace("/,+/", ",", $address);
	$address = preg_replace("/,+/", ", ", $address);
	$address = ltrim($address,',');
	$address = rtrim($address,',');


	    $save['gender'] = (strtolower( trim($gender) ) == 'm') ? 'Male' : 'Female';
	    $save['photo'] = base_url( 'uploads/'.$save['photo'] );
	    $save['address'] = $address;
	    $response['status'] = true;
	    $response['data'] = $save;
		$response['message'] = 'Request was Successfully'; 
		header("Content-Type:application/json");
		echo json_encode( $response );
		exit; 	
		
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

}
?>