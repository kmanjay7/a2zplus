<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Update_pan_kyc_by_sdk extends CI_Controller{
	
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

		$date_of_birth = !empty($req['dob']) ? date('Y-m-d',strtotime(str_replace('/', '-', trim($req['dob'])))) : FALSE;
		
		
		$name = !empty($req['name']) ? trim($req['name']) : '';
		$careof = !empty($req['careof']) ? trim($req['careof']) : '';
		$geotag = !empty($req['geotag']) ? trim($req['geotag']) : '';

		$checkupl['usertableid'] = $tableid;
	    $checkupl['usertype'] = $usertype;
	    $checkupl['txntype'] = 'aadhaar';
	    $old_record = $this->c_model->getSingle('dt_kycdata',$checkupl,'*'); 
	    $where = null;
	    if(!empty($old_record) && $old_record['id']){
			$where['id'] = $old_record['id'];

			$date_of_birth = !empty($old_record['dob']) ? date('Y-m-d',strtotime(str_replace('/', '-', trim($old_record['dob'])))) : FALSE;
		
			$name = !empty($old_record['firstname']) ? trim($old_record['firstname']) : '';
			$careof = !empty($old_record['careof']) ? trim($old_record['careof']) : '';
	    }

		// Pan Data
		$pan_name = !empty($req['pan_name']) ? trim($req['pan_name']) : '';
		$fathers_name = !empty($req['fathers_name']) ? trim($req['fathers_name']) : '';
		$pannumber = !empty($req['pan_number']) ? trim($req['pan_number']) : '';
		$pan_dob = !empty($req['pan_dob']) ? date('Y-m-d',strtotime(str_replace('/', '-', trim($req['pan_dob'])))) : '';
		$id_type = !empty($req['id_type']) ? trim($req['id_type']) : '';
		$part = !empty($req['part']) ? trim($req['part']) : '';
		$is_pan_dob_valid = !empty($req['is_pan_dob_valid']) ? trim($req['is_pan_dob_valid']) : '';
		$name_matched = !empty($req['name_matched']) ? trim($req['name_matched']) : '';
		//$panfilename = !empty($_FILES['panfilename']['name']) ? $_FILES['panfilename']['name'] : FALSE;

		$photo = !empty($req['id_front_uri']) ? trim($req['id_front_uri']) : '';

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
		}else if(!$name_matched || !$is_pan_dob_valid){
			$response['status'] = FALSE;
			$response['message'] = "Invalid pan details!"; 
			header("Content-Type:application/json");
			echo json_encode( $response );
			exit;
		}else if(strtolower(trim($name)) != strtolower(trim($pan_name))){
			$response['status'] = FALSE;
			$response['message'] = "Aadhar name & Pan name not matched!"; 
			header("Content-Type:application/json");
			echo json_encode( $response );
			exit;
		}else if(strtotime(trim($date_of_birth)) != strtotime(trim($pan_dob))){
			$response['status'] = FALSE;
			$response['message'] = "Aadhar dob & Pan dob not matched!"; 
			header("Content-Type:application/json");
			echo json_encode( $response );
			exit;
		}else if(!$pannumber || (strlen($pannumber) != 10) ){
			$response['status'] = FALSE;
			$response['message'] = "Enter 10 digit valid pan number!"; 
			header("Content-Type:application/json");
			echo json_encode( $response );
			exit;
		}else if(!$photo){
			$response['status'] = FALSE;
			$response['message'] = "Choose pan image file!"; 
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
 

	// Save Pan Data
	  $nsdlsv = [];
	  $nsdlsv['usertableid'] = $tableid;
	  $nsdlsv['usertype'] = $usertype;
	  $nsdlsv['txntype'] = 'pannsdl'; 
	  $nsdlsv['status'] = 'success';
	  $nsdlsv['add_date'] = date('Y-m-d H:i:s');
	  $nsdlsv['pan_aadhaar'] = $pannumber;
	  $nsdlsv['careof'] = $careof;
	  $nsdlsv['pan_aadhar_status'] = 'VALID';
	  $nsdlsv['firstname'] = !empty($pan_name)?$pan_name:'';
	  $nsdlsv['dob'] = $date_of_birth;
	  $nsdlsv['geotag'] = $geotag;
	  $base64_string = $photo;
	  $photoname = $tableid.'_'.md5($uniqueid).'_PAN_PHOTO.jpg';
	  $photopath = 'uploads/'.$photoname; 
	  $convert   = file_put_contents($photopath,base64_decode($base64_string));
	  $nsdlsv['photo'] =  $photoname ; 

$deletimagepath='';

		$nsdl_kyc_where['usertableid'] = $tableid;
		$nsdl_kyc_where['usertype'] = $usertype;
		$nsdl_kyc_where['txntype'] = 'pannsdl'; 
		$nsdl_hold_data = $this->c_model->getSingle('dt_kycdata',$nsdl_kyc_where,'*');
		$update_nsdl_kyc_where = null;
		if(!empty($nsdl_hold_data)){
		    //delete old record start script 
        	if(!empty($nsdl_hold_data)){ 
        		$deletimagepath = ("uploads/".$nsdl_hold_data['photo']); 
            } 
        	//delete old record end script
		    $update_nsdl_kyc_where['id'] = $nsdl_hold_data['id'];
	    }

		if(empty($nsdl_hold_data))
		{
			$nsdlsv['orderid'] = 'PV'.date('YmdHis').rand(10,99);
		}

	  	$update3=$this->c_model->saveupdate('dt_kycdata', $nsdlsv, null, $update_nsdl_kyc_where );

	  	if($update3 && $deletimagepath && $update_nsdl_kyc_where!=null && !empty($nsdl_hold_data))
	  	{
	  	    if(is_file($deletimagepath) && file_exists($deletimagepath)){ 
    	        $unlink = unlink( $deletimagepath );
    	    }
	  	}

	  	$is_pan_valid = 'VALID';
	  	$nsdl_fullname = strtoupper( $pan_name );


        $userwhere['id'] = $tableid;
		$userwhere['uniqueid'] = $uniqueid;
		$userwhere['user_type'] = $usertype; 

		$saveuser['pancard']=$pannumber;
        $saveuser['kyc_status'] = 'outlet_photo';
        $update4 = $this->c_model->saveupdate($table, $saveuser, null, $userwhere);
        
        if($update3 && $update4)
        {
        	$response['status'] = true;
	        $response['data'] = ['kyc_status' => 'onscreening'];
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

}
?>