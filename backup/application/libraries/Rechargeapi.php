<?php defined('BASEPATH') or exit('no direct access allowed');

class Rechargeapi{ 

public  function curl_exe($url,$data=null){
   $ch=curl_init();
   curl_setopt($ch,CURLOPT_URL,$url); 
   if(!is_null($data)){curl_setopt($ch,CURLOPT_POSTFIELDS,$data);}
   curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
   curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
   curl_setopt($ch, CURLOPT_MAXREDIRS, 10 );
   curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE); 
   $buffer= (curl_exec($ch));
   curl_close($ch);
   return $buffer;
 }

/* mani multi api start here */
public function mani_circle($mob,$op){
  $url = 'https://www.manimulti.in/apiservice.asmx/GetOperator?mn='.$mob.'&providertype='.$op;
  $xml_string = file_get_contents($url);
  $json = xmltojson( $xml_string );
  $array = json_decode( $json,true);
  return $array;
}

public function mani_prepaid_recharge($array){
  $url = 'https://www.manimulti.in/apiservice.asmx/Recharge?apiToken='.MANIMULTI_TOKEN;
  if($array['mobile']){ $url .= '&mn='.trim($array['mobile']); }
  if($array['operator']){ $url .= '&op='.trim($array['operator']); }
  if($array['amount']){ $url .= '&amt='.trim($array['amount']); }
  if($array['reqid']){ $url .= '&reqid='.trim($array['reqid']); }
  $url .= '&field1='.($array['field1']?trim($array['field1']):'');
  $url .= '&field2='.($array['field2']?trim($array['field2']):''); 
  $payload = explodeme($url,'?',1);
  $log = $this->pushlog($array['reqid'],'rech','I',$payload);
  $xml_string = $this->curl_exe($url);
  $json = xmltojson( $xml_string );
  $log = $this->pushlog($array['reqid'],'rech','O',$json);
  $res_arr = json_decode( $json,true); 
  $output = array();
  $output['status'] = 'FAILURE';
  $output['deductwallet'] = false;  
  if( !is_null($res_arr) && !empty($res_arr) ){
    $output['status'] = $this->manimulti_status( $res_arr['status'] );
    $output['remark'] = $res_arr['remark'];
    $output['balaftrech'] = $res_arr['balance'];
    $output['ec'] = $res_arr['ec'];  
    if(isset($res_arr['apirefid']) && $res_arr['apirefid'] ){
      $output['apirefid'] = $res_arr['apirefid']; 
    }
    if(isset($res_arr['field1']) && $res_arr['field1'] ){
      $output['op_transaction_id'] = $res_arr['field1']; 
    }  

    $haystack = array('SUCCESS','PROCESSED');
    $needle = trim($output['status']);
    if(in_array($needle,$haystack )){
    $output['deductwallet'] = true;  
    }

  }
  return $output;
}

  public function manimulti_transaction_status($reqid){ 
   $url = 'https://www.manimulti.in/apiservice.asmx/GetRechargeStatus?apiToken='.MANIMULTI_TOKEN.'&reqid='.$reqid;
  $payload = explodeme($url,'?',1);
  $log = $this->pushlog($reqid,'rech','I',$payload);
  $xml_string = $this->curl_exe($url);
  $json = xmltojson( $xml_string );
  $log = $this->pushlog($reqid,'rech','O',$json);  
  $arrayres = json_decode( $json,true);
  return $arrayres;
  } 

  public function mani_dth_recharge($array){
  $url = 'https://www.manimulti.in/apiservice.asmx/Recharge?apiToken='.MANIMULTI_TOKEN;
  if($array['mobile']){ $url .= '&mn='.trim($array['mobile']); }
  if($array['operator']){ $url .= '&op='.trim($array['operator']); }
  if($array['amount']){ $url .= '&amt='.trim($array['amount']); }
  if($array['reqid']){ $url .= '&reqid='.trim($array['reqid']); }
  $url .= '&field1='.($array['field1']?trim($array['field1']):'');
  $url .= '&field2='.($array['field2']?trim($array['field2']):''); 
  $payload = explodeme($url,'?',1);
  $log = $this->pushlog($array['reqid'],'rech','I',$payload);
  $xml_string = $this->curl_exe($url);
  $json = xmltojson( $xml_string );
  $log = $this->pushlog($array['reqid'],'rech','O',$json);
  $res_arr = json_decode( $json,true); 
  $output = array();
  $output['status'] = 'FAILURE';
  $output['deductwallet'] = false;  
  if( !is_null($res_arr) && !empty($res_arr) ){
    $output['status'] = $this->manimulti_status( $res_arr['status'] );
    $output['remark'] = $res_arr['remark'];
    $output['balaftrech'] = $res_arr['balance'];
    $output['ec'] = $res_arr['ec'];  
    if(isset($res_arr['apirefid']) && $res_arr['apirefid'] ){
      $output['apirefid'] = $res_arr['apirefid']; 
    }
    if(isset($res_arr['field1']) && $res_arr['field1'] ){
      $output['op_transaction_id'] = $res_arr['field1']; 
    }  

    $haystack = array('SUCCESS','PROCESSED');
    $needle = trim($output['status']);
    if(in_array($needle,$haystack )){
    $output['deductwallet'] = true;  
    }

  }
  return $output;
}

