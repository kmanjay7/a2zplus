<?php
if(!function_exists('ci')){ 
	function ci(){
		$ci = & get_instance();
		return $ci; 
		}
}

function bpsession_check(){
	if(!empty(ci()->session->userdata('uniqueid')) && ( ci()->session->userdata('user_type') == 'BP') && (ci()->session->userdata('is_ok')=='yes')){ 
           return TRUE;
            } else {
                ci()->session->sess_destroy();
               // redirect(ADMINURL.'login');
                redirect('https://bp.mydigicash.in/');
            }
}

function mdsession_check(){
      if(!empty(ci()->session->userdata('uniqueid')) && ( ci()->session->userdata('user_type') == 'MD') && (ci()->session->userdata('is_ok')=='yes')){
           return TRUE;
            } else {
                ci()->session->sess_destroy();
                redirect(ADMINURL.'login');
            }
}
function adsession_check(){
       if(!empty(ci()->session->userdata('uniqueid')) && ( ci()->session->userdata('user_type') == 'AD') && (ci()->session->userdata('is_ok')=='yes')){
           return TRUE;
            } else {
                ci()->session->sess_destroy();
                redirect(ADMINURL.'login');
            }
}
function agsession_check(){
       if(!empty(ci()->session->userdata('uniqueid')) && (ci()->session->userdata('user_type') == 'AGENT') && (ci()->session->userdata('is_ok')=='yes') ){
           return TRUE;
            } else {
                ci()->session->sess_destroy();
                redirect(ADMINURL.'login');
            }
}

function getloggeduserdata($key=null){
   return $session = $key ? ci()->session->userdata($key) :'';
}

/*------------Fetch Single Data Start---------*/
if(!function_exists('getftchSingleata')){
function getftchSingleata( $table,$where,$keys = null ){
if(!is_null($keys)){
ci()->db->select($keys);
}
return ci()->db->where($where)->get($table)->row_array();
}
}
/*----------Fetch Single Data End---------*/

function countitem($table,$where = null, $whereor = null,$whereorkey = null ){
		
if(!is_null($where)){

$query = ci()->db->where($where);

        if( !is_null($whereor)){ 
        $query = ci()->db->group_start();
        foreach($whereor as $row){ $query = ci()->db->or_where($whereorkey,$row ); }
        $query = ci()->db->group_end();
        }

$query = ci()->db->get($table);
}else{ $query = ci()->db->get($table);}
$count = $query->num_rows();
return ( $count > 0 ? $count : 0 );
}
  function generateStrongPassword($length , $add_dashes = false, $available_sets = 'luds')
{
	$sets = array();
	if(strpos($available_sets, 'l') !== false)
		$sets[] = 'abcdefghjkmnpqrstuvwxyz';
	if(strpos($available_sets, 'u') !== false)
		$sets[] = 'ABCDEFGHJKMNPQRSTUVWXYZ';
	if(strpos($available_sets, 'd') !== false)
		$sets[] = '23456789';
	if(strpos($available_sets, 's') !== false)
		$sets[] = '!@#$%&*?';
	$all = '';
	$password = '';
	foreach($sets as $set)
	{
		$password .= $set[array_rand(str_split($set))];
		$all .= $set;
	}
	$all = str_split($all);
	for($i = 0; $i < $length - count($sets); $i++)
		$password .= $all[array_rand($all)];
	$password = str_shuffle($password);
	if(!$add_dashes)
		return $password;
	$dash_len = floor(sqrt($length));
	$dash_str = '';
	while(strlen($password) > $dash_len)
	{
		$dash_str .= substr($password, 0, $dash_len) . '-';
		$password = substr($password, $dash_len);
	}
	$dash_str .= $password;
	return $dash_str;
}              

function pagination($base_url, $rows, $per_page)
{
    $config['base_url'] = $base_url;
    $config['total_rows'] = $rows;
    $config['per_page'] = $per_page;
    $config['reuse_query_string'] = TRUE;
    $config['full_tag_open'] = '<ul class="pagination">';
    $config['full_tag_close'] = '</ul>';            
    $config['prev_link'] = '«';
    $config['prev_tag_open'] = '<li class="page-item">';
    $config['prev_tag_close'] = '</li>';
    $config['next_link'] = '»';
    $config['anchor_class'] = 'class="page-link" ';
    $config['next_tag_open'] = '<li class="page-item">';
    $config['next_tag_close'] = '</li>';
    $config['attributes'] = array('class' => 'page-link');
    $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="#">';
    $config['cur_tag_close'] = '</a></li>';
    $config['num_tag_open'] = '<li class="page-item">';
    $config['num_tag_close'] = '</li>';
    ci()->load->library("pagination");
    ci()->pagination->initialize($config);
    return ci()->pagination->create_links();
}

