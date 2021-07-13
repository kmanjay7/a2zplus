<?php defined("BASEPATH") OR exit("No Direct Access Allowed!");

class Testt extends CI_Controller{
  
  public function __construct(){
    parent::__construct();
    }
    

function checkfacematch(){
  $post = $this->input->get();

  $uid = !empty($post['uid'])?$post['uid']:null;
if(empty($uid)){ echo 'bad request'; exit;}

  $getdata = $this->c_model->getSingle('dt_users',['uniqueid'=>$uid],'id,uniqueid,kyc_status');
  $tableid = $getdata['id'];

  $getdata2 = $this->c_model->getAll('dt_kycdata',null,['usertableid'=>$tableid,'usertype'=>'AGENT'],'*'); 

  $aadhaar = [];
  $pandata    = [];
  $facedata   = [];


   if(!empty($getdata2)){
                foreach ($getdata2 as $key => $value) {
                     if($value['txntype']=='aadhaar'){
                      $aadhaar = $value;
                     }else if($value['txntype']=='pan'){
                      $pandata = $value;
                     }else if($value['txntype']=='blinkface'){
                      $facedata = $value;
                     }
                }

            } 

 


  echo '<br/><br/> KYC Status: ';
 echo $kyc_status = $getdata['kyc_status'];
 echo '<br/>';


  $upl1 = $this->c_model->getSingle('dt_uploads',['tableid'=>$tableid,'documenttype'=>'Applicant Photo','usertype'=>'AGENT'],'id,documentorimage');

  if(empty($upl1)){
    echo 'Applicant Photo missing ';
  }


echo '<br/><br/> User Photo:';
  echo $userphotoimage = $upl1['documentorimage'];
echo '<br/><br/> Aadhar Photo:';
  echo $aadharimg = $aadhaar['photo'];




$digio_facematch_api = DIGIO_URL.'v3/client/kyc/facematch';

$post_data = [];
$post_data['file1']       = $this->url_get_contents( base_url('uploads/'.$aadharimg ) );
$post_data['file2']       = $this->url_get_contents( base_url('uploads/'.$userphotoimage ) );
$post_data['minimum_match']   = (int) '50';
$post_data['unique_request_id'] = 'FCM'.$tableid.'_'.date('YmdHis');
$headers = array("Authorization:Basic ".base64_encode(DIGIO_CLIENT_ID.":".DIGIO_SECRET_KEY),"content-type:Multipart/Form-data");
    
  echo '<br/><br/> face Match API Response:';
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
  echo $apiresponse = curl_exec($ch);
  curl_close($ch); 





echo '<br/><br/> Pan OCR Response:';

/* Start  of the OCR system for the Pan card */
   
  $pantext = '';
  $panImagepath = base_url( 'uploads/'.$pandata['photo'] ); 
  $post_data = [];
  $post_data['front_part']       = $this->url_get_contents( $panImagepath );  
  $post_data['unique_request_id'] = (string) 'PV'.date('YmdHis').$tableid;
 
$panurl = DIGIO_URL."v3/client/kyc/analyze/file/idcard"; 
$headers = array("Authorization:Basic ".base64_encode(DIGIO_CLIENT_ID.":".DIGIO_SECRET_KEY),"content-type:Multipart/Form-data");


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
  echo $buffer_response = curl_exec($ch); 
  curl_close($ch);




 
}


 


function checkkyc(){

$post = $this->input->post()?$this->input->post():$this->input->get();  
$id = !empty($post['id'])?trim($post['id']):null;
if(empty($id)){ exit; }

$getdata = $this->c_model->getAll('dt_kycdata',null,['usertableid'=>$id,'usertype'=>'AGENT'],'*');
echo '<pre>';
print_r($getdata);

}

function checkkycdelete(){

$post = $this->input->post()?$this->input->post():$this->input->get();  
$id = !empty($post['id'])?trim($post['id']):null;
if(empty($id)){ exit; }

$getdata = $this->c_model->delete('dt_kycdata',['usertableid'=>$id,'usertype'=>'AGENT']);
echo '<pre>';
print_r($getdata);

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



}?>