/* mani multi api end here */


/* emoney mobile recharge api start here */
public function emoney_recharge($array){
   $service = 'MR';
   $url = 'http://service.emoneygroup.co/request/emoney_group_xml.asmx/do_Recharge?UserName='.EMONEY_USERNAME.'&APIKey='.EMONEY_API_KEY; 
  if($array['mobile']){ $url .= '&Number='.trim($array['mobile']); }
  if($array['amount']){ $url .= '&Amount='.trim($array['amount']); }
  if($array['operator']){ $url .= '&OPCode='.trim($array['operator']); }
  $url .= '&ServiceType='.$service;
  if($array['reqid']){ $url .= '&Merchantrefno='.trim($array['reqid']); } 
  $payload = explodeme($url,'?',1);
  $log = $this->pushlog($array['reqid'],'rech','I',$payload);
  $xml_string = $this->curl_exe($url);
  $json = xmltojson( $xml_string );
  $log = $this->pushlog($array['reqid'],'rech','O',$json);
  $arrayres = json_decode( $json,true);
  $output = array();
    $output['status'] = 'FAILURE';
    $output['remark'] = '';
    $output['balaftrech'] = '';
    $output['reqid'] = $array['reqid'];
    $output['op_transaction_id'] = '';
    $output['apirefid'] = NULL;
    $output['field2'] = $service; 
    $output['deductwallet'] = false; 

  if( !is_null($arrayres) && !empty($arrayres) ){
    $output['status'] = $arrayres['Status'];
    $output['remark'] = $arrayres['MSG'];
    $output['balaftrech'] = $arrayres['Balance'];
    $output['reqid'] = $arrayres['Merchantrefno'];
    $output['op_transaction_id'] = $arrayres['OP_Transaction_ID'];
    $output['apirefid'] = $arrayres['eMoney_OrderID'];
    $output['field2'] = $service;
    $haystack = array('SUCCESS','PROCESSED');
    $needle = trim($arrayres['Status']);
    if(in_array($needle,$haystack )){
    $output['deductwallet'] = true;  
    }

  }
  return $output;
}

/* emoney mobile recharge api start here */


/* emoney Postpaid recharge api start here */
public function emoney_postpaid_rech($array){
   $service = 'PP';
   $url = 'http://service.emoneygroup.co/request/emoney_group_xml.asmx/do_Recharge?UserName='.EMONEY_USERNAME.'&APIKey='.EMONEY_API_KEY; 
  if($array['mobile']){ $url .= '&Number='.trim($array['mobile']); }
  if($array['amount']){ $url .= '&Amount='.trim($array['amount']); }
  if($array['operator']){ $url .= '&OPCode='.trim($array['operator']); }
  $url .= '&ServiceType='.$service;
  if($array['reqid']){ $url .= '&Merchantrefno='.trim($array['reqid']); } 
  $payload = explodeme($url,'?',1);
  $log = $this->pushlog($array['reqid'],'rech','I',$payload);
  $xml_string = $this->curl_exe($url);
  $json = xmltojson( $xml_string );
  $log = $this->pushlog($array['reqid'],'rech','O',$json);
  $arrayres = json_decode( $json,true);
  $output = array();
    $output['status'] = 'FAILURE';
    $output['remark'] = '';
    $output['balaftrech'] = '';
    $output['reqid'] = $array['reqid'];
    $output['field1'] = NULL;
    $output['apirefid'] = NULL;
    $output['field2'] = $service;
    $output['apiid'] = $array['apiid']; 
    $output['deductwallet'] = false; 

  if( !is_null($arrayres) && !empty($arrayres) ){
    $output['status'] = $arrayres['Status'];
    $output['remark'] = $arrayres['MSG'];
    $output['balaftrech'] = $arrayres['Balance'];
    $output['reqid'] = $arrayres['Merchantrefno'];
    $output['op_transaction_id'] = isset($arrayres['OP_Transaction_ID'])?$arrayres['OP_Transaction_ID']:'';
    $output['apirefid'] = $arrayres['eMoney_OrderID'];
    $output['field2'] = $service;
    $output['apiid'] = $array['apiid'];
    $haystack = array('SUCCESS','PROCESSED');
    $needle = trim($arrayres['Status']);
    if(in_array($needle,$haystack )){
    $output['deductwallet'] = true;  
    }

  }
  return $output;
}