if(!function_exists('upload'))
{
	function upload($pic_name,$path=false)
	{
	     $config['upload_path'] = $path;
         $config['allowed_types'] = 'gif|jpg|png|jpeg';
		 $config['encrypt_name'] = true;
         ci()->load->library('upload', $config);
		 ci()->upload->initialize($config);
		 if(!ci()->upload->do_upload($pic_name))
		 {
			$error = array('error' => ci()->upload->display_errors());
			return false;
			//print_r($error);
		 }
		 else
		 {
			$data =  ci()->upload->data();
			return $data;
		 }
	 }
}
                
function notification()
{
	if(ci()->session->flashdata('error'))
	{?>

<div class="alert alert-danger alert-dismissible" align="center" style="width:100%;padding:3px;"> <a href="#" class="close" data-dismiss="alert" aria-label="close" style="right:0px;">&times;</a> <?php echo ci()->session->flashdata('error')?> </div>
<?php }
	elseif(ci()->session->flashdata('success'))
	{?>
<div class="alert alert-success alert-dismissible" align="center" style="width:100%;padding:3px;"> <a href="#" class="close" data-dismiss="alert" aria-label="close" style="right:0px;">&times;</a> <?php echo ci()->session->flashdata('success')?></div>
<?php }
	elseif(ci()->session->flashdata('warning'))
	{?>
<div class="alert alert-warning alert-dismissible" align="center" style="width:100%;padding:3px;"> <a href="#" class="close" data-dismiss="alert" aria-label="close" style="right:0px;">&times;</a> <?php echo ci()->session->flashdata('warning')?> </div>
<?php }
	elseif(ci()->session->flashdata('info'))
	{?>
<div class="alert alert-info alert-dismissible" align="center" style="width:100%;padding:3px;"> <a href="#" class="close" data-dismiss="alert" aria-label="close" style="right:0px;">&times;</a> <?php echo ci()->session->flashdata('info')?> </div>
<?php }
}


if(!function_exists('errors'))
{
  function errors()
  { 
   $errors=validation_errors();
   if(!empty($errors)):
   ?>
<div class="alert alert-danger alert-dismissible" align="" style="width:100%;padding:5px;"> <a href="#" class="close" data-dismiss="alert" aria-label="close" style="right:0px;">&times;</a> <?php echo $errors;?> </div>
<?php endif; }
}


function sessionunique() { 
if(!empty(ci()->session->userdata('sessionid'))){}else{
$length     =   "10"; 
$time       =   time();
$char       =   "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"; 
$random     =   substr(str_shuffle($char), 0, $length);
//$random     =   $random.$time;
ci()->session->set_userdata('sessionid',$random);
}
}

if(!function_exists('generateOtp'))
{
   function generateOtp()
   {
     $str='0123456789';
	 $shuffle=str_shuffle($str);
	 $otp=substr($shuffle,0,4);
	 return $otp;
   }
}

function gettimedeffrence($start,$end){
$to_time = strtotime($end);
$from_time = strtotime($start);
$diff = round(abs($to_time - $from_time) / 60,2);
return $diff;
}

function userregistrationotp($array){
	$smsbody = SMSPREFIX."! Your Login OTP is:".$array['otp'] ;
	$mobile = $array['mobile'];
	$output = strlen($mobile) == 10 ? sms_control($mobile,$smsbody) : '' ;
	return  !empty($output) ? true : false;
	}

function simplesms($mobile,$smsbody,$restype = null){
	$output = strlen($mobile) == 10 ? sms_control($mobile,$smsbody,$restype) : '' ; 
	return ( !is_null( $restype ) ? $output : (!empty($output) ? true : false) );
	}

function sendsms_api_first($mobile,$message,$restype = null ){
	$str = urlencode($message);
	$urlll = "http://mysms.msgclub.net/rest/services/sendSMS/sendGroupSms?AUTH_KEY=".SMSKEY."&message=$str&senderId=".SENDERID."&routeId=".ROOTID."&mobileNos=$mobile&smsContentType=english";

    $payload = explodeme($urlll,'?',1);
    $log = pushlogsms($mobile,'sms','I',$payload);
    
	$curl = curl_init();
	$timeout = 25;
	curl_setopt($curl, CURLOPT_URL, $urlll);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_HEADER, false);
	curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $timeout);


	$jsondata = curl_exec($curl);
	curl_close($curl);
	$log = pushlogsms($mobile,'sms','O',$jsondata );
	$data = json_decode($jsondata, true);
	$datastatus = $data['responseCode'];
	if($datastatus =='3001'){ $status = true; }else{ $status=false;}
	return ( !is_null( $restype ) ? $jsondata : $status );
	}


