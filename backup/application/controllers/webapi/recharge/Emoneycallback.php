<?php defined("BASEPATH") OR exit("No Direct Access Allowed!");

class Emoneycallback extends CI_Controller{
	
	public function __construct(){
		parent::__construct();  
                $this->load->library('rechargeapi'); 
		}
		

	public function index(){

		header("Pragma: no-cache");
		header("Cache-Control: no-cache");
		header("Expires: 0");
		
         $rech_data = requestJson();
         $response = array();
   
 //print_r($rech_data);

 if( ($_SERVER['REQUEST_METHOD'] == 'POST') ){
 
        $Merchantrefno = isset($rech_data['reqid'])?$rech_data['reqid']:'';
        $Status = 'PROCESSED'; 
        $where['id'] = isset($rech_data['user_id'])?$rech_data['user_id']:''; 
        $action = isset($rech_data['action'])?$rech_data['action']:'';
        $serviceid = isset($rech_data['serviceid'])?$rech_data['serviceid']:'';
        $tableid = isset($rech_data['id'])?$rech_data['id']:''; 
        $final_status = isset($rech_data['final_status'])?$rech_data['final_status']:'yes';

        if($serviceid == 5){
            $servicename = 'Mobile Recharge';
            $servicename2 = 'mob_rech';
        }else if($serviceid == 3){
            $servicename = 'DTH Recharge';
            $servicename2 = 'dth_rech';
        }


  if($rech_data['reqid'] && $rech_data['user_id'] && $rech_data['id'] ){ 
        
        $obj = new $this->rechargeapi;
        $buffer = $obj->emoney_transaction_status($Merchantrefno);

        if(isset($buffer['Status']) && ($buffer['Status']) ){
                
                if( $final_status == 'yes' ){
                $response['status'] = true;
                $response['data'] = $buffer;
                $response['message'] = 'success!';
                header("Content-Type: application/json");
                echo json_encode( $response );
                exit;
                }


                $Status = $buffer['Status'];
                $newstatus = strtolower($Status); 
       

        /*check status */
        if(($action == 'check') && in_array( strtoupper($Status), ['SUCCESS','FAILURE'])){
         
                $save_dt['op_transaction_id'] = !empty($buffer['OP_Transaction_ID'])?$buffer['OP_Transaction_ID']:'';
                $save_dt['apirefid'] = isset($buffer['eMoney_OrderID'])?$buffer['eMoney_OrderID']:'';
                $save_dt['status_update'] = date('Y-m-d H:i:s');
                $save_dt['status'] = $Status; 

                if($Status == 'SUCCESS'){
                  $save_dt['remark'] = 'Your transaction has been SUCCESSED.';
                  $update = $this->c_model->saveupdate('dt_rech_history',$save_dt,null,array('id'=>$rech_data['id']));
                }else if($Status == 'FAILURE'){
                  $save_dt['remark'] = isset($buffer['response'])?$buffer['response']:$buffer['MSG'];
                  $save_dt['final_status']='yes';
                }
                
                 


        $checkinreturnlist = in_array($newstatus, ['failure','na']);

        //if( $checkinreturnlist ) {
        if( $newstatus =='failure' ){

        $refund_orderid = 'RFND'.filter_var($Merchantrefno,FILTER_SANITIZE_NUMBER_INT );   
        $userdata = $this->c_model->getSingle('dt_users',$where,'id,uniqueid,user_type');
        $chwk_wt['userid'] = $rech_data['user_id']; 
        $chwk_wt['referenceid'] = $Merchantrefno;
        $chwk_wt['credit_debit'] = 'debit';
        $countwt = $this->c_model->countitem('dt_wallet',$chwk_wt ); 
        if($countwt == 1){
        $reversalAmount = false;    
        $wtkey = 'amount,id';
        $wt_Arr = $this->c_model->getSingle('dt_wallet',$chwk_wt ,$wtkey );
        $reversalAmount = $wt_Arr['amount']; 
        } 

        /*check refund amount */
        $rfnd_wt['userid'] = $rech_data['user_id']; 
        $rfnd_wt['referenceid'] = $refund_orderid;
        $rfnd_wt['credit_debit'] = 'credit';
        $rfnd_amount = $this->c_model->countitem('dt_wallet',$rfnd_wt );


        /* check wallet for this transaction start */ 
        $wtsave['userid'] = $userdata['id'];
        $wtsave['usertype'] = $userdata['user_type'];
        $wtsave['uniqueid'] = $userdata['uniqueid'];
        $wtsave['paymode'] = 'wallet';
        $wtsave['transctionid'] = 'NA';//$Merchantrefno;
        $wtsave['credit_debit'] = 'credit';
        $wtsave['upiid'] = NULL;
        $wtsave['bankname'] = NULL; 
        $wtsave['remark'] = 'Refund for order ID: '.$Merchantrefno;
        $wtsave['status'] = 'success'; 
        $wtsave['amount'] = (float)$reversalAmount;
        $wtsave['subject'] = $servicename2.'_refund';
        $wtsave['addby'] = $userdata['id']; 
        $wtsave['orderid'] = $refund_orderid; 
        $wtsave['odr'] = (float)$reversalAmount; 
        $wtsave['surch'] = '0';
        $wtsave['comi'] = '0';
        $wtsave['tds'] = '0'; 
        $wtsave['flag'] = 1;

        $postapiurl = APIURL.('webapi/wallet/Creditdebit');
        $upwtwhere['dts'] = $wtsave;  
        $header = array('auth: Access-Token='.WALLETOKEN );
        if( ($rfnd_amount == 0 ) && ($countwt == 1) && ($reversalAmount > 0) ){
            $upwt =  curlApis($postapiurl,'POST',$upwtwhere,$header); 
            if($upwt['status']){
              $update = $this->c_model->saveupdate('dt_rech_history',$save_dt,null,array('id'=>$rech_data['id']));

            }
        }else if( ($rfnd_amount == 1 ) && ($countwt == 1) && ($reversalAmount > 0) ){
              $update = $this->c_model->saveupdate('dt_rech_history',$save_dt,null,array('id'=>$rech_data['id']));
        }
        

        }
        /*chekc refund amount process end */
    } 


                
                
                $response['status'] = true;
                $response['data'] = $buffer;
                $response['message'] = 'success!'; 
        }else{
                $response['status'] = false;
                $response['data'] = $buffer;
                $response['message'] = 'failure!';
        }


}else{
$response['status'] = FALSE;
$response['message'] = 'Invalid Inputs!'; 
}

		

}else{
$response['status'] = FALSE;
$response['message'] = 'Bad request!'; 
}

        //header("Content-Type: application/json");
        echo json_encode( $response );

}

	

}
?>