/* emoney postpaid recharge api end here */


/* emoney DTH recharge api start here */
public function emoney_dth_rech($array){
  $service = 'DH';
   $url = 'http://service.emoneygroup.co/request/emoney_group_xml.asmx/do_Recharge?UserName='.EMONEY_USERNAME.'&APIKey='.EMONEY_API_KEY; 
  if($array['mobile']){ $url .= '&Number='.trim($array['mobile']); }
  if($array['amount']){ $url .= '&Amount='.trim($array['amount']); }
  if($array['operator']){ $url .= '&OPCode='.trim($array['operator']); }
  $url .= '&ServiceType='.$service;
  if($array['reqid']){ $url .= '&Merchantrefno='.trim($array['reqid']); } 
  $payload = explodeme($url,'?',1);
  $log = $this->pushlog($array['reqid'],'rech','I',$payload);
  $xml_string = $this->curl_exe($url);
  $json = xmltojson( $xml_string );
  $log = $this->pushlog($array['reqid'],'rech','O',$json);
  $arrayres = json_decode( $json,true);
  $output = array();
    $output['status'] = 'FAILURE';
    $output['remark'] = '';
    $output['balaftrech'] = '';
    $output['reqid'] = $array['reqid'];
    $output['field1'] = NULL;
    $output['apirefid'] = NULL;
    $output['field2'] = $service; 
    $output['deductwallet'] = false; 

  if( !is_null($arrayres) && !empty($arrayres) ){
    $output['status'] = $arrayres['Status'];
    $output['remark'] = $arrayres['MSG'];
    $output['balaftrech'] = $arrayres['Balance'];
    $output['reqid'] = $arrayres['Merchantrefno'];
    $output['op_transaction_id'] = isset($arrayres['OP_Transaction_ID'])?$arrayres['OP_Transaction_ID']:'';
    $output['apirefid'] = $arrayres['eMoney_OrderID'];
    $output['field2'] = $service; 
    $haystack = array('SUCCESS','PROCESSED');
    $needle = trim($arrayres['Status']);
    if(in_array($needle,$haystack )){
    $output['deductwallet'] = true;  
    }

  }
  return $output;
}

/* emoney DTH Recharge api end here */

public function emoney_wallet_blance(){ 
   $url = 'http://service.emoneygroup.co/request/emoney_group_xml.asmx/get_Balance?UserName='.EMONEY_USERNAME.'&APIKey='.EMONEY_API_KEY;  
  $xml_string = $this->curl_exe($url);
  $json = xmltojson( $xml_string );  
  $arrayres = json_decode( $json,true);
  return $arrayres;
  } 


  public function emoney_transaction_status($Merchantrefno){ 
   $url = 'http://service.emoneygroup.co/request/emoney_group_xml.asmx/check_Recharge?UserName='.EMONEY_USERNAME.'&APIKey='.EMONEY_API_KEY.'&Merchantrefno='.$Merchantrefno;  
  $payload = explodeme($url,'?',1);
  $log = $this->pushlog($Merchantrefno,'rech','I',$payload);
  $xml_string = $this->curl_exe($url);
  $json = xmltojson( $xml_string ); 
  $log = $this->pushlog($Merchantrefno,'rech','O',$json); 
  $arrayres = json_decode( $json,true);
  return $arrayres;
  } 



