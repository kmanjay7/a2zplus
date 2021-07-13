<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Upload_pan extends CI_Controller{
	
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
		$pannumber = !empty($req['pannumber']) ? trim($req['pannumber']) : FALSE;
		$panfilename = !empty($_FILES['panfilename']['name']) ? $_FILES['panfilename']['name'] : FALSE;
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
		}else if(!$pannumber || (strlen($pannumber) != 10) ){
			$response['status'] = FALSE;
			$response['message'] = "Enter 10 digit valid pan number!"; 
			header("Content-Type:application/json");
			echo json_encode( $response );
			exit;
		}else if(!$panfilename){
			$response['status'] = FALSE;
			$response['message'] = "Choose pan image file!"; 
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

		$saverec = [];

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


    /*delete old record start script*/
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
	/*delete old record end script*/


 /*NSDL ______ Is Pan Valid check condition end*/
$orderidNSDL = 'PNSDL'.date('YmdHis').$tableid;
/*Verify Pan details  */
$payload = [];
$payload['token'] = INSTANTPAY_TOKEN;
$payload['request']['pan'] = $pannumber;
$payload['request']['agentid'] = $orderidNSDL;

$post_pushdata = json_encode($payload, JSON_UNESCAPED_SLASHES);

  $curl = curl_init(); 
  curl_setopt_array($curl, array(
  CURLOPT_URL => "https://www.instantpay.in/ws/dataservices/pbv/pan",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => json_encode($payload) ,
  CURLOPT_HTTPHEADER => array(
    "Content-Type: application/json",
    "Accept: application/json"
  ),
));
$jsonnsdl = curl_exec($curl);
curl_close($curl);

$pan_respnsdl = json_decode( $jsonnsdl,true );
//print_r($pan_resp); exit;
$is_pan_valid = '';
if(!empty($pan_respnsdl['statuscode']) && ( $pan_respnsdl['statuscode'] == 'TXN' )){
  $pandata = $pan_respnsdl['data'];
  $namearr = $pan_respnsdl['data']['name'];
  $nsdlsv = [];
  $nsdlsv['usertableid'] = $tableid;
  $nsdlsv['usertype'] = $usertype;
  $nsdlsv['orderid'] = $orderidNSDL;
  $nsdlsv['txntype'] = 'pannsdl'; 
  $nsdlsv['status'] = 'success';
  $nsdlsv['add_date'] = date('Y-m-d H:i:s');
  $nsdlsv['pan_aadhaar'] = $pannumber;
  $nsdlsv['pan_aadhar_status'] = !empty($pandata['pan_status'])?$pandata['pan_status']:'';
  $nsdlsv['firstname'] = !empty($namearr['first'])?$namearr['first']:'';
  $nsdlsv['midname'] = !empty($namearr['middle'])?$namearr['middle']:'';
  $nsdlsv['lastname'] = !empty($namearr['last'])?$namearr['last']:''; 
  $nsdlsv['geotag'] = $geotag;

	$nsdl_kyc_where['usertableid'] = $tableid;
	$nsdl_kyc_where['usertype'] = $usertype;
	$nsdl_kyc_where['txntype'] = 'pannsdl'; 
	$nsdl_hold_data = $this->c_model->getSingle('dt_kycdata',$nsdl_kyc_where,'id,usertype,orderid');
	$update_nsdl_kyc_where = null;
	if(!empty($nsdl_hold_data)){ $update_nsdl_kyc_where['id'] = $nsdl_hold_data['id']; }

  $this->c_model->saveupdate('dt_kycdata', $nsdlsv, null, $update_nsdl_kyc_where );
  $is_pan_valid = !empty($pandata['pan_status'])?$pandata['pan_status']:'';

  $nsdl_fullname = trim( (!empty($nsdlsv['firstname'])?$nsdlsv['firstname'].' ':'').(!empty($nsdlsv['midname'])?$nsdlsv['midname'].' ':'').(!empty($nsdlsv['lastname'])?$nsdlsv['lastname']:'') );
  $nsdl_fullname = strtoupper( $nsdl_fullname );

}

 
 /*Is Pan Valid check condition start*/
 if( !in_array($is_pan_valid, ['VALID']) ){
		$response['status'] = FALSE;
		$response['message'] = 'Invalid Pan Number'; 
		header("Content-Type:application/json");
		echo json_encode( $response );
		exit;
 }
 /*NSDL ______ Is Pan Valid check condition end*/









/*Verify Pan details  */ 
	$uploadres = $this->upload_panfile($panfilename,$filepath,$filename,$newfile);
	$verifyfilename = '';
		if(empty($uploadres['status'])){
			$response['status'] = FALSE;
			$response['message'] = $uploadres['message']; 
			header("Content-Type:application/json");
			echo json_encode( $response );
			exit;
		} 



/*save pan copy start script */
$panimage = !empty($uploadres['newfilename']) ? $uploadres['newfilename'] :'';
if($panimage){ 

	    $savepn['documentorimage'] = $panimage;
	    $savepn['uploaddate'] = date('Y-m-d H:i:s');
	    $savepn['add_by'] = $tableid;
		$savepn['tableid'] = $tableid;
		$savepn['usertype'] = $usertype;
		$savepn['documenttype'] = 'Pan Card';
		$savepn['verifystatus'] = 'yes';
		$savepn['status'] = 'yes';  
		$savepn['geotag'] = $geotag;  

	$doc_upload_where['tableid'] = $tableid;
	$doc_upload_where['usertype'] = $usertype;
	$doc_upload_where['documenttype'] = 'Pan Card'; 
	$doc_upload_data = $this->c_model->getSingle('dt_uploads',$doc_upload_where,'id,documentorimage');
	$check_upload_where = null;
	if(!empty($doc_upload_data)){ $check_upload_where['id'] = $doc_upload_data['id'];
	@unlink( 'uploads/'.$doc_upload_data['documentorimage'] ); }
	$update = $this->c_model->saveupdate('dt_uploads', $savepn,null,$check_upload_where );

}
/*save pan copy start script */
	

/* Start  of the OCR system for the Pan card */
    $orderid = 'PV'.date('YmdHis').$tableid;
	$pantext = '';
	$panImagepath = base_url( 'uploads/'.$panimage ); 
	$post_data = [];
	$post_data['front_part']       = $this->url_get_contents( $panImagepath );  
	$post_data['unique_request_id'] = (string) $orderid;
 
$panurl = DIGIO_URL."v3/client/kyc/analyze/file/idcard"; 
$pkey = DIGIO_CLIENT_ID;
$secret = DIGIO_SECRET_KEY;
$headers = array("Authorization:Basic ".base64_encode($pkey.":".$secret),"content-type:Multipart/Form-data");


  $ch = curl_init(); 
  curl_setopt($ch, CURLOPT_URL,$panurl);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data );  
  curl_setopt($ch, CURLOPT_HEADER ,false);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); 
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_TIMEOUT, 30);
  curl_setopt($ch, CURLOPT_MAXREDIRS, 10 );
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE); 
  $buffer_response = curl_exec($ch); 
  curl_close($ch);
  $getTextFromPan =  json_decode($buffer_response, true );

  if(!empty($getTextFromPan['encoded_signature'])){
   unset($getTextFromPan['encoded_signature']);
  }
  if(!empty($getTextFromPan['encoded_image'])){ 
  	unset($getTextFromPan['encoded_image']);  
  }
 
 
 
 if(isset($getTextFromPan['details']) && !empty($getTextFromPan['code'])){
 	    $response['status'] = FALSE;
		$response['message'] = $getTextFromPan['message']; 
		header("Content-Type:application/json");
		echo json_encode( $response );
		exit;
 }
 
 $pan_data = $this->match_pan_details( $getTextFromPan );

 if(empty($pan_data)){
		$response['status'] = FALSE;
		$response['message'] = 'Please upload valid and clear copy of Pancard'; 
		header("Content-Type:application/json");
		echo json_encode( $response );
		exit;
 }
