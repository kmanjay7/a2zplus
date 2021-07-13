<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Transaction_history extends CI_Controller{
	
	 public function __construct() {
		parent::__construct();
	 }
	 
		
	public function index() {
			
			$response = array();
			    $data = array();  
			  
			   $table = 'dt_dmtlog';  
			$request = requestJson();
			
 /****  check Request  Status start ****/ 
 if( ($_SERVER['REQUEST_METHOD'] == 'POST') ){
			
	           
	           $user_id = $request['user_id'];
	           $usertype = $request['usertype'];
	           $senderid = $request['senderid'];
	           $filterby = $request['filterby'];
	           $requestparam = $request['requestparam'];
	           $limit = $request['limit'];
			   $start = $request['start'];
			   $start = $limit && $start ? $start*$limit : 0;
			   $orderby = $request['orderby']?$request['orderby']:'DESC';  
			   $serviceid = '6'; 

			   if(!$user_id){
				$response['status'] = FALSE;
				$response['message'] = 'User Id is Blank!'; 
				echo json_encode($response);
				exit;
			   }else if(!$usertype){
				$response['status'] = FALSE;
				$response['message'] = 'Usertype is Blank!'; 
				echo json_encode($response);
				exit;
			   }/*else if(!$senderid){
				$response['status'] = FALSE;
				$response['message'] = 'Sender id is Blank!'; 
				echo json_encode($response);
				exit;
			   }*/
			   

			   /*date function start*/
			   if( ($filterby == 'date') && $requestparam ){
					$explode = explode('|', $requestparam );
					$fromdate = $explode[0];
					$fromdate = date('Y-m-d',strtotime($fromdate));
					$todate = $explode[1];
					$todate = date('Y-m-d',strtotime($todate));
					if(($fromdate !='1970-01-01') && ($todate !='1970-01-01') ){
					$where['DATE(add_date) >='] = $fromdate;
					$where['DATE(add_date) <='] = $todate;
				    }
			   }
			
			$limit = !empty($limit) ? $limit : NULL;
			$start = !empty($start) ? $start : NULL; 

		    $trwhere['dt_dmtlog.userid'] = $user_id; 
            $trwhere['dt_dmtlog.usertype'] = $usertype;
            if($senderid){  
            $trwhere['dt_dmtlog.sender_id'] = $senderid; 
            } 
            $trwhere['dt_dmtlog.status !='] = 'REQUEST';  

            $select = 'dt_dmtlog.id,dt_sender.name as s_name, dt_sender.mobile as s_mobile, dt_benificiary.name as b_name, dt_benificiary.ac_number, dt_dmtlog.mode, dt_dmtlog.apiname, dt_dmtlog.orderid, dt_dmtlog.amount, dt_bank.bankname, dt_dmtlog.add_date, dt_dmtlog.status, dt_dmtlog.sur_charge, dt_dmtlog.ag_comi, dt_dmtlog.ag_tds,  dt_benificiary.ifsc_code,dt_dmtlog.sys_orderid,dt_dmtlog.respmsg,dt_dmtlog.ptm_rrn,dt_dmtlog.operatorname, dt_dmtlog.status_update, dt_scheme.sch_name,dt_users.uniqueid as agent_uniqueid,dt_dmtlog.usertype,dt_users.ownername,dt_sender.kyc_status ,dt_dmtlog.userid'; 
            $from = 'dt_dmtlog';

            $join[0]['table'] = 'dt_sender';
            $join[0]['joinon'] = 'dt_dmtlog.sender_id = dt_sender.id';
            $join[0]['jointype'] = 'LEFT';

            $join[1]['table'] = 'dt_benificiary';
            $join[1]['joinon'] = 'dt_benificiary.id = dt_dmtlog.benifi_id' ; 
            $join[1]['jointype'] = 'LEFT';

            $join[2]['table'] = 'dt_bank';
            $join[2]['joinon'] = 'dt_bank.id = dt_benificiary.bankname' ;  
            $join[2]['jointype'] = 'LEFT';

            $join[3]['table'] = 'dt_users';
            $join[3]['joinon'] = 'dt_users.id = dt_dmtlog.userid' ; 
            $join[3]['jointype'] = 'LEFT';

            $join[4]['table'] = 'dt_scheme';
            $join[4]['joinon'] = 'dt_users.scheme_type = dt_scheme.id' ; 
            $join[4]['jointype'] = 'LEFT'; 

            $groupby = null;//'sys_orderid' ; 
            $orderby = 'id '.$orderby ; 
            $getorcount = 'get';

           
            $getdataarr = $this->c_model->joinmultiple( $select, $trwhere, $from, $join, $groupby ,$orderby, $limit, $start, $getorcount ); 
 
			$getdata = array();
			if( !empty($getdataarr) && !is_null($getdataarr) ){ 

					foreach ($getdataarr as $key => $value) {
						 $arr['id'] = (string)$value['id'];
						 $arr['s_name'] = (string)$value['s_name'];
						 $arr['s_mobile'] = (string)$value['s_mobile'];
						 $arr['b_name'] = (string)$value['b_name'];
						 $arr['ac_number'] = (string)$value['ac_number'];
						 $arr['mode'] = (string)$value['mode'];
						 $arr['apiname'] = (string)$value['apiname'];
						 $arr['orderid'] = (string)$value['orderid'];
						 $arr['amount'] = (string)$value['amount'];
						 $arr['bankname'] = (string)$value['bankname'];
						 $arr['add_date'] = (string)date('d-M-Y h:i:s A',strtotime($value['add_date']));
						 $arr['status'] = (string)statusbtn_c($value['status']);
						 $arr['sur_charge'] = (string)$value['sur_charge'];
						 $arr['ag_comi'] = (string)$value['ag_comi'];
						 $arr['ag_tds'] = (string)$value['ag_tds'];
						 $arr['ifsc_code'] = (string)$value['ifsc_code'];
						 $arr['sys_orderid'] = (string)$value['sys_orderid'];
						 $arr['respmsg'] = (string)$value['respmsg'];
						 $arr['ptm_rrn'] = (string)$value['ptm_rrn'];
						 $arr['operatorname'] = (string)$value['operatorname'];
						 $arr['status_update'] = (string)(!empty($value['status_update'])? date('d-M-Y h:i:s A',strtotime($value['status_update'])):'');
						 $arr['sch_name'] = (string)$value['sch_name'];
						 $arr['agent_uniqueid'] = (string)$value['agent_uniqueid'];
						 $arr['usertype'] = (string)$value['usertype'];
						 $arr['ownername'] = (string)$value['ownername'];
						 $arr['kyc_status'] = (string)$value['kyc_status'];
						 $arr['printurl'] = (string) ADMINURL.'ag/Print_reciept?utp='.md5($value['sys_orderid']).'&apptype=app&id='.md5($value['userid']).'&pdf=yes'; 
						 array_push($getdata, $arr);
					}

			 $status = 1; 
			}else{ $status = 2; } 
			 
			 
			
			if($status == 1 ){ 
			$response['status'] = TRUE;
			$response['data'] = $getdata ; 
		    $response['message'] = "Request Successfull";
			}else if($status == 2 ){ 
			$response['status'] = FALSE;
		    $response['message'] = "No record match!";		
			} 
			
		 
		
/*token check end*/	
}else{ 
	$response['status'] = FALSE;
    $response['message'] = "Bad Request!";
}
		
	    header("Content-Type:application/json");
		echo json_encode( $response );
		
	
	 }
			
}
?>