public function getcircle($mob,$op){ 
   if( RECH_MOBILE == 'manimulti' ){
     $resp = $this->mani_circle($mob,$op);
     return $resp;
    }
}


public function rechargepostpaid($array){ 
     $resp = array();
   if( RECH_MOBILE == 'manimulti' ){
     $resp = $this->mani_recharge($array);
   }else if( RECH_MOBILE == 'emoney' ){
     $resp = $this->emoney_postpaid_rech($array);
   }
     return $resp; 
}

public function dthrecharge($array){ 
     $resp = array();
   if( RECH_MOBILE == 'manimulti' ){
     //$resp = $this->mani_recharge($array);
   }else if( RECH_MOBILE == 'emoney' ){
     $resp = $this->emoney_dth_rech($array);
   }
     return $resp; 
}


/************ mplan prepaid mobile Api start here *******************/
public function mplan_checkplan($array){ 
  //if($array['operator'] == 'Jio'){
    // $url = 'https://www.mplan.in/api/Jionamecheck.php?apikey='.MPLAN_API_KEY;
  //}else{
     if($array['offer']=='simple'){
     $url = 'https://www.mplan.in/api/plans.php?apikey='.MPLAN_API_KEY;
     }else if($array['offer']=='roffer'){ 
     $url = 'https://www.mplan.in/api/plans.php?apikey='.MPLAN_API_KEY;
     $url .= '&offer='.$array['offer'];
     $url .= '&tel='.$array['mobile'];
     }
  //} 
   
  if($array['operator']){ $url .= '&operator='.$array['operator']; }
  if($array['cricle']){ $url .= '&cricle='.urlencode($array['cricle']); } 
  $json = $this->curl_exe($url);
  $array_ot = json_decode( $json,true);
  return $array_ot;
}
/************* mplan prepaid mobile Api end here ********************/


/************ mplan get dth customer Info Api start here *******************/
public function mplan_check_dth_customer_info($array){  
  $url = 'https://www.mplan.in/api/Dthinfo.php?apikey='.MPLAN_API_KEY.'&offer=roffer&tel='.$array['customerid'].'&operator='.$array['operator'];
  $json = $this->curl_exe($url); 
  $array_ot = json_decode( $json,true);
  return $array_ot;
}
/************ mplan get dth customer Info Api end here *******************/


/************ mplan dth heavy refresh Api start here *******************/
public function mplan_dth_heavy_refresh($array){  
  $url = 'https://www.mplan.in/api/Dthheavy.php?apikey='.MPLAN_API_KEY.'&offer='.$array['offer'].'&tel='.$array['customerid'].'&operator='.$array['operator'];  
  $json = $this->curl_exe($url); 
  $array_ot = json_decode( $json,true);
  return $array_ot;
}
/************ mplan dth heavy refresh Api end here *******************/

/************ mplan dth plan Api start here *******************/
public function mplan_dth_plan($array){ 
  if($array['offer']=='simple'){
  $url = 'https://www.mplan.in/api/dthplans.php?apikey='.MPLAN_API_KEY.'&operator='.$array['operator']; 
  }else if($array['offer']=='channel'){
  $url = 'https://www.mplan.in/api/dth_plans.php?apikey='.MPLAN_API_KEY.'&operator='.$array['operator']; 
  }else if($array['offer']=='roffer'){
    $url = 'https://www.mplan.in/api/DthRoffer.php?apikey='.MPLAN_API_KEY.'&offer='.$array['offer'].'&tel='.$array['customerid'].'&operator='.$array['operator']; 
  }  
  
  $json = $this->curl_exe($url); 
  $array_ot = json_decode( $json,true);
  return $array_ot;
}
/************ mplan dth plan Api start here *******************/


/************ mplan prepaid mobile Api start here *******************/
public function mplan_checkoperator($mobileno){ 
  $url = 'http://operatorcheck.mplan.in/api/operatorinfo.php?apikey='.MPLAN_API_KEY.'&tel='.$mobileno;
  $json = $this->curl_exe( $url ); 
  $array = json_decode( $json,true); 
  return $array;
}
/************* mplan prepaid mobile Api end here ********************/

/************ mplan dth check operator Api start here *******************/
public function mplan_dth_checkoperator($customercv){ 
  $url = 'http://operatorcheck.mplan.in/api/dthoperatorinfo.php?apikey='.MPLAN_API_KEY.'&tel='.$customercv;
  $json = $this->curl_exe( $url ); 
  $array = json_decode( $json,true); 
  return $array;
}
/************ mplan dth check operator Api end here *******************/

