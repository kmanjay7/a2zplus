<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Money_transaction_report extends CI_Controller{
	
	 public function __construct() {
		parent::__construct();
	 }
	 
		
	public function index() {
			
			$response = array();
			    $data = array();  
			  
			   $table = 'dt_dmtlog';  
			$request = requestJson();

			$inkey = null;
          $invalue = null;
			
 /****  check Request  Status start ****/ 
 if( ($_SERVER['REQUEST_METHOD'] == 'POST') ){
			
	           
	           $user_id = $request['user_id'];
	           $usertype = $request['usertype'];
	           $transaction = $request['transaction'];
	           $daterange = $request['daterange'];
	           $filterby = $request['filterby'];
	           $requestparam = $request['requestparam'];
	           $limit = $request['limit'];
			   $start = $request['start'];
			   $start = $limit && $start ? $start*$limit : 0;
			   $orderby = $request['orderby']?$request['orderby']:'DESC';  
			   $serviceid = 6; 

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
			   } 
			   

			   $where['a.userid'] = $user_id;
			   $where['a.usertype'] = $usertype;
			   $where['DATE(a.add_date)'] = date('Y-m-d');

			   /*date function start*/
			   if( $daterange ){
					$explode = explode('|', $daterange );
					$fromdate = $explode[0];
					$fromdate = date('Y-m-d',strtotime($fromdate));
					$todate = $explode[1];
					$todate = date('Y-m-d',strtotime($todate));
					if(($fromdate !='1970-01-01') && ($todate !='1970-01-01') ){ 
						unset($where['DATE(a.add_date)']);
					$where['DATE(a.add_date) >='] = $fromdate;
                    $where['DATE(a.add_date) <='] = $todate;
				    }
			   }


			 if( $transaction == 'success' ){ 
                $where['a.status'] = 'SUCCESS';

             }else if( $transaction == 'failed' ){
                unset($where['a.status !=']); 
                $where['a.status'] = 'FAILURE';

             }else if( $transaction == 'pending' ){ 
                $inkey = 'a.status';
                $invalue = 'PENDING,PROCESSED'; 
             }else if( $transaction == 'imps' ){ 
                $where['a.mode'] = 'IMPS';

             }else if( $transaction == 'neft' ){ 
                $where['a.mode'] = 'NEFT';

             }else if( $transaction == 'dmt1' ){ 
                $where['a.apiname'] = 'paytm';

             }else if( $transaction == 'dmt2' ){ 
                $where['a.apiname'] = '';

             }
 



             if( $filterby=='orderid' && $requestparam ){ 
                $where['a.orderid'] = $requestparam;
             
             }else if( $filterby=='txnid' && $requestparam ){ 
                $where['a.ptm_rrn'] = $requestparam;

             }else if( $filterby=='mob' && $requestparam ){ 
                $where['dt_sender.mobile'] = $requestparam;

             }else if( $filterby=='ac' && $requestparam ){ 
                $where['b.ac_number'] = $requestparam;

             } 

             
			
			

		    $select = 'a.sys_orderid,a.id,dt_sender.name as s_name, dt_sender.mobile as s_mobile, b.name as b_name, b.ac_number, a.mode, a.apiname, a.orderid, a.amount, c.bankname, a.add_date, a.status, a.sur_charge, a.ag_comi, a.ag_tds, a.banktxnid,a.operatorname,a.ptm_rrn, a.status_update, e.sch_name,d.uniqueid as agent_uniqueid,a.usertype,d.ownername,dt_sender.kyc_status,b.ifsc_code,a.respmsg,a.complaint,a.userid '; 
            $from = 'dt_dmtlog as a'; 
            $join[0]['table'] = 'dt_sender';
            $join[0]['joinon'] = 'a.sender_id = dt_sender.id';
            $join[0]['jointype'] = 'LEFT';

            $join[1]['table'] = 'dt_benificiary as b';
            $join[1]['joinon'] = 'b.id = a.benifi_id' ; 
            $join[1]['jointype'] = 'LEFT';

            $join[2]['table'] = 'dt_bank as c';
            $join[2]['joinon'] = 'c.id = b.bankname' ;  
            $join[2]['jointype'] = 'LEFT';

            $join[3]['table'] = 'dt_users as d';
            $join[3]['joinon'] = 'd.id = a.userid' ; 
            $join[3]['jointype'] = 'LEFT';

            $join[4]['table'] = 'dt_scheme as e';
            $join[4]['joinon'] = 'd.scheme_type = e.id' ; 
            $join[4]['jointype'] = 'LEFT'; 
 
            $groupby = null; 
            $orderby = 'a.id '.$orderby ; 
            $getorcount = 'get';
            $limit = !empty($limit) ? $limit : NULL;
			$start = !empty($start) ? $start : NULL; 

            $getdataarr = $this->c_model->joinmultiple( $select, $where, $from, $join, $groupby ,$orderby, $limit, $start, $getorcount, $inkey, $invalue  );

             
 
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
						 $arr['complaint'] = (string)$value['complaint'];
						 $arr['printurl'] = (string) ADMINURL.'ag/Print_reciept?utp='.md5($value['sys_orderid']).'&apptype=app&id='.md5($value['userid']).'&f='.$value['sys_orderid'].'.pdf'; 
						   
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