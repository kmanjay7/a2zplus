<?php defined('BASEPATH') or exit('no direct access allowed');

class Bbps_recharge{   

private function agent_login()
{
  $url = BBPS_URL.'agents/'.BBPS_URL_AGENT_ID.'/login';
  $post['username'] = BBPS_USERNAME;
  $post['password'] = BBPS_PASSWORD;
  $header[] = 'Content-Type: application/json';
  $array = curlApis($url,'POST', $post,$header ); 
  return $array['token'];
}

public function billers_info()
{ 
  $url = BBPS_URL.'agents/'.BBPS_URL_AGENT_ID.'/billers/info'; 

  $header[] = 'Content-Type: application/json';
  $token=$this->agent_login();
  $header[] = 'jwt_token:'.$token;
  $response = curlApis( $url,'GET', [], $header ); 
  return $response;
}

public function bill_fetch($array)
{ 
  $url = BBPS_URL.'agents/'.BBPS_URL_AGENT_ID.'/bill/fetch'; 

  $header[] = 'Content-Type: application/json';
  $token=$this->agent_login();
  $header[] = 'jwt_token:'.$token;
  $response = curlApis( $url,'POST', $array , $header ); 
  return $response;
}

public function bill_pay($array)
{ 
  $url = BBPS_URL.'agents/'.BBPS_URL_AGENT_ID.'/bill/pay'; 

  $header[] = 'Content-Type: application/json';
  $token=$this->agent_login();
  $header[] = 'jwt_token:'.$token;
  $response = curlApis( $url,'POST', $array , $header ); 
  // $response['ref_id'] = $array['ref_id']; 
  return $response;
}

public function status($array)
{ 
  $url = BBPS_URL.'agents/'.BBPS_URL_AGENT_ID.'/bill/status'; 

  $header[] = 'Content-Type: application/json';
  $token=$this->agent_login();
  $header[] = 'jwt_token:'.$token;
  $response = curlApis( $url,'POST', $array , $header ); 
  // $response['ref_id'] = $array['ref_id']; 
  return $response;
}

public function balance()
{ 
  $url = BBPS_URL.'agents/'.BBPS_URL_AGENT_ID.'/fetchBalance'; 

  $header[] = 'Content-Type: application/json';
  $token=$this->agent_login();
  $header[] = 'jwt_token:'.$token;
  $response = curlApis( $url,'GET', [] , $header ); 
  // $response['ref_id'] = $array['ref_id']; 
  return $response;
}

}
?>