public function manimulti_status($status){
   $status = trim( $status );
   $out = 'FAILURE';
   if($status == 'FAILED'){
    $out = 'FAILURE';
   }else if($status == 'SUCCESS'){
    $out = 'SUCCESS';
   }else if($status == 'PENDING'){
    $out = 'PROCESSED';
   }else if($status == 'REFUND'){
    $out = 'FAILURE';
   }else if($status == 'FREQUENT'){
    $out = 'FAILURE';
   }
   return $out;
}

/***************** M-Robotics Operator API start ***************/
public function mrobotics_operator_balance(){  
  //$url = 'https://mrobotics.in/api/operator_balance'; 
  $url = MROBOTICX_API_URL.'/api/operator_balance'; 
  $post['api_token'] = MROBOTICX_API_KEY; 
  $header = array('Content-type:application/json');
  $json = curlApis($url,'POST',$post,$header ); 
  return $json; 
  }

 public function mrobotics_lapu_balance($lapuid){ 
  $url = MROBOTICX_API_URL.'/api/lapu_balance'; 
  $post['api_token'] = MROBOTICX_API_KEY;
  $post['lapu_id'] = $lapuid;  
  $header = array('Content-type:application/json');
  $json = curlApis($url,'POST',$post,$header); 
  return $json;
  } 

 public function mrobotics_txn_status($orderid,$lapuid){ 
  $url = MROBOTICX_API_URL.'/api/order_id_status';   
  $post['api_token'] = MROBOTICX_API_KEY;
  //if($lapuid){ $post['lapu_id'] = $lapuid; }
  $post['order_id'] = $orderid; 
  $log = $this->pushlog($orderid,'rech','I',json_encode($post));
  $header = array('Content-type:application/json');
  $json = curlApis($url,'POST',$post,$header);
  $log = $this->pushlog($orderid,'rech','O',json_encode($json) );  
  return $json;
  }  

public function mrobotics_recharge($array){  
  //$url = "https://mrobotics.in/api/recharge_get?api_token=".MROBOTICX_API_KEY."&mobile_no=".trim($array['mobile'])."&amount=".trim($array['amount'])."&company_id=".trim($array['operator'])."&order_id=".trim($array['reqid'])."&is_stv=false&lapu_id=".trim($array['field1']);
  $url = MROBOTICX_API_URL."/api/recharge_get?api_token=".MROBOTICX_API_KEY."&mobile_no=".trim($array['mobile'])."&amount=".trim($array['amount'])."&company_id=".trim($array['operator'])."&order_id=".trim($array['reqid'])."&is_stv=false";
    $payload = explodeme($url,'?',1);
    $log = $this->pushlog($array['reqid'],'rech','I',$payload);
    $arrayres = curlApis($url,'GET'); 
    $log = $this->pushlog($array['reqid'],'rech','O',json_encode($arrayres));
   
    $output = array();
    $output['status'] = 'FAILURE';
    $output['remark'] = '';
    $output['balaftrech'] = '';
    $output['reqid'] = $array['reqid'];
    $output['op_transaction_id'] = '';
    $output['apirefid'] = NULL;
    $output['field2'] = trim($array['field1']);
    $output['field1'] = NULL; 
    $output['deductwallet'] = false; 

  if( !is_null($arrayres) && !empty($arrayres) && !isset($arrayres['error']) ){
    $output['status'] = strtoupper($arrayres['status']);
    $output['remark'] = $arrayres['response'];
    $output['balaftrech'] = $arrayres['balance'];
    $output['reqid'] = $arrayres['order_id'];
    $output['op_transaction_id'] = $arrayres['tnx_id'];
    $output['apirefid'] = $arrayres['lapu_no'];
    $output['status_update'] = date('Y-m-d H:i:s',strtotime($arrayres['updatedAt']));  
    $haystack = array('SUCCESS','PROCESSED');
    $needle = strtoupper( trim($arrayres['status']) );
    if(in_array($needle,$haystack )){
    $output['deductwallet'] = true;  
    }
  }else if( !is_null($arrayres) && !empty($arrayres) && isset($arrayres['error']) && ($arrayres['error']==1) && ($arrayres['status']=='failure') ){
    $output['status'] = strtoupper($arrayres['status']);
    $output['remark'] = $arrayres['errorMessage'];
  }

    return $output;
} 

