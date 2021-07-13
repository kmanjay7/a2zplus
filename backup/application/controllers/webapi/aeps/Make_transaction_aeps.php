<?php defined("BASEPATH") OR exit("No Direct Access Allowed!");

class Make_transaction_aeps extends CI_Controller{
	var $token ;
	var $apiid ;
    public function __construct(){
	parent::__construct(); 
	$this->token = INSTANTPAY_TOKEN;
	$this->apiid = 9;  
	}
		
public function index(){

		header("Pragma: no-cache");
		header("Cache-Control: no-cache");
		header("Expires: 0");

		$response = array();
		$data = array();
		$request = requestJson();
		//$upd = [];

if( ($_SERVER['REQUEST_METHOD'] == 'POST') ){


		 		/*	$response['status'] = FALSE;
					$response['data'] = array();
					$response['bankname'] = '';
					$response['orderid'] = '';
					$response['message']= 'Service is Temporarily Down!';
					header("Content-Type: application/json");
					echo json_encode($response);
					exit;*/

				 
				$userid = isset($request['userid'])?$request['userid']:null; 
				$user_type = isset($request['user_type'])?$request['user_type']:null;

				$outlet_id = isset($request['outlet_id'])?$request['outlet_id']:null;
				$post['outlet_id'] = $outlet_id;
				$post['amount'] = isset($request['amount'])?$request['amount']:false;
				$post['aadhaar_uid'] = isset($request['aadhaar_uid'])?$request['aadhaar_uid']:null;
				$post['bankiin'] = isset($request['bankiin'])?$request['bankiin']:null;
				$post['latitude'] = isset($request['latitude'])?$request['latitude']:null;
				$post['longitude'] = isset($request['longitude'])?$request['longitude']:null;
				$post['mobile'] = isset($request['mobile'])?$request['mobile']:null;
				$sys_orderid = '';
				$sys_orderid = 'AEPS'.date('YmdHis').$userid.rand(100,999);
				$post['agent_id'] = $sys_orderid;
			 
				$post['sp_key'] = isset($request['sp_key'])?$request['sp_key']:null;
				if($post['sp_key'] != 'WAP'){
				$post['amount'] = 0;
				}
				$post['pidDataType'] = isset($request['pidDataType'])?$request['pidDataType']:null;
				$post['pidData'] = isset($request['pidData'])?$request['pidData']:null;
				$post['ci'] = isset($request['ci'])?$request['ci']:null;
				$post['dc'] = isset($request['dc'])?$request['dc']:null;
				$post['dpId'] = isset($request['dpId'])?$request['dpId']:null;

				$post['errCode'] = isset($request['errCode'])?$request['errCode']:null;
				$post['errInfo'] = isset($request['errInfo'])?$request['errInfo']:null;

				$post['fCount'] = isset($request['fCount'])?$request['fCount']:null;
				$post['tType'] = isset($request['tType'])?$request['tType']:null;
				$post['hmac'] = isset($request['hmac'])?$request['hmac']:null;
				$post['iCount'] = isset($request['iCount'])?$request['iCount']:null;
				$post['mc'] = isset($request['mc'])?$request['mc']:null;
				$post['mi'] = isset($request['mi'])?$request['mi']:null;
				$post['nmPoints'] = isset($request['nmPoints'])?$request['nmPoints']:null;
				$post['pCount'] = isset($request['pCount'])?$request['pCount']:null;
				$post['pType'] = isset($request['pType'])?$request['pType']:null;
				$post['qScore'] = isset($request['qScore'])?$request['qScore']:null;
				$post['rdsId'] = isset($request['rdsId'])?$request['rdsId']:null;
				$post['rdsVer'] = isset($request['rdsVer'])?$request['rdsVer']:null;
				$post['sessionKey'] = isset($request['sessionKey'])?$request['sessionKey']:null;
				$post['srno'] = isset($request['srno'])?$request['srno']:null; 
				$post['apptype'] = isset($request['apptype'])?$request['apptype']:'app'; 
				
				  
            
if( !$userid ){
	$response['status'] = FALSE;
	$response['message'] = 'User Id is blank!';
	header("Content-Type: application/json");
	echo json_encode( $response ); exit;
}else if( !$user_type ){
	$response['status'] = FALSE;
	$response['message'] = 'User type is blank!';
	header("Content-Type: application/json");
	echo json_encode( $response ); exit;
}else if( !$outlet_id ){
	$response['status'] = FALSE;
	$response['message'] = 'Outlet id is blank!';
	header("Content-Type: application/json");
	echo json_encode( $response ); exit;
}else if( !$post['sp_key'] ){
	$response['status'] = FALSE;
	$response['message'] = 'Transaction type is blank!';
	header("Content-Type: application/json");
	echo json_encode( $response ); exit;
}

			 
			$where['id'] = $userid;
			$where['user_type'] = $user_type;
			$where['outlet_id'] = $outlet_id;

			$countitem = $this->c_model->countitem('dt_users',$where);

			if( $countitem != 1 ){
				$response['status'] = FALSE;
				$response['message'] = 'User not exists!';
				header("Content-Type: application/json");
				echo json_encode( $response );
				exit;
			} 


$getaddby = $this->c_model->getSingle('dt_users',array('id'=>$userid),'uniqueid,scheme_type');

$surcharge = false;

$commisionArr = $post['amount'] ? $this->get_commision($post['amount'],$getaddby['scheme_type'],$this->apiid ) : [];
$commision = isset($commisionArr['commision'])?$commisionArr['commision']:false;
$ag_comi = isset($commisionArr['ag_comi'])?$commisionArr['ag_comi']:0;
$operatorid = isset($commisionArr['operatorid'])?$commisionArr['operatorid']:false;
$operatorname = isset($commisionArr['operatorname'])?$commisionArr['operatorname']:false;
$ag_tds = isset($commisionArr['ag_tds'])?$commisionArr['ag_tds']:0;

	/*check commission */
	if( ($post['sp_key'] == 'WAP') && ($post['amount'] >= 500 ) && empty( $ag_comi ) ){
		$response['status'] = FALSE;
		$response['message'] = 'Please Try Again!';
		header("Content-Type: application/json");
		echo json_encode( $response );
		exit;
	}

$for_agent_amount = (  (float)$post['amount'] + (float)$ag_comi  );
 
/*save data in database start here */  
				$save['userid'] = $userid;
				$save['usertype'] = $user_type;
				$save['sys_orderid'] = $sys_orderid;
				$save['aadharuid'] = $this->mask( $post['aadhaar_uid'] );
				$save['mobileno'] = $post['mobile'];
				$save['mode'] = $post['sp_key'];
				$save['lat'] = $post['latitude'];
				$save['long'] = $post['longitude'];
				$save['device_name'] = $post['dpId']; 
				$save['device_srno'] = $post['srno'];
				$save['device_mi'] = $post['mi'];
				$save['bankname'] = $post['bankiin'];
				$save['status'] = 'PENDING'; //REQUEST
				$save['add_date'] = date('Y-m-d H:i:s'); 
				$save['amount'] = (float)$post['amount'];
				$save['ag_comi'] = (float)$ag_comi;
				$save['ag_tds'] = (float)$ag_tds;
				$save['sur_charge'] = (float)$surcharge;
				$save['apiname'] = $this->apiid;
				$save['operatorid'] = $operatorid;
				$save['operatorname'] = $operatorname;
				$save['apptype'] = $post['apptype'];

				/*check duplicate*/
				$ch_dupl = $this->c_model->countitem('dt_aeps',['sys_orderid'=>$sys_orderid]);

				if( $ch_dupl ){
				$response['status'] = FALSE;
				$response['message'] = 'Please Try Again!';
				header("Content-Type: application/json");
				echo json_encode( $response );
				exit;
			    } 


                $insertid = $this->c_model->saveupdate('dt_aeps',$save );

                if( !$insertid ){
				$response['status'] = FALSE;
				$response['message'] = 'Some error Occured!';
				header("Content-Type: application/json");
				echo json_encode( $response );
				exit;
			    } 
/*save data in database end here*/  
 
$bankname = $this->c_model->getSingle('dt_bank',array('bank_iin'=>$post['bankiin']),'bankname');


 
$posturl = "https://www.instantpay.in/ws/aepsweb/aeps/transaction"; 

$post_data['token'] = $this->token;
$post_data['request'] = $post; 
$post_data = json_encode($post_data, JSON_UNESCAPED_SLASHES);
$log = $this->pushlog($sys_orderid,'aeps','I',$post_data); /*log entry*/

//$ch = curl_init($posturl);
//curl_setopt($ch, CURLOPT_POST, true);
//curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
//curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json","Accept: application/json"));
//curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30 );
//curl_setopt($ch, CURLOPT_TIMEOUT, 30 );  /*30=1800000 default*/
//curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
//$json = curl_exec($ch); 
//curl_close($ch);
//$buffer = json_decode($json,TRUE);
//print_r($buffer); 

$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => $posturl,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => $post_data,
  CURLOPT_HTTPHEADER => array("Content-Type: application/json"),
));

$json = curl_exec($curl);
curl_close($curl);
$buffer = json_decode($json,TRUE);
$log = $this->pushlog($sys_orderid,'aeps','O',$json);/*log*/ 


