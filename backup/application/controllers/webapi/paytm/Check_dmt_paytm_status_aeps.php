<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Check_dmt_paytm_status_aeps extends CI_Controller{
	
	function __construct() {
         parent::__construct();  
    }



	function index(){

			header("Pragma: no-cache");
			header("Cache-Control: no-cache");
			header("Expires: 0");

		    // following files need to be included
			require_once(APPPATH . "/third_party/PaytmKit/lib/config_paytm.php");
			require_once(APPPATH . "/third_party/PaytmKit/lib/encdec_paytm.php");

			$request = requestJson('dts');
			$response = array();

if( ($_SERVER['REQUEST_METHOD'] == 'POST') ){

$orderId = isset($request['orderId']) ? trim($request['orderId']) : false;
$getagentid = $this->c_model->getSingle('dmtlog_aeps', array('orderid'=>$orderId) ,' * ' );
$buffer['status'] = $getagentid['status'];


if( $orderId){
$paytmParams = array(); 
$paytmParams["orderId"] = $orderId;
$post_data = json_encode($paytmParams, JSON_UNESCAPED_SLASHES);
$log = $this->pushlog($orderId,'dmtaeps','I',$post_data);

$checksum = getChecksumFromString($post_data, PAYTM_MERCHANT_KEY ); 
$x_mid = PAYTM_MERCHANT_MID;
$x_checksum = $checksum;

$url = PAYTM_FUND_CHECKSUM_URL; 
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "x-mid: " . $x_mid, "x-checksum: " . $x_checksum));
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10 );
curl_setopt($ch, CURLOPT_TIMEOUT, 20 ); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
$jsonresponse = curl_exec($ch);
curl_close($ch);
$log = $this->pushlog($orderId,'dmtaeps','O',$jsonresponse);

$buffer = $jsonresponse ? json_decode( $jsonresponse, true ) : array();


		if(isset($buffer['status']) && ($buffer['status']) ){
		$response['status'] = true;
		$response['data'] = $buffer ;
		$response['message'] = isset($buffer['statusMessage'])?$buffer['statusMessage']:'Success!';
		}else{
		$response['status'] = false;
		$response['message'] = isset($buffer['statusMessage'])?$buffer['statusMessage']:'Paytm error!';
		}


}else{
	$response['status'] = false;
	$response['message'] = 'Order Id is blank!';
}



}else{
	$response['status'] = false;
	$response['message'] = 'Bad Request!';
}

header('Content-Type:application/json');
echo json_encode($response);
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
	
}
?>