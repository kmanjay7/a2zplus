<?php defined("BASEPATH") OR exit("No Direct Access Allowed!");

class Goldpay_callback extends CI_Controller{
	
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
   


 if( ($_SERVER['REQUEST_METHOD'] == 'POST') ){
 
        $Merchantrefno = $rech_data['reqid'];
        $Status = ''; 
        $where['id'] = $rech_data['user_id']; 
        $action = $rech_data['action'];
        $tableid = isset($rech_data['id'])?$rech_data['id']:''; 
        $final_status = isset($rech_data['final_status'])?$rech_data['final_status']:'yes';

        $serviceid = $rech_data['serviceid'];
        $lapuid = !empty($rech_data['lapuid'])?$rech_data['lapuid']:false; 

        if($serviceid == 5){
            $servicename = 'Mobile Recharge';
            $servicename2 = 'mob_rech';
        }else if($serviceid == 3){
            $servicename = 'DTH Recharge';
            $servicename2 = 'dth_rech';
        } 

if($rech_data['reqid'] && $rech_data['user_id'] && $rech_data['id'] ){        

        $obj = new $this->rechargeapi;
        $buffer = $obj->goldpay_rech_status($Merchantrefno);

        if(empty($buffer)){
            $response['status'] = false;
            $response['data'] = $buffer;
            $response['message'] = 'No Response From API!';
            header("Content-Type: application/json");
            echo json_encode( $response );
            exit;
        }
  
        
         
if(isset($buffer['status']) && ($buffer['status']) ){ /*buffer init start*/
                    $Status = strtoupper( $buffer['status'] );  

                    if( $final_status == 'yes' ){
                    $response['status'] = true;
                    $response['data'] = $buffer;
                    $response['message'] = 'success!';
                    header("Content-Type: application/json");
                    echo json_encode( $response );
                    exit;
                    }
           
        /*check success failure status start*/
        if(($action == 'check') && in_array($Status, ['SUCCESS','FAILURE'])){

                $save_dt['op_transaction_id'] = $buffer['op_transaction_id'];
                $save_dt['apirefid'] = $save_dt['apirefid']; 
                $save_dt['status'] = $Status;
                $save_dt['status_update'] = date('Y-m-d H:i:s');


                if($Status == 'SUCCESS'){
                  $save_dt['remark'] = 'Your transaction has been SUCCESSED.';
                  $update = $this->c_model->saveupdate('dt_rech_history',$save_dt,null,array('id'=>$tableid ));
                }else if($Status == 'FAILURE'){
                  $save_dt['remark'] = !empty($buffer['remark'])?$buffer['remark']:'INTERNAL_ERROR';
                  $save_dt['final_status']='yes';
                } 


                        

        /*chekc refund amount process start */
        if( (strtolower($Status)=='failure') ){
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
    /*check success failure status end*/

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