   /*update response in table start here */
   $amount_txn = false;  $upd['status'] = '';
   if(!empty($buffer) && isset($buffer['statuscode']) && $buffer['data'] ){
    $respdata =  $buffer['data'];
    $amount_txn = isset($respdata['amount_txn'])?$respdata['amount_txn']:false;
	$upd['txn_mode'] = isset($respdata['txn_mode'])?$respdata['txn_mode']:'';
	$upd['account_no'] = isset($respdata['account_no'])?$respdata['account_no']:'';
	$upd['banktxnid'] = isset($respdata['opr_id'])?$respdata['opr_id']:'';
	$upd['status'] = isset($respdata['status'])?$respdata['status']:'PENDING';
	$upd['wallet_txn_id'] = isset($respdata['wallet_txn_id'])?$respdata['wallet_txn_id']:'';
	$upd['api_status_on'] = isset($buffer['timestamp'])?$buffer['timestamp']:'';
	$upd['respmsg'] = isset($buffer['status'])?$buffer['status']:'';
	$upd['api_response'] = isset($json)?$json:'';
	$upd['api_orderid'] = isset($buffer['orderid'])?$buffer['orderid']:''; 
	if( $post['sp_key'] != 'WAP' ){
	$update = $this->c_model->saveupdate('dt_aeps',$upd,null,['id'=>$insertid] );
	}else if( ($post['sp_key'] == 'WAP') && in_array($upd['status'], ['REFUND','FAILURE','FAILED']) ){
	$upd['status'] = 'FAILED';
    $update = $this->c_model->saveupdate('dt_aeps',$upd,null,['id'=>$insertid] );
    }
   }
   /*update response in table end here */


   