function sendsms_api_second($mobile,$message,$restype = null){
	$str = urlencode($message); 
	$url = "http://trans.smsfresh.co/api/sendmsg.php?user=DigiCash.Ind&pass=123456&sender=DIGICH&phone=".$mobile."&text=".$str."&priority=ndnd&stype=normal";

    $payload = explodeme($url,'?',1);
    $log = pushlogsms($mobile,'sms','I',$payload);
    
	$curl = curl_init();
	$timeout = 25;
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_HEADER, false);
	curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $timeout);


	$jsondata = curl_exec($curl);
	curl_close($curl);
	$log = pushlogsms($mobile,'sms','O',$jsondata );
	$status = false;
	if(strpos($jsondata, 'S')){
		$status = true;
	}  
	return ( !is_null( $restype ) ? $jsondata : $status );
	}


function sendsms_api_third($mobile,$message,$restype = null){
	$str = urlencode($message); 
	
	if(  strpos(strtolower($message), 'otp') !== false ){
    $url = "https://www.alcodes.com/api/sms-compose?message=".$str."&phoneNumbers=".$mobile."&countryCode=IN&smsSenderId=DIGICS&smsTypeId=1&otp=true&walletType=DOMESTIC&username=b1df8839-cc9d-4e82-97a9-0bafa120b3fa&password=passwd";
    }else{
    $url = "https://www.alcodes.com/api/sms-compose?message=".$str."&phoneNumbers=".$mobile."&countryCode=IN&smsSenderId=DIGICS&smsTypeId=1&otp=false&walletType=DOMESTIC&username=b1df8839-cc9d-4e82-97a9-0bafa120b3fa&password=passwd";
    }
    
    $payload = explodeme($url,'?',1);
    $log = pushlogsms($mobile,'sms','I',$payload);
    
	$curl = curl_init();
	$timeout = 25;
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_HEADER, false);
	curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $timeout);

	$jsondata = curl_exec($curl);
	curl_close($curl);
	$log = pushlogsms($mobile,'sms','O',$jsondata );
	$response = json_decode($jsondata,true);
	$status = false;
	if(isset($response['status']) && $response['status']=='success'){
		$status = true;
	} 
	return ( !is_null( $restype ) ? $jsondata : $status );
	}	


function sendsms_api_fourth($mobile,$message,$restype = null){
	$str = rawurlencode($message); 
	$sender = urlencode('DGCash'); 
	$mobile = urlencode( '91'.$mobile );
	$apiKey = urlencode('7uXvl8Fj6EA-Aba7sou8BSRVNXW1JcssyvyHEIlT0T');
    $url = "https://api.textlocal.in/send/?apiKey=".$apiKey."&sender=".$sender."&numbers=".$mobile."&message=".$str; 
    $payload = explodeme($url,'?',1);
    $log = pushlogsms($mobile,'sms','I',$payload);
	$curl = curl_init();
	$timeout = 25;
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_HEADER, false);
	curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $timeout);

	$jsondata = curl_exec($curl);
	curl_close($curl);
	$log = pushlogsms($mobile,'sms','O',$jsondata );
	$response = json_decode($jsondata,true);
	$status = false;
	if(isset($response['status']) && $response['status']=='success'){
		$status = true;
	} 
	return ( !is_null( $restype ) ? $jsondata : $status );
	}


function sms_control( $mobile, $smsbody,$restype){ 
   $status = false;
   $row = ci()->db->select('sms')->get('dt_sms_setting')->row_array();
   if($row['sms'] == 1){
   	$status = sendsms_api_first( $mobile, $smsbody,$restype );
   }else if($row['sms'] == 2){
	$status = sendsms_api_second( $mobile, $smsbody,$restype );
   }else if($row['sms'] == 3){
	$status = sendsms_api_third( $mobile, $smsbody,$restype );
   }else if($row['sms'] == 4){
	$status = sendsms_api_fourth( $mobile, $smsbody,$restype );
   }
   
   return $status;
}

function pushlogsms($odr,$type,$io,$payload){
  if((strlen($odr)==10) && ($type=='sms') ){ $odr = '91'.$odr; }
  $insert = [];
  $insert['odr'] = $odr;
  $insert['type'] = $type;
  $insert['io'] = $io;
  $insert['req_res'] = $payload;
  $insert['timeon'] = date('Y-m-d H:i:s'); 
  ci()->db->insert('dt_pushlog',$insert );
  return ci()->db->insert_id();
}