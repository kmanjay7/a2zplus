<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Aadhaar_file_data extends CI_Controller{
	
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
 
		 
    $checkupl['usertableid'] = $tableid;
    $checkupl['usertype'] = $usertype;
    $checkupl['txntype'] = 'aadhaar';
    $fetchdata = $this->c_model->getSingle('dt_kycdata',$checkupl,'*'); 

				  
	
	$data = []; 
	$data['dob'] = $fetchdata['dob'];
	$data['firstname'] = $fetchdata['firstname']; 
	$data['gender'] = (strtolower( trim($fetchdata['gender']) ) == 'm') ? 'Male' : 'Female';
	$data['careof'] = $fetchdata['careof'];
	$data['country'] = $fetchdata['country'];
	$data['dist'] = $fetchdata['dist'];
	$data['house'] = $fetchdata['house'];
	$data['landmark'] = $fetchdata['landmark'];
	$data['location'] = $fetchdata['location'];
	$data['pincode'] = $fetchdata['pincode'];
	$data['postoffice'] = $fetchdata['postoffice'];
	$data['state'] = $fetchdata['state'];
	$data['street'] = $fetchdata['street'];
	$data['subdist'] = $fetchdata['subdist'];
	$data['vtc'] = $fetchdata['vtc'];   
	$photopath = 'uploads/'.$fetchdata['photo'];  
	$data['photo'] = base_url( $photopath ); 

	$address = $data['house'].','.$data['street'].','.$data['location'].','.$data['subdist'].','.$data['dist'].','.$data['pincode'].','.$data['state'].','.$data['country'];
	$address = preg_replace("/,+/", ",", $address);
	$address = preg_replace("/,+/", ", ", $address);
	$address = ltrim($address,',');
	$address = rtrim($address,',');

	$data['address'] = $address;
	    
	    $response['status'] = true;
	    $response['data'] = $data;
		$response['message'] = 'Request was Successfully'; 
		header("Content-Type:application/json");
		echo json_encode( $response );
		exit; 	
		
	}
		
}
?>