				if(!empty($buffer) && isset($buffer['statuscode']) && ($buffer['statuscode']=='TXN')){ 
                /* run wallet deduct script start */
				if( ($upd['status'] == 'SUCCESS') && (trim($post['sp_key'])=='WAP') ){
				
				/*check  aeps wallet entry  here */ 
				$chwk_wt['userid'] = $userid;
				$chwk_wt['referenceid'] = trim($post['agent_id']);
				$chwk_wt['credit_debit'] = 'credit';
				$countwt = $this->c_model->countitem('dt_wallet_aeps',$chwk_wt  ); 

				/*maintane aeps wallet start here */
				$wt['userid'] = $userid;
				$wt['usertype'] = $user_type;
				$wt['uniqueid'] = trim($getaddby['uniqueid']);  
				$wt['paymode'] = 'wallet';
				$wt['transctionid'] = 'NA';
				$wt['credit_debit'] = 'credit';
				$wt['upiid'] = '';
				$wt['bankname'] = $bankname;
				$wt['remark'] = 'Aeps - Cash Withdrawal';
				$wt['status'] = 'success'; 
				$wt['amount'] = $for_agent_amount;
				$wt['subject'] = 'aeps_m_t';
				$wt['addby'] = $userid; 
				$wt['orderid'] = trim($post['agent_id']);
				$wt['odr'] = (float)$post['amount'];
				$wt['comi'] = (float)$ag_comi;
				$wt['surch'] = '0';
				$wt['tds'] = (float)$ag_tds;
				$wt['flag'] = 1;
				$walleturl = ADMINURL.('webapi/wallet/Creditdebit_aeps'); 
				$headerwt = array('auth: Access-Token='.AEPS_FUND_TXN_TOKEN );   
				$waltwhere['dts'] = $wt;
					if( ( $countwt == 0 ) && $for_agent_amount ){
						$upwt = curlApis($walleturl,'POST', $waltwhere,$headerwt );
							if( $upwt['status'] ){
							$update = $this->c_model->saveupdate('dt_aeps',$upd,null,['id'=>$insertid] );
							}
					}

			    }
				/* run wallet deduct script end */

					$response['status'] = TRUE; 
					$response['data'] = $buffer;
					$response['message'] = $buffer['status'];
					$response['bankname'] = $bankname; 
					$response['orderid'] = $sys_orderid;
				}else if( ($buffer['statuscode']=='ERR') && $buffer['status']){
					$response['status'] = TRUE;
					$response['data'] = $buffer;
					$response['message']= $buffer['status'];
					$response['bankname'] = $bankname;
					$response['orderid'] = $sys_orderid;
				}else{
					$response['status'] = FALSE;
					$response['data'] = array();
					$response['bankname'] = $bankname;
					$response['orderid'] = $sys_orderid;
					$response['message']= 'No result found!';
				}

			
}else{ 
	$response['status'] = FALSE;
	$response['message'] = 'Bad request!'; 
}

   		header("Content-Type: application/json");
		echo json_encode( $response );
}



