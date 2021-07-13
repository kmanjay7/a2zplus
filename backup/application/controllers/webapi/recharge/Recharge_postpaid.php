<?php defined("BASEPATH") OR exit("No Direct Access Allowed!");

class Recharge_postpaid extends CI_Controller{
	
	public function __construct(){
		parent::__construct(); 
		$this->load->library('rechargeapi'); 
		}
		

	public function index(){

		header("Pragma: no-cache");
		header("Cache-Control: no-cache");
		header("Expires: 0");

		$response = array();
		$data = array();
		$request = requestJson();
		$serviceid = 9;
		//print_r($request);

	if( ($_SERVER['REQUEST_METHOD'] == 'POST') ){
				 $mobile = $request['mobileno'];
				 $operator = $request['operatorcode'];
				 $amount = $request['amount'];
				 $uniqueid = $request['uniqueid'];
				 $usertype = $request['usertype'];
				 $circle = isset($request['circle'])?trim($request['circle']):'';


				$where['uniqueid'] = $uniqueid;
				$where['user_type'] = $usertype;
				$keys = 'kyc_status, id';
				$userdata = $this->c_model->getSingle('users',$where,$keys);	
 
    if(!is_null($userdata)){
 

		if( !is_null($userdata) && $mobile && ( strlen($mobile) == 10 ) && $operator && $amount ){


		/*check wallet balance start here */
    	$ckbl['userid'] = $userdata['id'];
    	$ckbl['user_type'] = $usertype;
    	$wallet = checkwallet($ckbl);
    	/*check wallet balance end here */

        if( $wallet >= $amount ){ /*check wallet start here */


						$arr['mobile'] = $mobile;
						$arr['operator'] = $operator;
						$arr['amount'] = $amount;
						$arr['field1'] = isset($request['field1'])?$request['field1']:'';
						$arr['field2'] = isset($request['field2'])?$request['field2']:'';
						$arr['reqid'] = txnid();

						/* insert data in recharge table start */
						$whereop['serviceid'] = $serviceid;
						$whereop['op_code'] = $operator;
						$op_code_data = $this->c_model->getSingle('operators_code',$whereop,'operatorid,apiid'); 
						$adddate = date('Y-m-d H:i:s');
						$save['user_id'] = $userdata['id'];
						$save['apiid'] = $op_code_data['apiid'];
						$save['serviceid'] = $serviceid;
						$save['reqid'] = $arr['reqid'];
						$save['status'] = 'REQUEST';
						$save['remark'] = '';
						$save['balaftrech'] = '';
						$save['amount'] = $arr['amount'];
						$save['mobileno'] = $arr['mobile'];
						$save['field1'] = $arr['field1'];
						$save['field2'] = $arr['field2'];
						$save['ec'] = '';
						$save['apirefid'] = '';
						$save['operatorid'] = $op_code_data['operatorid'];
						$save['circleid'] = $this->c_model->getSingle('operator_circle',array('serviceid'=>$serviceid,'circle'=>$circle),'id');
						$save['add_date'] = $adddate;
						$save['nextupdate'] = date('Y-m-d H:i:s',strtotime(date('Y-m-d H:i:s').' +45 seconds'));
				        $save['noofround'] = 0;

                        $insertid = $this->c_model->saveupdate('rech_history',$save,$save);
						/* insert data in recharge table end */


		/* check wallet for this transaction start */ 
        $wtsave['userid'] = $userdata['id'];
        $wtsave['usertype'] = $usertype;
        $wtsave['uniqueid'] = $uniqueid;
        $wtsave['paymode'] = 'wallet';
        $wtsave['transctionid'] = txnid();
        $wtsave['credit_debit'] = 'debit';
        $wtsave['upiid'] = NULL;
        $wtsave['bankname'] = NULL; 
        $wtsave['remark'] = 'Recharge';
        $wtsave['status'] = 'success'; 
        $wtsave['amount'] = trim($arr['amount']); 
        $wtsave['subject'] = 'rech_mobile';
        $wtsave['addby'] = $userdata['id']; 
        $wtsave['orderid'] =  $insertid;

        $postapiurl = base_url('webapi/wallet/Creditdebit');
        $upwtwhere['dts'] = $wtsave;  
        $header = array('auth: Access-Token='.WALLETOKEN ); 
        /* check wallet for this registration end */





                        $arr['apiid'] = $op_code_data['apiid'];

						$obj = new $this->rechargeapi; 
						$buffer = $obj->rechargepostpaid($arr); 
						if(!is_null($buffer) && $insertid ){
							/*deduct wallet start*/
							if( isset($buffer['deductwallet']) && $buffer['deductwallet']){
 							$upwt = $wtsave['amount'] ? curlApis($postapiurl,'POST', $upwtwhere,$header ):array(); 
							}
							unset($buffer['deductwallet']);
							/*deduct wallet start*/
							$this->c_model->saveupdate('rech_history',$buffer,null,array('id'=>$insertid)); 
							$response['status']= TRUE;
							$response['data'] = $buffer;
							$response['message']= 'Status Updated Successfully!';
						}else{
							$response['status']= FALSE;
							$response['message']= 'No match found!';
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
$response['status']= FALSE;
$response['message']= 'Bad request!'; 
}

	header("Content-Type: application/json");
	echo json_encode( $response );
}

	

}
?>