/***************** M-Robotics  Operator API end ***************/

/***************** Goldpay Recharge API script ***************/
public function goldpay_recharge($array){  
  $url = "http://goldpay.live/Recharge/Recharge_Get?UserID=".GOLDPAY_USERID."&Customernumber=".trim($array['mobile'])."&Optcode=".trim($array['operator'])."&Amount=".trim($array['amount'])."&Yourrchid=".trim($array['reqid'])."&Tokenid=".GOLDPAY_TOKEN."&optional1=&optional2="; 
    $payload = explodeme($url,'?',1);
    $log = $this->pushlog($array['reqid'],'rech','I',$payload);
    $arrayres = curlApis($url,'GET'); 
    $log = $this->pushlog($array['reqid'],'rech','O',json_encode($arrayres));
   
    $output = array();
    $output['status'] = 'FAILURE';
    $output['remark'] = '';
    $output['balaftrech'] = '';
    $output['reqid'] = $array['reqid'];
    $output['op_transaction_id'] = '';
    $output['apirefid'] = NULL;
    $output['field2'] = trim($array['field1']);
    $output['field1'] = NULL; 
    $output['deductwallet'] = false; 

  if( !is_null($arrayres) && !empty($arrayres) ){
    $output['status'] = $this->goldpay_status($arrayres['Status']);
    $output['remark'] = !empty($arrayres['Errormsg'])?$arrayres['Errormsg']:'';
    $output['balaftrech'] = $arrayres['Remain'];
    $output['reqid'] = $arrayres['Yourrchid'];
    $output['op_transaction_id'] = $arrayres['Transid'];
    $output['apirefid'] = $arrayres['RechargeID'];
    $output['status_update'] = date('Y-m-d H:i:s');  
    $haystack = array('SUCCESS','PENDING');
    $needle = strtoupper( $output['status'] );
    if(in_array($needle,$haystack )){
    $output['deductwallet'] = true;  
    }
  }
    return $output;
} 
/***************** Goldpay Recharge API script end ***************/

/***************** Goldpay Recharge Status API script ***************/
public function goldpay_rech_status($orderid){  
  $url = "https://www.goldpay.live/Recharge/Status";
  $body = [];
  $body['userid'] = GOLDPAY_USERID;
  $body['tokenid'] = GOLDPAY_TOKEN;
  $body['ClientRchid'] = $orderid; 
  $header = array('Content-Type: application/json'); 
 
  $log = $this->pushlog($orderid,'rech','I',json_encode($body));
  $arrayres = curlApis($url,'POST',$body,$header ); 
  $log = $this->pushlog($orderid,'rech','O',json_encode($arrayres)); 
  $output = [];
  if( !is_null($arrayres) && !empty($arrayres) ){
    $output['status'] = $this->goldpay_status($arrayres['Status']);
    $output['remark'] = !empty($arrayres['Errormsg'])?$arrayres['Errormsg']:'';
    $output['balaftrech'] = $arrayres['Remain'];
    $output['reqid'] = $arrayres['Yourrchid'];
    $output['op_transaction_id'] = $arrayres['Transid'];
    $output['apirefid'] = $arrayres['RechargeID']; 
  }
    return $output;
} 
/***************** Goldpay Recharge Status API script end ***************/


public function goldpay_status($status){
   $status = strtoupper( trim( $status ) );
   $out = 'FAILURE';
   if($status == 'FAILED'){
    $out = 'FAILURE';
   }else if($status == 'SUCCESS'){
    $out = 'SUCCESS';
   }else if($status == 'PENDING'){
    $out = 'PROCESSED';
   }else if($status == 'REFUND'){
    $out = 'FAILURE';
   }else if($status == 'FREQUENT'){
    $out = 'FAILURE';
   }
   return $out;
}


public function pushlog($odr,$type,$io,$payload){
  $insert = [];
  $insert['odr'] = $odr;
  $insert['type'] = $type;
  $insert['io'] = $io;
  $insert['req_res'] = $payload;
  $insert['timeon'] = date('Y-m-d H:i:s'); 
  ci()->db->insert('dt_pushlog',$insert );
  return ci()->db->insert_id();
}


	
}
?>