/* End of the OCR system for the Pan card */

/* MATCH IMAGE RECORD WITH ENTERED PAN DETAILS START */
$img_pannumber = !empty($pan_data['pannumber'])?trim($pan_data['pannumber']):'';
$img_pan_name = !empty($pan_data['panname'])?trim($pan_data['panname']):'';
$img_pan_careof = !empty($pan_data['careof'])?trim($pan_data['careof']):'';
$img_pan_dob = !empty($pan_data['dob'])?str_replace('/', '-', $pan_data['dob']):'';
$img_pan_dob = !empty($img_pan_dob)?date('Y-m-d',strtotime($img_pan_dob)):'';

  $sv = [];
  $sv['usertableid'] = $tableid;
  $sv['usertype'] = $usertype;
  $sv['orderid'] = $orderid;
  $sv['txntype'] = 'pan'; 
  $sv['status'] = 'success';
  $sv['add_date'] = date('Y-m-d H:i:s');
  $sv['pan_aadhaar'] = $img_pannumber;
  $sv['pan_aadhar_status'] = 'success';
  $sv['firstname'] = !empty($img_pan_name)?$img_pan_name:'';
  $sv['midname'] = '';
  $sv['lastname'] = ''; 
  $sv['geotag'] = $geotag;
  $sv['dob'] = $img_pan_dob;
  $sv['careof'] = $img_pan_careof; 
  $sv['photo'] = $panimage;

	$doc_adh_where['usertableid'] = $tableid;
	$doc_adh_where['usertype'] = $usertype;
	$doc_adh_where['txntype'] = 'pan'; 
	$doc_aadholddata = $this->c_model->getSingle('dt_kycdata',$doc_adh_where,'id,usertype,orderid');
	$check_kyc_where = null;
	if(!empty($doc_aadholddata)){ $check_kyc_where['id'] = $doc_aadholddata['id']; }

  $updatepankyc = $this->c_model->saveupdate('dt_kycdata', $sv, null, $check_kyc_where );


