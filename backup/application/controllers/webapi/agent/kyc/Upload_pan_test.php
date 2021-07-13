<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Upload_pan_test extends CI_Controller{
	
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
		//$facefilename = !empty($_FILES['facefilename']['name']) ? $_FILES['facefilename']['name'] : FALSE;
		//$aadharimage = !empty($_FILES['aadharfile']['name']) ? $_FILES['aadharfile']['name'] : FALSE;
		//$geotag = !empty($req['geotag']) ? trim($req['geotag']) : FALSE;

		$image1 = !empty($req['image1']) ? trim($req['image1']) : FALSE;
		$image2 = !empty($req['image2']) ? trim($req['image2']) : FALSE;


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

 


/*use face detection library start */
$digio_facematch_api = DIGIO_URL.'v3/client/kyc/facematch';  

 
$post_data['file1'] 			= '@'.$image1;
$post_data['file2'] 			= '@'.$image2;
//$post_data['minimum_match'] 	= 'NO';
$post_data['unique_request_id'] = 'FM'.date('YmdHis');
$token = DIGIO_CLIENT_ID .':'. DIGIO_SECRET_KEY;
$token = base64_encode( $token );
$header[] = 'authorization: Basic '.$token;
$header[] = 'content-type: Multipart/Form-data';
 

   
	$curl = curl_init(); 
    curl_setopt($curl, CURLOPT_URL, $digio_facematch_api);
	curl_setopt($curl, CURLOPT_HEADER, FALSE);
	curl_setopt($curl, CURLOPT_POST,true);
	curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($post_data) );
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE); 
    curl_setopt($curl, CURLOPT_TIMEOUT, 30);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_MAXREDIRS, 10 );
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);  
	curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
   echo  $jsondata = curl_exec($curl);
    curl_close($curl);

  exit;
	
	 
	$saverec['kyc_status'] = 'onscreening';
	$update = $this->c_model->saveupdate($table, $saverec, null, $where );
	
	
	    $response['status'] = true;
	    $response['data'] = ['kyc_status'=>'onscreening'];
		$response['message'] = 'Request was Successfull'; 
		header("Content-Type:application/json");
		echo json_encode( $response );
		exit;
	}


public function upload_panfile($file,$folder,$filename,$newfile){
				$response = [];
				$response['status'] = false;
				$response['newfilename'] = '';
				$response['message'] = '';
				$new_image_name = '';

				if(!is_dir($folder)){ mkdir($folder,0777,true); }  

                $config['upload_path'] = './'.$folder.'/';  
                $config['allowed_types'] = 'jpg|png';  
                $config['overwrite'] = TRUE;  
                $config['file_name'] = $newfile;  

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
 

}
?>