public function get_commision($amount=false,$scheme=false,$apiid=false){

		$table = 'dt_operators';  
		$commision = false; 
		$serviceid = 1;  

			$output['commision'] = false;
			$output['operatorid'] = false;
			$output['operatorname'] = false;

					$checkamt['min <='] = $amount;
					$checkamt['max >='] = $amount;
					$checkamt['service'] = $serviceid;
					$dbdata = $this->c_model->getSingle($table,$checkamt,'*');

					$comwhere['user_type'] = 'AGENT';
					$comwhere['operatorid'] = $dbdata['id'];
					if($scheme){ $comwhere['scheme_type'] = $scheme; }
					if($apiid){ $comwhere['apiid'] = $apiid; } 
					$comwhere['serviceid'] = $serviceid;
					$commssionarray = $this->c_model->getSingle('dt_set_commission', $comwhere, 'commision_fixed, commision_percent');
 
					$arr['operatorid'] = $dbdata['id'];
					$arr['operatorname'] = $dbdata['operator'];

							if(!is_null($dbdata) && !empty($dbdata)){

							  if( $commssionarray['commision_fixed'] > 0 ){
								$commision = (float)$commssionarray['commision_fixed']; 
							  }else{

							    $percent = percentage($amount,$commssionarray['commision_percent'] );
							  	$commision = (float)$percent; 
							  } 

							}
/*get TDS */
$tds = (float) percentage($commision,TDS );					

$output['commision'] = $commision;
$output['operatorid'] = $dbdata['id'];
$output['operatorname'] = $dbdata['operator'];
$output['ag_comi'] = (float)($commision - $tds);
$output['ag_tds'] = $tds;
 
            return $output;
 
	}

public function strformat($str){
$output=''; 
for($i=0;$i<strlen($str);$i=$i+8){
$substr1=substr($str,$i,4);
$substr2=substr($str,$i+4,4);
$output.='-'.$substr1.'-'.$substr2; 
}
$output=substr($output,1,strlen($output));
$output=substr($output,0,strlen($output)-1);
return $output;
}


public function mask ( $str ) {
	$length = strlen($str)-4;
	$start = 0;
    $mask = preg_replace ( "/\S/", "x", $str );
    if( is_null ( $length )) {
        $mask = substr ( $mask, $start );
        $str = substr_replace ( $str, $mask, $start );
    }else{
        $mask = substr ( $mask, $start, $length );
        $str = substr_replace ( $str, $mask, $start, $length );
    }
    return $this->strformat($str);
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