if( $img_pannumber != $pannumber ){
	$response['status'] = FALSE;
	$response['message'] = "Pan number didn't match with image pan number"; 
	header("Content-Type:application/json");
	echo json_encode( $response );
	exit;
}
/* MATCH IMAGE RECORD WITH ENTERED PAN DETAILS END */

/* MATCH IMAGE RECORD WITH ENTERED PAN DETAILS START */
$kyc_where['usertableid'] = $tableid;
$kyc_where['usertype'] = $usertype;
$kyc_data = $this->c_model->getAll('dt_kycdata',null,$kyc_where,'*');
//print_r($kyc_data);
if(!empty($kyc_data)){
	foreach ($kyc_data as $key => $value) {
		$fullname = trim( (!empty($value['firstname'])?$value['firstname'].' ':'').(!empty($value['midname'])?$value['midname'].' ':'').(!empty($value['lastname'])?$value['lastname']:'') );
		$fullname = strtoupper($fullname);
		$saverec['ownername'] = $fullname;
		if(in_array($value['txntype'], ['aadhaar']) && ( $fullname != strtoupper($img_pan_name) ) ){
		$response['status'] = FALSE;
		$response['message'] = "Aadhaar name not matched with OCR Pan name"; 
		header("Content-Type:application/json");
		echo json_encode( $response );
		exit;
		}else if(in_array($value['txntype'], ['aadhaar']) && ( $fullname != $nsdl_fullname ) ){
		$response['status'] = FALSE;
		$response['message'] = "Aadhaar name not matched with NSDL Pan name"; 
		header("Content-Type:application/json");
		echo json_encode( $response );
		exit;
		}else if(in_array($value['txntype'], ['pan']) && ( $nsdl_fullname != strtoupper($img_pan_name) ) ){
		$response['status'] = FALSE;
		$response['message'] = "OCR Pan name not matched with NSDL Pan name"; 
		header("Content-Type:application/json");
		echo json_encode( $response );
		exit;
		}else if(in_array($value['txntype'], ['aadhaar']) && (strtotime($img_pan_dob) != strtotime($value['dob'])) ){
		$saverec['dob'] = $value['dob'];
		$response['status'] = FALSE;
		$response['message'] = "Pan DOB not matched with Aadhaar DOB";
		header("Content-Type:application/json");
		echo json_encode( $response );
		exit;
		}

		if(in_array($value['txntype'], ['aadhaar']) ){
			$saverec['aadharno'] = $value['pan_aadhaar'];
		}

	}
}  
/* MATCH IMAGE RECORD WITH ENTERED PAN DETAILS END */



	$saverec['kyc_status'] = 'outlet_photo';
	$saverec['pancard'] = $pannumber;
	$update = $this->c_model->saveupdate($table, $saverec, null, $where );
	
	
	    $response['status'] = true;
	    $response['data'] = ['kyc_status'=>'outlet_photo'];
		$response['message'] = 'Request was Successfully'; 
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
 

private function pushlog($odr,$type,$io,$payload){
	$insert = [];
	$insert['odr'] = $odr;
	$insert['type'] = $type;
	$insert['io'] = $io;
	$insert['req_res'] = $payload;
	$insert['timeon'] = date('Y-m-d H:i:s'); 
	return $this->c_model->saveupdate('dt_pushlog',$insert );
}


private function match_pan_details($arraydata){ 
 
$out = [];   
$out['dob'] = !empty($arraydata['dob']) ? trim($arraydata['dob']) : '';
$out['panname'] = !empty($arraydata['name']) ? trim($arraydata['name']) : '';
$out['careof'] = !empty($arraydata['fathers_name']) ? trim($arraydata['fathers_name']) : '';
$out['pannumber'] = !empty($arraydata['id_no']) ? trim($arraydata['id_no']) : '';
return $out;
}

private function expan($str,$simbol,$position ){
	$str = rtrim($str,$simbol);
	$explode = explode($simbol,$str);
	return is_array($explode) && !empty($explode) ? $explode[$position] : '';
}

private function url_get_contents ($Url) {
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