<?php defined("BASEPATH") OR exit("No Direct Access Allowed!");

class Env_aeps_resp extends CI_Controller{ 
    public function __construct(){
	parent::__construct();
	}
		
public function index(){

		$response = array();
		$data = array();
		 

//if( ($_SERVER['REQUEST_METHOD'] == 'GET') ){



				$ip = $this->get_IP_address();

        if( $ip != '180.179.213.127' ){
        $response['status'] = FALSE;
        $response['message'] = 'IP misconfigured!';
        //header("Content-Type: application/json");
        //echo json_encode( $response );
        //exit;
        }

 
			
         $param = $this->input->get()?$this->input->get():$this->input->post();
         $testsave['testdata'] = json_encode($param);
         $testsave['testip'] = $ip;
         $testsave['indate'] = date('Y-m-d H:i:s');
         echo $this->c_model->saveupdate('dt_test',$testsave);

exit;
//print_r($param);

/*check Entry in table*/
$checkhw['sys_orderid'] = $param['agent_id'];
$checkhw['status !='] = 'SUCCESS'; 
$getArray = $this->c_model->getSingle('dt_aeps',$checkhw ,'id,sys_orderid,status,mode,ag_comi,amount,userid,usertype');

$agent_amount = (float)$getArray['amount'] + (float)$getArray['ag_comi'];
 
if(empty($getArray)){
        $response['status'] = FALSE;
        $response['message'] = 'No record in our database!';
        header("Content-Type: application/json");
        echo json_encode( $response );
        exit;
}

$id = $getArray['id'];
$mode = $getArray['mode'];

if( $mode != 'WAP' ){
        $response['status'] = FALSE;
        $response['message'] = 'Transaction Mode is Wrong!';
        header("Content-Type: application/json");
        echo json_encode( $response );
        exit;
}


/*update response in table */
$inputstatus = isset($param['s_tatus'])?trim($param['s_tatus']):'';
$up['status'] = isset($param['s_tatus'])?trim($param['s_tatus']):$getArray['status'];
$up['api_orderid'] = isset($param['ipay_id'])?trim($param['ipay_id']):'';
$up['banktxnid'] = isset($param['opr_id'])?trim($param['opr_id']):'';
$up['respmsg'] = isset($param['res_msg'])?trim($param['res_msg']):'';
$up['api_status_on'] = date('Y-m-d H:i:s');


if( $param['res_code'] == 'TXN' ){
  if($up['status']=='REFUND'){ $up['status'] = 'FAILED'; }
//$update = $this->c_model->saveupdate('dt_aeps',$up,null,['id'=>$id] ); 
}



if( ($param['res_code']=='TXN') && ($mode == 'WAP') && (($inputstatus=='SUCCESS') ||  ($inputstatus=='REFUND')) ){ 

  /*check wallet entry */
$wtchk['referenceid'] = $getArray['sys_orderid'];
$wtchk['credit_debit'] = 'credit';
$wtchk['subject'] = 'aeps_m_t';
$wtchk['userid'] = $getArray['userid']; 

$countitem = $this->c_model->countitem('dt_wallet_aeps',$wtchk);


 /*maintane aeps wallet start here */ 
 $userid = $getArray['userid'];
 $getaddby = $this->c_model->getSingle('dt_users',array('id'=>$userid),'uniqueid,id');

        $wt['userid'] = $userid;
        $wt['usertype'] = trim($getArray['usertype']);
        $wt['uniqueid'] = trim($getaddby['uniqueid']);  
        $wt['paymode'] = 'wallet';
        $wt['transctionid'] = 'NA';
        $wt['credit_debit'] = 'credit';
        $wt['upiid'] = '';
        $wt['bankname'] = ''; 
        $wt['remark'] = 'Aeps - Cash Withdrawal';
        $wt['status'] = 'success'; 
        $wt['amount'] = $agent_amount; 
        $wt['subject'] = 'aeps_m_t';
        $wt['addby'] = $userid;
        $wt['orderid'] = $getArray['sys_orderid'];
        $walleturl = ADMINURL.('webapi/wallet/Creditdebit_aeps'); 
        $headerwt = array('auth: Access-Token='.AEPS_FUND_TXN_TOKEN ); 
        $waltwhere['dts'] = $wt;
          if(($countitem == 0) && $agent_amount && ($inputstatus=='SUCCESS') ){
            //$upwt = curlApis($walleturl,'POST', $waltwhere,$headerwt );
          }else if(($countitem ==1) && $agent_amount && ($inputstatus == 'REFUND')){

            $wt['remark'] = 'Refund for orderid: '.$getArray['sys_orderid'];
            $wt['orderid'] = 'RFND'.filter_var($getArray['sys_orderid'],FILTER_SANITIZE_NUMBER_INT );
            $wt['subject'] = 'aeps_tr_bk_refund';
            $wt['credit_debit'] = 'debit';
            $waltwhere['dts'] = $wt;
            //$upwt = curlApis($walleturl,'POST', $waltwhere,$headerwt );
          } 
   /*maintane aeps wallet end here */
}

  $response['status'] = true;
  $response['message']= 'Success!';
			
			
/*}else{ 
	$response['status'] = FALSE;
	$response['message']= 'Bad request!'; 
}*/

   	header("Content-Type: application/json");
		echo json_encode( $response );
}


public function get_IP_address(){
    foreach (array('HTTP_CLIENT_IP',
                   'HTTP_X_FORWARDED_FOR',
                   'HTTP_X_FORWARDED',
                   'HTTP_X_CLUSTER_CLIENT_IP',
                   'HTTP_FORWARDED_FOR',
                   'HTTP_FORWARDED',
                   'REMOTE_ADDR') as $key){
        if (array_key_exists($key, $_SERVER) === true){
            foreach (explode(',', $_SERVER[$key]) as $IPaddress){
                $IPaddress = trim($IPaddress); // Just to be safe

                if (filter_var($IPaddress,
                               FILTER_VALIDATE_IP,
                               FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)
                    !== false) {

                    return $IPaddress;
                }
            }
        }
    }
}
 
 
}
?>