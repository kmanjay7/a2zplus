<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Upload_aadhaar_file extends CI_Controller{
	
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
		$passcode = !empty($req['passcode']) ? trim($req['passcode']) : FALSE;
		$zipfilename = !empty($_FILES['zipfilename']['name']) ? $_FILES['zipfilename']['name'] : FALSE;

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
		}else if(!$passcode){
			$response['status'] = FALSE;
			$response['message'] = "Passcode is Blank!"; 
			header("Content-Type:application/json");
			echo json_encode( $response );
			exit;
		}else if(!$zipfilename){
			$response['status'] = FALSE;
			$response['message'] = "Choose zip file!"; 
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


		$filepath = 'zipaadhaar/';
		$foldername = 'zipaadhaar';
		$filename = 'zipfilename';
		$target_file = $filepath.$zipfilename;
		$ext = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
		$rawfile = strtolower(pathinfo($target_file,PATHINFO_FILENAME));
		$xmlfile = $rawfile.'.xml';
		$newxmlfile = $tableid.'_'.$uniqueid.'_'.$usertype.'.xml';


		if(!in_array($ext,['zip'])){
			$response['status'] = FALSE;
			$response['message'] = "Only Zip File Allowed!"; 
			header("Content-Type:application/json");
			echo json_encode( $response );
			exit;
		}




	$uploadres = $this->upload_zip($zipfilename,$filepath,$filename);
	$verifyfilename = '';
		if(empty($uploadres['status'])){
			$response['status'] = FALSE;
			$response['message'] = $uploadres['message']; 
			header("Content-Type:application/json");
			echo json_encode( $response );
			exit;
		} 



	$zip_response = $this->unzipme($zipfilename,$foldername,$xmlfile,$passcode,$newxmlfile);

	if(empty($zip_response['status'])){
		$response['status'] = FALSE;
		$response['message'] = $zip_response['message']; 
		header("Content-Type:application/json");
		echo json_encode( $response );
		exit;
	}
	
	$saverec['kyc_status'] = 'aadhaar_details';
	$saverec['passcode'] = $passcode;
	$update = $this->c_model->saveupdate($table, $saverec, null, $where );
	
	$data = []; 
	$data['dob'] = $zip_response['data']['UidData']['Poi']['@attributes']['dob'];
	$data['name'] = $zip_response['data']['UidData']['Poi']['@attributes']['name'];
	$gender = $zip_response['data']['UidData']['Poi']['@attributes']['gender'];
	$data['gender'] = (strtolower( trim($gender) ) == 'm') ? 'Male' : 'Female';
	$data['careof'] = $zip_response['data']['UidData']['Poa']['@attributes']['careof'];
	$data['country'] = $zip_response['data']['UidData']['Poa']['@attributes']['country'];
	$data['dist'] = $zip_response['data']['UidData']['Poa']['@attributes']['dist'];
	$data['house'] = $zip_response['data']['UidData']['Poa']['@attributes']['house'];
	$data['landmark'] = $zip_response['data']['UidData']['Poa']['@attributes']['landmark'];
	$data['location'] = $zip_response['data']['UidData']['Poa']['@attributes']['loc'];
	$data['pincode'] = $zip_response['data']['UidData']['Poa']['@attributes']['pc'];
	$data['postoffice'] = $zip_response['data']['UidData']['Poa']['@attributes']['po'];
	$data['state'] = $zip_response['data']['UidData']['Poa']['@attributes']['state'];
	$data['street'] = $zip_response['data']['UidData']['Poa']['@attributes']['street'];
	$data['subdist'] = $zip_response['data']['UidData']['Poa']['@attributes']['subdist'];
	$data['vtc'] = $zip_response['data']['UidData']['Poa']['@attributes']['vtc'];
	$base64_string = $zip_response['data']['UidData']['Pht'];
	$photoname = $tableid.'_'.$uniqueid.'_AADHAAR_PHOTO.jpeg';
	$photopath = 'zipaadhaar/'.$photoname; 
	$convert   = file_put_contents($photopath,base64_decode($base64_string));
	$data['photo'] = base_url( $photopath );  
	$data['xmlfile'] = $newxmlfile; 

	
	    
	    $response['status'] = true;
	    $response['data'] = $data;
		$response['message'] = 'Request was Successfully'; 
		header("Content-Type:application/json");
		echo json_encode( $response );
		exit; 	
		
	}


public function unzipme($filename,$foldername,$xmlfile,$password,$newxmlfile){  
		$extractpath = $foldername."/";
		$extractfilepath = $extractpath.$newxmlfile; 
 		$output = '';
 		$message = 'Please upload original zip file!'; 
 		$status = false;
                    ## Extract the zip file ---- start
                    $zip = new ZipArchive;
                    $res = $zip->open($extractpath.$filename);
                    if ($res === TRUE) { 
                        if ($zip->setPassword($password)){
                             if (!$zip->extractTo( $extractpath )){
                             $message = "Enter Correct Passcode";  
                             }else if($zip->extractTo($extractpath)){
                                $rename = rename($extractpath.$xmlfile,$extractpath.$newxmlfile); 
								$fileContents = @file_get_contents($extractfilepath);
								
								if($fileContents){
									$json = xmltojson( $fileContents );
									$output = json_decode( $json,true); 
									$status = true;
									$message = 'Extract Successfully.';
								}
								
                             }
                        } 
                        $zip->close();
                    } else {
                        $message = 'Failed to extract.';
                    }
                    ## ---- end
                    
    $response['status'] = $status;
    $response['data'] = $output;
    $response['message'] = $message;
    return $response;
	}

public function upload_zip($file,$folder,$filename){
				$response = [];
				$response['status'] = false;
				$response['newfilename'] = '';
				$response['message'] = '';
				$new_image_name = '';

				if(!is_dir($folder)){ mkdir($folder,0777,true); }  

                $config['upload_path'] = './'.$folder.'/';  
                $config['allowed_types'] = 'zip';  
                $config['overwrite'] = TRUE;  

                $this->load->library('upload', $config);  
                if(!$this->upload->do_upload($filename)){  
                     $message = $this->upload->display_errors();   
                     $response['status'] = false;
                     $response['message'] = strip_tags($message);  
                }else{  
                     $data = $this->upload->data();  
					 $response['status'] = true;
					 $response['newfilename'] = $data["file_name"]; 
					 $response['message'] = 'uploaded'; 
                }  
                
              return $response; 
             
         }	

private function removeatrr($array,$attr,$fieldname){
	$obj = $array[$attr];
	foreach($obj as $key => $values){
        return $obj->$key[$fieldname];
    }
}

		
}
?>