<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Upload_blink_face_image extends CI_Controller{
	
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
			   $editedkycstatus = 'blink_face';
			   
			
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
		$facefilename = !empty($_FILES['facefilename']['name']) ? $_FILES['facefilename']['name'] : FALSE;
		$geotag = !empty($req['geotag']) ? trim($req['geotag']) : FALSE;


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
		} else if(!$facefilename){
			$response['status'] = FALSE;
			$response['message'] = "Choose blink face image file!"; 
			header("Content-Type:application/json");
			echo json_encode( $response );
			exit;
		}else if(!$geotag){
			$response['status'] = FALSE;
			$response['message'] = "Geo Tags are blank!"; 
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


		$filepath = 'uploads/';
		$foldername = 'uploads';
		$filename = 'facefilename';
		$target_file = $filepath.$facefilename;
		$ext = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
		$rawfile = strtolower(pathinfo($target_file,PATHINFO_FILENAME)); 
		$newfile = $tableid.'_'.md5($uniqueid).'_BLINK_EYE_FACE.'.$ext;


		if(!in_array($ext,['png','jpg','jpeg'])){
			$response['status'] = FALSE;
			$response['message'] = "Only PNG,JPG,JPEG Files Allowed!"; 
			header("Content-Type:application/json");
			echo json_encode( $response );
			exit;
		}


    /*delete old record start script*/
	$doc_where['tableid'] = $tableid;
	$doc_where['usertype'] = $usertype;
	$doc_where['documenttype'] = 'Applicant Photo'; 
	$doc_olddata = $this->c_model->getSingle('dt_uploads',$doc_where,'documentorimage,id'); 
	if(!empty($doc_olddata)){ 
		$deletimagepath = ("uploads/".$doc_olddata['documentorimage'] );
	    if(is_file($deletimagepath) && file_exists($deletimagepath)){ 
	        $unlink = unlink( $deletimagepath );
	    } 
    $delete = $this->c_model->delete('dt_uploads',$doc_where ) ;
    }
	/*delete old record end script*/


	$uploadres = $this->upload_panfile($facefilename,$filepath,$filename,$newfile);
	$verifyfilename = '';
		if(empty($uploadres['status'])){
			$response['status'] = FALSE;
			$response['message'] = $uploadres['message']; 
			header("Content-Type:application/json");
			echo json_encode( $response );
			exit;
		} 

/*save applicant photo start script */
$userphotoimage = !empty($uploadres['newfilename']) ? $uploadres['newfilename'] :'';
if($userphotoimage){ 
	    $savepn = [];
	    $savepn['documentorimage'] = $userphotoimage;
	    $savepn['uploaddate'] = date('Y-m-d H:i:s');
	    $savepn['add_by'] = $tableid; 
		$savepn['tableid'] = $tableid;
		$savepn['usertype'] = $usertype;
		$savepn['documenttype'] = 'Applicant Photo';
		$savepn['verifystatus'] = 'yes';
		$savepn['status'] = 'yes';
		$savepn['geotag'] = $geotag; 

	$doc_upload_where['tableid'] = $tableid;
	$doc_upload_where['usertype'] = $usertype;
	$doc_upload_where['documenttype'] = 'Applicant Photo'; 
	$doc_upload_data = $this->c_model->getSingle('dt_uploads',$doc_upload_where,'id,documentorimage');
	$check_upload_where = null;
	if(!empty($doc_upload_data)){ $check_upload_where['id'] = $doc_upload_data['id'];
	@unlink( 'uploads/'.$doc_upload_data['documentorimage'] ); } 
	$update = $this->c_model->saveupdate('dt_uploads', $savepn, null, $check_upload_where  );
}
/*save applicant photo end script */

/*fetch aadhaar image from records start script*/ 
	$checkupl['usertableid'] = $tableid;
    $checkupl['usertype'] = $usertype;
    $checkupl['txntype'] = 'aadhaar';
    $aadharphoto_record = $this->c_model->getSingle('dt_kycdata',$checkupl,'id,photo'); 
    $aadharimg = '';
    if(!empty($aadharphoto_record)){
	$aadharimg = $aadharphoto_record['photo'];
    }  
/*fetch aadhaar image from records end script*/


/*use face detection APi start */
$face_match_orderid = 'FCM'.$tableid.'_'.date('YmdHis');
$digio_facematch_api = DIGIO_URL.'v3/client/kyc/facematch';

$post_data = [];
$post_data['file1'] 			= $this->url_get_contents( base_url('uploads/'.$aadharimg ) );
$post_data['file2'] 			= $this->url_get_contents( base_url('uploads/'.$userphotoimage ) );
$post_data['minimum_match'] 	= (int) '60';
$post_data['unique_request_id'] = $face_match_orderid;
$headers = array("Authorization:Basic ".base64_encode(DIGIO_CLIENT_ID.":".DIGIO_SECRET_KEY),"content-type:Multipart/Form-data");
    

	$ch = curl_init(); 
	curl_setopt($ch, CURLOPT_URL, $digio_facematch_api );
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data );  
	curl_setopt($ch, CURLOPT_HEADER ,false);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers ); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
	curl_setopt($ch, CURLOPT_TIMEOUT, 30);
	curl_setopt($ch, CURLOPT_MAXREDIRS, 10 );
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE); 
	$apiresponse = curl_exec($ch);
	curl_close($ch); 
	 

    $apiarray = json_decode($apiresponse,true );
    if(empty($apiarray)){
    	    $response['status'] = FALSE;
			$response['message'] = "Some Technical Issue in Face Matching API!"; 
			header("Content-Type:application/json");
			echo json_encode( $response );
			exit;
    }


