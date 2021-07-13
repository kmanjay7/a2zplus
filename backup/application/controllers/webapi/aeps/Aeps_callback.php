<?php defined("BASEPATH") OR exit("No Direct Access Allowed!");

class Aeps_callback extends CI_Controller{
	
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
		
        $rech_data = requestJson();
        $response = array();
   


 if( ($_SERVER['REQUEST_METHOD'] == 'POST') ){
 
        $orderid = $rech_data['orderid']; 
       

        if( !$orderid ){
            $response['status'] = FALSE;
            $response['message'] = 'Order Id is blank!';
            header("Content-Type: application/json");
            echo json_encode( $response ); exit;
        }


$getData = $this->c_model->getSingle('dt_aeps',['sys_orderid'=>$orderid],'id,userid,add_date, status,mode,amount,ag_comi' );
$where['id'] = $getData['userid'];

$agentamount = (float)$getData['amount'] + (float)$getData['ag_comi'];


/* run check status api start */
$posturl = "https://www.instantpay.in/ws/status/preorderbyexternalid"; 

$post_data['token'] = $this->token;
$post_data['request']['external_id'] = $orderid;
$post_data['request']['transaction_date'] = date('Y-m-d',strtotime($getData['add_date']));
 
$post_data = json_encode($post_data, JSON_UNESCAPED_SLASHES);
$log = $this->pushlog($orderid,'aeps','I',$post_data);/*log*/ 
$ch = curl_init($posturl);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json",
    "Accept: application/json"));
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20 );
curl_setopt($ch, CURLOPT_TIMEOUT, 30 );
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
curl_setopt($ch, CURLOPT_MAXREDIRS, 10); 
curl_setopt($ch, CURLOPT_FOLLOWLOCATION , true); 
$json = curl_exec($ch); 
curl_close($ch);
$buffer = json_decode($json,TRUE);
/* run check status api end */
$log = $this->pushlog($orderid,'aeps','O',$json);/*log*/ 
//print_r($buffer) ;
			 
       $save_dt = [];
       $save_dt['status'] = 'PENDING';

       if($buffer['statuscode'] == 'TXN'){

        $respdata = $buffer['data'];

       

                if( ($getData['status']=='PENDING') ){  
                    if($respdata['transaction_status']=='REFUND'){
                        $save_dt['status'] = 'FAILED';
                    }else if($respdata['transaction_status']=='SUCCESS'){
                        $save_dt['status'] = 'SUCCESS';
                    }else if($respdata['transaction_status']=='FAILURE'){
                        $save_dt['status'] = 'FAILED';
                    }else if($respdata['transaction_status']=='FAILED'){
                        $save_dt['status'] = 'FAILED';
                    }else if($respdata['transaction_status']=='NOTFOUND'){
                        $save_dt['status'] = 'FAILED';
                    }


    $save_dt['txn_mode'] = isset($respdata['txn_mode'])?$respdata['txn_mode']:'';
    $save_dt['account_no'] = isset($respdata['transaction_account'])?$respdata['transaction_account']:''; 
    $save_dt['banktxnid'] = isset($respdata['serviceprovider_id'])?$respdata['serviceprovider_id']:''; 
    $save_dt['wallet_txn_id'] = isset($respdata['additional_details']['wallet_txn_id'])?$respdata['additional_details']['wallet_txn_id']:'';
    $save_dt['api_status_on'] = isset($buffer['timestamp'])?$buffer['timestamp']: date('Y-m-d');
    $save_dt['respmsg'] = isset($respdata['transaction_description'])?$respdata['transaction_description']:'';
    $save_dt['api_response'] = isset($json)?$json:'';
    $save_dt['api_orderid'] = isset($respdata['order_id'])?$respdata['order_id']:'';
     
    if( in_array($getData['mode'],['SAP','BAP']) ){
    $update = $this->c_model->saveupdate('dt_aeps',$save_dt,null,array('id'=>$getData['id']));
    }else if($save_dt['status']=='FAILED'){
    $update = $this->c_model->saveupdate('dt_aeps',$save_dt,null,array('id'=>$getData['id']));
    }                  
   
                }
                
                $response['status'] = true;
                $response['data'] = $buffer;
                $response['message'] = 'success!'; 
        }else{
                $response['status'] = false;
                $response['data'] = $buffer;
                $response['message'] = 'failure!';
        }
        

	if( ($getData['status']=='PENDING') && ($save_dt['status']=='SUCCESS') && ($respdata['product_key'] == 'WAP') ){ 

        $userdata = $this->c_model->getSingle('dt_users',$where,'id,uniqueid,user_type');
        $chwk_wt['userid'] = $userdata['id'];
        $chwk_wt['referenceid'] = $orderid;
        $chwk_wt['credit_debit'] = 'credit';
        $countwt = $this->c_model->countitem('dt_wallet_aeps',$chwk_wt  ); 
          
		/* check aeps wallet for this transaction start */ 
        $wtsave['userid'] = $userdata['id'];
        $wtsave['usertype'] = $userdata['user_type'];
        $wtsave['uniqueid'] = $userdata['uniqueid'];
        $wtsave['paymode'] = 'wallet';
        $wtsave['transctionid'] = 'NA';
        $wtsave['credit_debit'] = 'credit';
        $wtsave['upiid'] = '';
        $wtsave['bankname'] = ''; 
        $wtsave['remark'] = 'Aeps - Cash Withdrawal';
        $wtsave['status'] = 'success'; 
        $wtsave['amount'] = (float)$agentamount;
        $wtsave['subject'] = 'aeps_m_t';
        $wtsave['addby'] = $userdata['id']; 
        $wtsave['orderid'] = $orderid;
        $wtsave['odr'] = (float)$agentamount;
        $wtsave['comi'] = '0';
        $wtsave['surch'] = '0';
        $wtsave['tds'] = '0';
        $wtsave['flag'] = 1;

        $postapiurl = APIURL.('webapi/wallet/creditdebit_aeps');
        $upwtwhere['dts'] = $wtsave;  
        $header = array('auth: Access-Token='.AEPS_FUND_TXN_TOKEN );
            if( ($countwt == 0 ) && $agentamount ){
                $upwt = curlApis($postapiurl,'POST',$upwtwhere,$header); 
                if($upwt['status']){
                $update = $this->c_model->saveupdate('dt_aeps',$save_dt,null,array('id'=>$getData['id']));
                }
            }else if( ($countwt == 1 ) && $agentamount ){
            $update = $this->c_model->saveupdate('dt_aeps',$save_dt,null,array('id'=>$getData['id']));
            } 
        }
        /* check wallet for this registration end */
          
 



}else{
$response['status'] = FALSE;
$response['message'] = 'Bad request!'; 
}

        header("Content-Type: application/json");
        echo json_encode( $response );

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