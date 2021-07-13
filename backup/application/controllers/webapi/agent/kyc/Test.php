<?php defined("BASEPATH") OR exit("No Direct Access Allowed!");

class Test extends CI_Controller{
	
	public function __construct(){
		parent::__construct();
		}
		

function checkfacematch(){
  $post = requestJson();

$aadharimg = $post['file1'];
$userphotoimage = $post['file2'];

$post_data['file1']       = $this->url_get_contents( $aadharimg );
$post_data['file2']       = $this->url_get_contents( $userphotoimage );
$post_data['minimum_match']   = (int) '';
$post_data['unique_request_id'] = (string) 'FCM_'.date('YmdHis');

//echo json_encode( $post_data );


$url = 'https://ext.digio.in:444/v3/client/kyc/facematch'; 
//Client Id : AIXQOWWJLG3R724WFGGLFX92ORMXWV9L
//Client Secret : JTJGQLD5WRIXUTBBNGJAE74LH263HK8T 
$pkey = DIGIO_CLIENT_ID;
$secret = DIGIO_SECRET_KEY;

$headers = array("Authorization:Basic ".base64_encode($pkey.":".$secret),"content-type:Multipart/Form-data");
//$headers = array("Authorization:Basic ".base64_encode(DIGIO_CLIENT_ID.":".DIGIO_SECRET_KEY),"content-type:Multipart/Form-data");
//$headers = array("Authorization:Basic ".base64_encode("AIXQOWWJLG3R724WFGGLFX92ORMXWV9L:JTJGQLD5WRIXUTBBNGJAE74LH263HK8T"),"content-type:Multipart/Form-data");
 
 print_r($headers);

  $ch = curl_init(); 
  curl_setopt($ch,CURLOPT_URL,$url);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS,$post_data);  
  curl_setopt($ch,CURLOPT_HEADER ,false);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); 
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  echo $buffer = curl_exec($ch);
  curl_close($ch);
 



}

function checkocr(){

$ocrimg = 'https://mydigicash.in/zipaadhaar/13_6386695694_AADHAAR_PHOTO.jpg'; 
$b64image = base64_encode( $this->url_get_contents( $ocrimg ));

$post_data['front_part']       = $b64image;  
$post_data['unique_request_id'] = (string) 'FCM_'.date('YmdHis');

echo json_encode( $post_data );


$url = 'https://ext.digio.in:444/v3/client/kyc/image/perform_ocr'; 
//Client Id : AIXQOWWJLG3R724WFGGLFX92ORMXWV9L
//Client Secret : JTJGQLD5WRIXUTBBNGJAE74LH263HK8T 
$token = "AIXQOWWJLG3R724WFGGLFX92ORMXWV9L:JTJGQLD5WRIXUTBBNGJAE74LH263HK8T";
echo $token = base64_encode($token);

$headers = array("authorization:Basic $token","content-type:Application/json");

 

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_FOLLOWLOCATION=> true,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        //CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POST => 1,
        CURLOPT_POSTFIELDS => $post_data,
        CURLOPT_HTTPHEADER => $headers,
    ));

   echo $response = curl_exec($curl); 
   $err = curl_error($curl); 
   curl_close($curl);



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


function sendmaily($to,$subject,$message ,$file = null,$replyto = null,$from = false,$mailer = false,$cc = false,$bcc = false ){
                
                $hostname = $_SERVER['HTTP_HOST'];
                echo $ip = gethostbyname($hostname);

                if($ip != '127.0.0.1'){
                ci()->load->library('email');
                
                $config['protocol'] = 'mail'; //smtp/sendmail/mail
                $config['smtp_host'] = FHOST ;
                $config['smtp_user'] = FSERVERUSER ;
                $config['smtp_pass'] = FPASSWORD ;
                $config['smtp_port'] = 587;
                
                $config['charset'] = 'utf-8'; 
                $config['wordwrap'] = TRUE;

                $config['wrapchars'] = 76;
                $config['priority'] = 1;
                $config['mailtype'] = 'html';
                //$config['smtp_crypto'] = 'tls';
                
print_r($config);
                
                if(!$from){ $from = FROMMAIL ; } 
                if(!$mailer){ $mailer = FMAILER ; } 
                if(!$cc){ $cc = FCC ; } 
                if(!$bcc){ $bcc = '' ; }  
                
                $bcc = '';
                
                ci()->email->initialize( $config );
                
                ci()->email->from( $from, $mailer );
                ci()->email->to( $to , $mailer );
                !empty($cc) ? ci()->email->cc( $cc , $mailer) : '';
                if( !is_null($replyto)){  ci()->email->reply_to($replyto , $mailer ); }
                !empty($bcc) ? ci()->email->bcc( $bcc , $mailer) : '';
                ci()->email->subject( $subject );
                if( !is_null($file)){ ci()->email->attach( $file );}
                ci()->email->message( $message );
                
                if( ci()->email->send() ) {
                return true;
                } else {
                return false;
                echo ci()->email->print_debugger();
                }
            }else{ return false;}
                
}
/*php mailer end */



function changetable(){
    echo $this->db->query("ALTER TABLE `dt_uploads` ADD `geotag` VARCHAR(150) NOT NULL ;");
}


function deletekycdata(){
    $post = $this->input->get();
    if(empty($post)){ exit; }

    $query = "DELETE FROM dt_kycdata WHERE id='".$post['id']."' ";
    echo !empty($post['id']) ? $this->db->query($query) : '';
}

function updatekycdata(){
    $post = $this->input->get();
    if(empty($post['key']) && empty($post['val']) ){ exit; }

    $query = "UPDATE dt_kycdata SET `".$post['key']."`='".$post['val']."' WHERE id='".$post['id']."' ";
    echo !empty($post['id']) ? $this->db->query($query) : '';
}


function genhash(){

 $post = $this->input->get();

  $mobile = $post['m'];
  $aadhar = $post['a'];
  $passcode = $post['p'];

  $last_aadhar = substr($aadhar,-1);
  $hash = $mobile.$passcode;
 for ($i=1; $i <= $last_aadhar; $i++) {  
      $hash = hash('SHA256', $hash );
 }
$signature = 'ffbde5e6666a36252cb3cd485ca580c4ce2b0291dcbce6db701e0290ec25662a';
if($hash == $signature ){
  echo 'hi';
}


 function validateemhash($email_mob,$passcode,$aadhar,$hashval){
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


function getkycdata(){ 
      $request = $this->input->get();
     $id = $request['id'];
     $uniqueid = $request['uniqueid'];
     $utype = $request['utype']; 

     if($id && $uniqueid && $utype ){
      $where['id'] = $id;
      $where['uniqueid'] = $uniqueid;
      $where['user_type'] = strtoupper( $utype );
      $resp =  $this->c_model->getSingle('dt_users',$where,'*');
      echo '<pre>';
      print_r( $resp );
     }

}



}?>