/*save  blink face APi response start */
  $sv = [];
  $sv['usertableid'] = $tableid;
  $sv['usertype'] = $usertype;
  $sv['orderid'] = $face_match_orderid;
  $sv['txntype'] = 'blinkface'; 
  $sv['status'] = 'success';
  $sv['add_date'] = date('Y-m-d H:i:s');
  $sv['pan_aadhaar'] = !empty($apiarray['confidence'])?$apiarray['confidence']:'';
  $sv['pan_aadhar_status'] = !empty($apiarray['match_result'])?$apiarray['match_result']:$apiarray['details'];
  $sv['photo'] = $userphotoimage; 
  $sv['landmark'] = !empty($apiarray['message'])?$apiarray['message']:'';
  $sv['geotag'] = $geotag;

	$doc_adh_where['usertableid'] = $tableid;
	$doc_adh_where['usertype'] = $usertype;
	$doc_adh_where['txntype'] = 'blinkface'; 
	$doc_aadholddata = $this->c_model->getSingle('dt_kycdata',$doc_adh_where,'id,usertype,orderid');
	$check_kyc_where = null;
	if(!empty($doc_aadholddata)){ $check_kyc_where['id'] = $doc_aadholddata['id']; }

  $updatepankyc = $this->c_model->saveupdate('dt_kycdata', $sv, null, $check_kyc_where );
/*save  blink face APi response start */


 if(!empty($apiarray['details']) ){ 
    	    $response['status'] = FALSE;
			$response['message'] = $apiarray['message']; 
			header("Content-Type:application/json");
			echo json_encode( $response );
			exit; 
 }


    if(!empty($apiarray['match_result']) && ($apiarray['match_result'] == 'matched') ){ 
    	    
    	$confidence = (int)( str_replace('%','', $apiarray['confidence'] ) );
    	if($confidence < 60 ){
    		$response['status'] = FALSE;
			$response['message'] = 'Face not Matched,confidence : '.$apiarray['confidence'].'%'; 
			header("Content-Type:application/json");
			echo json_encode( $response );
			exit;
    	}else{
    	    $editedkycstatus = 'yes';
    	}

    }else if(!empty($apiarray['match_result']) && ($apiarray['match_result'] == 'not_matched') ){ 

    		$response['status'] = FALSE;
			$response['message'] = 'Face not Matched,confidence : '.$apiarray['confidence'].'%'; 
			header("Content-Type:application/json");
			echo json_encode( $response );
			exit;

    }
/*use face detection APi end */



	
	 
	//$saverec['kyc_status'] = 'onscreening';
	$saverec['kyc_status'] = $editedkycstatus;
	$update = $this->c_model->saveupdate($table, $saverec, null, $where );
	
	
	    $response['status'] = true;
	    $response['data'] = ($editedkycstatus == 'yes') ? ['kyc_status'=>'onscreening' ] : ['kyc_status'=>'blink_face' ] ;
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
                $config['allowed_types'] = 'jpg|png|jpeg';  
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


public function pushlog($odr,$type,$io,$payload){
	$insert = [];
	$insert['odr'] = $odr;
	$insert['type'] = $type;
	$insert['io'] = $io;
	$insert['req_res'] = $payload;
	$insert['timeon'] = date('Y-m-d H:i:s'); 
	return $this->c_model->saveupdate('dt_pushlog',$insert );
}     


function url_get_contents ($Url) {
    if (!function_exists('curl_init')){ 
        die('CURL is not installed!');
    }
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $Url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}    
 

}
?>