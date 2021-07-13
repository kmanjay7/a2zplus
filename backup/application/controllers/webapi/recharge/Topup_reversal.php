<?php defined("BASEPATH") OR exit("No Direct Access Allowed!");

class Topup_reversal extends CI_Controller{
	
	public function __construct(){
		parent::__construct();    
		}
		

	public function index(){

		header("Pragma: no-cache");
		header("Cache-Control: no-cache");
		header("Expires: 0");
		
         $rech_data = requestJson();
         $response = array();
   
 //print_r($rech_data);

 if( ($_SERVER['REQUEST_METHOD'] == 'POST') ){
 
        $orderid = isset($rech_data['orderid'])?$rech_data['orderid']: false;

        if(!$orderid){
            $response['status'] = false;
            $response['message'] = 'Order ID is Blank!';          
        }

     
   //  print_r( $getData ); exit;

       

       


        $select = 'a.id,a.userid,a.usertype,a.amount,c.uniqueid,b.serviceid';
        $where['a.referenceid'] = $orderid;
        $from = 'dt_wallet as a';
        $join[0]['table'] = 'dt_rech_history as b';
        $join[0]['joinon'] = 'a.referenceid = b.reqid';
        $join[0]['jointype'] = 'LEFT';
        $join[1]['table'] = 'dt_users as c';
        $join[1]['joinon'] = 'c.id = a.userid and c.user_type = a.usertype';
        $join[1]['jointype'] = 'LEFT';

        $groupby = null; 
        $orderby = null;
        $limit = null;
        $offset = null;
        $getorcount = 'get';
        $inkey = null;
        $inlistarray = null;

        $wtlist = $this->c_model->joinmultiple( $select, $where, $from, $join, $groupby ,$orderby, $limit, $offset, $getorcount, $inkey = null, $inlistarray = null );

//print_r($wtlist);exit;

        if(!empty($wtlist)){


foreach ($wtlist as $key => $value) {
        
       
       $wtorderid = 'RVSL'.filter_var($orderid,FILTER_SANITIZE_NUMBER_INT );
       $credit_debit = 'debit';

        if($value['serviceid'] == 5){
            $remark = 'Reversal for order ID: '.$orderid;
            $subject = 'mob_rech_rvsl'; 

            if($value['usertype']=='AGENT'){
                $remark = 'Refund for order ID: '.$orderid;
                $subject = 'mob_rech_refund';
                $wtorderid = 'RFND'.filter_var($orderid,FILTER_SANITIZE_NUMBER_INT );
                $credit_debit = 'credit';
            }
            
        }else if($value['serviceid'] == 3){
            $remark = 'Reversal for order ID: '.$orderid;
            $subject = 'dth_rech_rvsl';

            if($value['usertype']=='AGENT'){
                $remark = 'Refund for order ID: '.$orderid;
                $subject = 'dth_rech_refund';
                $wtorderid = 'RFND'.filter_var($orderid,FILTER_SANITIZE_NUMBER_INT );
                $credit_debit = 'credit';
            } 
        }else if($value['serviceid'] == 4){
            $remark = 'Reversal for order ID: '.$orderid;
            $subject = 'elc_rvsl';

            if($value['usertype']=='AGENT'){
                $remark = 'Refund for order ID: '.$orderid;
                $subject = 'electricity_refund';
                $wtorderid = 'RFND'.filter_var($orderid,FILTER_SANITIZE_NUMBER_INT );
                $credit_debit = 'credit';
            } 
        }


        $amount = $value['amount'];



 
                 
         /*update status in recharge table*/        
        if($value['usertype'] == 'AGENT'){
        $save_rch['status'] = 'FAILURE';
        $save_rch['remark'] = 'Amount Refunded Successfully';
        $where_rch['reqid'] =  $orderid;
        $this->c_model->saveupdate('dt_rech_history',$save_rch,null, $where_rch );
        }

 
       
        $chwk_wt['userid'] = $value['userid']; 
        $chwk_wt['referenceid'] = $orderid; 
        $chwk_wt['credit_debit'] = $credit_debit;
        $chwk_wt['subject'] = $subject;
        $countwt = $this->c_model->countitem('dt_wallet',$chwk_wt ); 
        if($countwt == 1){
            

        }else if($countwt == 0){
        
        /* check wallet for this transaction start */ 
        $wtsave['userid'] = $value['userid'];
        $wtsave['usertype'] = $value['usertype'];
        $wtsave['uniqueid'] = $value['uniqueid'];
        $wtsave['paymode'] = 'wallet';
        $wtsave['transctionid'] = 'NA';
        $wtsave['credit_debit'] = $credit_debit;
        $wtsave['upiid'] = '';
        $wtsave['bankname'] = ''; 
        $wtsave['remark'] = $remark;
        $wtsave['status'] = 'success'; 
        $wtsave['amount'] = (float)$amount; 
        $wtsave['subject'] = $subject;
        $wtsave['addby'] = $value['userid']; 
        $wtsave['orderid'] = $wtorderid; 

        $postapiurl = APIURL.('webapi/wallet/Creditdebit');
        $upwtwhere['dts'] = $wtsave;  
        $header = array('auth: Access-Token='.WALLETOKEN );
        $upwt = ($amount > 0) ? curlApis($postapiurl,'POST',$upwtwhere,$header):array();
         
         if(!$upwt['status']){
            $response['status'] = FALSE;
            $response['message'] = 'Already Refunded!';
         }else{
            $response['status'] = true;
            $response['message'] = 'Request Completed Successfully!';
         }
        
        }
        /* check wallet for this registration end */

}//end or for each loop

       
                
}else{
 $response['status'] = FALSE;
 $response['message'] = 'No Record!';
}     
             

		

}else{
$response['status'] = FALSE;
$response['message'] = 'Bad request!'; 
}

        header("Content-Type: application/json");
        echo json_encode( $response );

}

	

}
?>