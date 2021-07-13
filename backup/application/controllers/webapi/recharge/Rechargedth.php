<?php defined("BASEPATH") OR exit("No Direct Access Allowed!");

class Rechargedth extends CI_Controller{
	var $serviceid;
	public function __construct(){
		parent::__construct(); 
		$this->load->library('rechargeapi'); 
		$this->serviceid = 3;
		}
		
    public function get_bal($uid,$utype){
		$where['userid'] = $uid;
		$where['usertype'] = $utype; 
		$where['status !='] = 'failed'; 
		$inkey = 'credit_debit';
		$invalue = 'credit,debit';
		$res = $this->c_model->getSingle('wallet' ,$where, 'id,finalamount','id DESC', 1, $inkey, $invalue );
		$amt = !empty($res)?$res['finalamount']: 0;
		return $amt;
	}	


	public function index(){

		header("Pragma: no-cache");
		header("Cache-Control: no-cache");
		header("Expires: 0");

		$response = array();
		$data = array();
		$request = requestJson();
		$serviceid = $this->serviceid;
		$orderid = '';
		//print_r($request);

	if( ($_SERVER['REQUEST_METHOD'] == 'POST') ){
				 $mobile = trim($request['customercv']);
				 $operator = trim($request['operator']);
				 $amount = $request['amount'];
				 $uniqueid = $request['uniqueid'];
				 $usertype = $request['usertype']; 


				$where['uniqueid'] = $uniqueid;
				$where['user_type'] = $usertype;
				$keys = 'kyc_status, id,scheme_type';
				$userdata = $this->c_model->getSingle('users',$where,$keys);	
 
    if(!is_null($userdata)){
    	$scheme = $userdata['scheme_type'];

    	/*check processing and 5 minutes request start*/
    	$wherepending['mobileno'] = $mobile; 
    	$lastentry = $this->c_model->getSingle('dt_rech_history',$wherepending,'id,status,amount,add_date','id DESC',1 ); 
    	if(!empty($lastentry)){
    		$now = strtotime( date('Y-m-d H:i:s') ); 
    		$lastentrytime = strtotime($lastentry['add_date']);
    		$timediff = twodecimal(abs($now - $lastentrytime) / 60);
    		if( in_array($lastentry['status'],['PROCESSED','PENDING']) ){
				$response['status'] = false;
				$response['message'] = 'Transaction Already Pending...'; 
				echo json_encode($response);
				exit;
    		}else if( ($timediff < 5) && ((float)$lastentry['amount'] == (float)$amount ) && ($lastentry['status'] == 'SUCCESS') ){
				$response['status'] = false;
				$response['message'] = "Please wait for 5 minutes to initiate new entry with same amount for same customer/dth number..."; 
				echo json_encode($response);
				exit;
    		}
					
		} 
    	/*check processing and 5 minutes request end*/
 

		if( !is_null($userdata) && $mobile && $operator && $amount ){


		/*check wallet balance start here */
    	$wallet = $this->get_bal($userdata['id'],$usertype);
    	/*check wallet balance end here */

        if( $wallet >= $amount ){ /*check wallet start here */
				$orderid = '';
				$orderid = 'DTH'.date('YmdHis').$userdata['id'].rand(100,999);
				$arr['mobile'] = $mobile; 
				$arr['amount'] = $amount;
				$arr['field1'] = isset($request['field1'])?$request['field1']:'';
				$arr['field2'] = isset($request['field2'])?$request['field2']:'';
				$arr['reqid'] = $orderid;

				/* find and match api operator codes start */
				$op_where['service'] = $serviceid;
				$op_where['operator'] = $operator;
				$op_data_arr = $this->c_model->getSingle('operators',$op_where,'id,currentapiid'); 
				if(empty($op_data_arr)){
					$response['status'] = false;
					$response['message'] = 'No Current Api Set For DTH Recharge'; 
					echo json_encode($response);
					exit;
				}

				$operatorid = $op_data_arr['id'];
				$currentapiid = $op_data_arr['currentapiid'];

				$whereop['serviceid'] = $serviceid;
				$whereop['operatorid'] = $operatorid;
				$whereop['apiid'] = $currentapiid;
				$op_code_data = $this->c_model->getSingle('operators_code',$whereop,'operatorid,op_code,apioperatorname');
				$arr['operator'] = isset($op_code_data['op_code'])?$op_code_data['op_code']:false; 

				$apioperatorname = isset($op_code_data['apioperatorname'])?$op_code_data['apioperatorname']:'';


				if( ($whereop['apiid'] == 5 ) && ( !$apioperatorname || !is_numeric($apioperatorname) ) ){
					$response['status'] = false;
					$response['message'] = 'Lapu Id not Set this API For Mobile Recharge'; 
					echo json_encode($response);
					exit;
				}


				if(empty($arr['operator'])){
					$response['status'] = false;
					$response['message'] = 'No Operator Code Set this API For DTH Recharge'; 
					echo json_encode($response);
					exit;
				}
				

/*get Comission For Agent start code*/ 
$apiid = $op_data_arr['currentapiid'];
$comi_Arr = $this->get_commision($amount,$scheme,false,$operatorid );
/*Note If database configured with apiid in tabel set comission remove false status form above function */
$ag_comi = isset($comi_Arr['ag_comi'])?$comi_Arr['ag_comi']:false;
$ag_tds = isset($comi_Arr['ag_tds'])?$comi_Arr['ag_tds']:false;
$finalAmount = ( (float)$arr['amount'] - (float)$ag_comi );  
/*get Comission For Agent start code*/


				/* insert data in recharge table start */
				$adddate = date('Y-m-d H:i:s');
				$save['user_id'] = $userdata['id'];
				$save['apiid'] = $op_data_arr['currentapiid'];
				$save['serviceid'] = $serviceid;
				$save['reqid'] = $orderid;
				$save['status'] = 'PROCESSED';
				$save['remark'] = '';
				$save['balaftrech'] = '';
				$save['amount'] = $arr['amount'];
				$save['mobileno'] = $arr['mobile'];
				$save['field1'] = $arr['field1'];
				$save['field2'] = $arr['field2'];
				$save['ec'] = '';
				$save['apirefid'] = '';
				$save['operatorid'] = $op_code_data['operatorid']; 
				$save['add_date'] = $adddate;
				$save['operatorname'] = $operator;
				$save['ag_comi'] = $ag_comi;
				$save['ag_tds'] = $ag_tds;
				$save['nextupdate'] = date('Y-m-d H:i:s',strtotime(date('Y-m-d H:i:s').' +31 seconds'));
				$save['noofround'] = 0;

				$dupl_entry = [];
				$dupl_entry['reqid'] = $orderid;
				
 if($op_data_arr['currentapiid'] &&$op_code_data['operatorid']&&$op_code_data['op_code'] ){
                $insertid = $this->c_model->saveupdate('rech_history',$save,$dupl_entry);
                
                if(!$insertid){
                	$response['status']= FALSE;
			        $response['message']= 'Some Error Occured!';
					header("Content-Type: application/json");
					echo json_encode( $response );
					exit; 
                } 
				/* insert data in recharge table end */

 

		/* check wallet for this transaction start */ 
        $wtsave['userid'] = $userdata['id'];
        $wtsave['usertype'] = $usertype;
        $wtsave['uniqueid'] = $uniqueid;
        $wtsave['paymode'] = 'wallet';
        $wtsave['transctionid'] = $insertid;
        $wtsave['credit_debit'] = 'debit';
        $wtsave['upiid'] = NULL;
        $wtsave['bankname'] = NULL; 
        $wtsave['remark'] = 'DTH Recharge'; 
        $wtsave['status'] = 'success'; 
        $wtsave['amount'] = (float)($finalAmount); 
        $wtsave['subject'] = 'dth_rech';
        $wtsave['addby'] = $userdata['id']; 
        $wtsave['orderid'] = $orderid;

        $wtsave['odr'] = $amount;
        $wtsave['surch'] = '0';
        $wtsave['comi'] = $ag_comi;
        $wtsave['tds'] = $ag_tds;
        $wtsave['flag'] = 1;

        $wallet_apiurl = base_url('webapi/wallet/Creditdebit');
        $upwtwhere['dts'] = $wtsave;  
        $header = array('auth: Access-Token='.WALLETOKEN ); 
        $upwt = $wtsave['amount'] ? curlApis($wallet_apiurl,'POST', $upwtwhere,$header ):array('status'=>false);

        if(!$upwt['status']){
        	$delete = $this->c_model->delete('rech_history',['id'=>$insertid]);
			$response['status']= FALSE;
			$response['message']= $upwt['message'];
			header("Content-Type: application/json");
			echo json_encode( $response );
			exit; 
        }
        /* check wallet for this registration end */



			$obj = new $this->rechargeapi;
			$buffer = null;
			/********** for multi money ************/
            if( $apiid && $apiid == 1 ){
				$buffer = $obj->mani_dth_recharge($arr);  
            }
            /********** for e-money ****************/
            else if($apiid && $apiid == 2){ 
				$buffer = $obj->emoney_dth_rech($arr);  
            }
            /********** for e-money ****************/
            else if($apiid && $apiid == 5){ 
            	$arr['field1'] = $apioperatorname;
				$buffer = $obj->mrobotics_recharge($arr);  
            }



		 
						if(!is_null($buffer) && $insertid ){
							$apistatus = $buffer['deductwallet'];
							/*remove deduct wallet start*/
							unset($buffer['deductwallet']);
							/*remove deduct wallet end*/
							if(in_array($buffer['status'], ['FAILURE','REFUND'])){
								$buffer['status'] = 'PROCESSED';
							}
							$this->c_model->saveupdate('rech_history',$buffer,null,array('id'=>$insertid)); 
				if(!empty($buffer['balaftrech'])){
				$this->c_model->saveupdate('dt_recharge_api',array('balance'=>twodecimal($buffer['balaftrech']) ),null,array('id'=>$op_data_arr['currentapiid']) );
				}
							
								
								//if( $apistatus ){
									$response['status'] = TRUE;
									$response['message'] = 'Request Accepted!';
								//}else{
								//	$response['status'] = FALSE;
									//$response['message'] = 'Some Network Error !';
								//} 
								     $response['data'] = $buffer;
								
						}else{
							$response['status']= FALSE;
							$response['message']= 'Request Accepted!';
						}

		}else if(!isset($op_data_arr['currentapiid']) && !$op_data_arr['currentapiid']){ 
			$response['status']= FALSE;
			$response['message']= 'No Current Api Set For Recharge!';
		}else if(!isset($op_data_arr['op_code']) && !$op_data_arr['op_code']){ 
			$response['status']= FALSE;
			$response['message']= 'No op_code for this Api!';
		}	




		}else{
			$response['status']= FALSE;
		    $response['message']= 'Wallet balance is low for this transaction!';
		}
		/*check wallet start here */				

		}else{
		$response['status']= FALSE;
		$response['message']= 'Please fill the required fields!';
		}


			
	}else{
	$response['status']= FALSE;
	$response['message']= 'Please fill the required fields!';
	}
	


}else{ 
$response['status'] = FALSE;
$response['message'] = 'Bad request!'; 
}

	header("Content-Type: application/json");
	echo json_encode( $response );
}

	

public function get_commision($amount=false,$scheme=false,$apiid=false,$opid=false){

		$table = 'dt_operators';  
		$commision = false; 
		$serviceid = $this->serviceid;  

$output['commision'] = false; 
 
					$comwhere['user_type'] = 'AGENT';
					$comwhere['operatorid'] = $opid;
					if($scheme){ $comwhere['scheme_type'] = $scheme; }
					if($apiid){ $comwhere['apiid'] = $apiid; } 
					$comwhere['serviceid'] = $serviceid;
					$commssionarray = $this->c_model->getSingle('dt_set_commission', $comwhere, 'commision_fixed, commision_percent');
 
					 

							if(!is_null($commssionarray) && !empty($commssionarray)){

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
$output['ag_comi'] = (float)($commision - $tds);
$output['ag_tds'] = $tds;
 
            return $output;
 
	}

}
?>