<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Check_wallet_balance extends CI_Controller{
	 
	  function __construct() {
         parent::__construct(); 
      }


function check_balance(){

 header("Pragma: no-cache");
 header("Cache-Control: no-cache");
 header("Expires: 0");
 $request = requestJson();
        
if( ($_SERVER['REQUEST_METHOD'] == 'POST') ){


			$uniqueid = isset($request['uniqueid']) ? trim($request['uniqueid']) : false; 

			if( $uniqueid == md5(8115171716) ){ 

			 		 require_once(APPPATH . "/third_party/PaytmKit/lib/config_paytm.php");
					 require_once(APPPATH . "/third_party/PaytmKit/lib/encdec_paytm.php");

				 
				/* initialize an array */
				$paytmParams = array();
				$post_data = json_encode($paytmParams, JSON_UNESCAPED_SLASHES);  
				$checksum = getChecksumFromString($post_data, PAYTM_MERCHANT_KEY ); 
				$x_mid = PAYTM_MERCHANT_MID; 
				$x_checksum = $checksum; 
				$url = PAYTM_CHECK_BALANCE_URL; 

				$ch = curl_init($url);
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "x-mid: " . $x_mid, "x-checksum: " . $x_checksum)); 
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
				$buffer = curl_exec($ch);
				$buffer = json_decode($buffer,true);


				$balance = 0;
                if($buffer['status']=='SUCCESS' && isset($buffer['result']) && $buffer['result'] ){ 
                	foreach ($buffer['result'] as $key => $value) {
                		 if($value['subWalletGuid'] == SUBWALLET_GUID ){
                		 	$balance = $value['walletBalance'];
                		 	break;
                		 	
                		 		/* initialize an array */
				$paytmParams = array();
				$post_data = json_encode($paytmParams, JSON_UNESCAPED_SLASHES);  
				$checksum = getChecksumFromString($post_data, PAYTM_MERCHANT_KEY ); 
				$x_mid = PAYTM_MERCHANT_MID; 
				$x_checksum = $checksum; 
				$url = PAYTM_CHECK_BALANCE_URL; 

				$ch = curl_init($url);
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "x-mid: " . $x_mid, "x-checksum: " . $x_checksum)); 
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
				$buffer = curl_exec($ch);
				$buffer = json_decode($buffer,true);


				$balance = 0;
                if($buffer['status']=='SUCCESS' && isset($buffer['result']) && $buffer['result'] ){ 
                	foreach ($buffer['result'] as $key => $value) {
                		 if($value['subWalletGuid'] == SUBWALLET_GUID_AEPS ){
                		 	$balance = $value['walletBalance'];
                		 	break;
                		 }

                	}
                	
                } 



				$response['status'] = true;
				$response['balance'] = (float)$balance; 
			    $response['message'] = 'fetched Data !';
			 
			}else{ 
				$response['status'] = false; 
			    $response['message'] = 'Something went wrong !';
			}


}else{
	$response['status'] = false;
	$response['message'] = 'Bad Request!';
}
	header('Content-Type:application/json');
	echo json_encode($response);
} 

}
?>