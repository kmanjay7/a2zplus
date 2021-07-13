<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Recharge_histroy extends CI_Controller{
	
	 public function __construct() {
		parent::__construct();
	 }
	 
		
	public function index() {
			
			$response = array();
			    $data = array();
			    $getdata = array(); 
			    $inkey = null;
                $invalue = null;
			  
			     
			$request = requestJson();
			
 /****  check Request  Status start ****/ 
 if( ($_SERVER['REQUEST_METHOD'] == 'POST') ){
			
	           
   $user_id = $request['user_id'];
   $serviceid = $request['serviceid'];
   $transaction = isset($request['transaction'])?$request['transaction']:false;
   $daterange = isset($request['daterange'])?$request['daterange']:false;
   $filterby = $request['filterby'];
   $requestparam = $request['requestparam'];

	           $limit = $request['limit'];
			   $start = $request['start'];
			   $start = $limit && $start ? $start*$limit : 0;
			   $orderby = $request['orderby']?$request['orderby']:'DESC'; 
			  

			   if(!$user_id){
				$response['status'] = FALSE;
				$response['message'] = 'User Id is Blank!'; 
				echo json_encode($response);
				exit;
			   }else if(!$serviceid){
				$response['status'] = FALSE;
				$response['message'] = 'Service Id is Blank!'; 
				echo json_encode($response);
				exit;
			   }
			   

			   /*date function start*/
			   if( ($filterby == 'date') && $requestparam ){
					$explode = explode('|', $requestparam );
					$fromdate = $explode[0];
					$fromdate = date('Y-m-d',strtotime($fromdate));
					$todate = $explode[1];
					$todate = date('Y-m-d',strtotime($todate));
					if(($fromdate !='1970-01-01') && ($todate !='1970-01-01') ){
					$where['DATE(a.add_date) >='] = $fromdate;
					$where['DATE(a.add_date) <='] = $todate;
				    }
			   }else if( ($filterby == 'mobileno') && $requestparam ){
					$where['a.mobileno'] = $requestparam; 
			   }else if( $daterange ){
					$explode = explode('|', $daterange );
					$fromdate = $explode[0];
					$fromdate = date('Y-m-d',strtotime($fromdate));
					$todate = $explode[1];
					$todate = date('Y-m-d',strtotime($todate));
					if(($fromdate !='1970-01-01') && ($todate !='1970-01-01') ){
					$where['DATE(a.add_date) >='] = $fromdate;
					$where['DATE(a.add_date) <='] = $todate;
				    }
			   }

 			$where['a.status !='] = 'REQUEST';
 			 

 			 if( $transaction == 'success' ){
                unset($where['a.status !=']); 
                $where['a.status'] = 'SUCCESS';

             }else if( $transaction == 'failed' ){
                unset($where['a.status !=']); 
                $where['a.status'] = 'FAILURE';

             }else if( $transaction == 'pending' ){
                unset($where['a.status !=']);  
                $inkey = 'a.status';
                $invalue = 'PENDING,PROCESSED';

             }  


             if( $filterby =='orderid' && $requestparam ){ 
                $where['a.reqid'] = $requestparam;
             
             }else if( $filterby =='txnid' && $requestparam ){ 
                $where['a.op_transaction_id'] = $requestparam;

             }else if( $filterby =='mob' && $requestparam ){ 
                $where['a.mobileno'] = $requestparam;

             } 

			
			 

		$where['a.serviceid'] = $serviceid; 
        $where['a.user_id'] = $user_id;
         
			 
		$select = 'a.reqid,a.user_id, a.status, a.amount, a.mobileno, a.field2,a.apirefid,a.op_transaction_id, a.operatorname, a.add_date, a.ag_comi, a.ag_tds, a.status_update, dt_operators.image, a.operatorname,a.apirefid,a.status,a.status_update,a.mobileno,a.complaint,a.id'; 
			
			$from = 'dt_rech_history as a'; 

            $join[0]['table'] = 'dt_operators';
            $join[0]['joinon'] = 'a.operatorid = dt_operators.id';
            $join[0]['jointype'] = 'LEFT'; 
 
            $groupby = NULL; 
            $orderby = 'a.id '.$orderby ; 
            $getorcount = 'get';

            $limit = !empty($limit) ? $limit : NULL;
			$start = !empty($start) ? $start : NULL;

            $fetchdata = $this->c_model->joinmultiple( $select, $where, $from, $join, $groupby ,$orderby, $limit, $start, $getorcount, $inkey, $invalue );;
			 
 
			
			if( !empty($fetchdata) && !is_null($fetchdata) ){ 
                foreach ($fetchdata as $key => $value) {
						 $arr['reqid'] = (string)$value['reqid'];
						 $arr['status'] = (string)$value['status'];
						 $arr['amount'] = (string)$value['amount'];
						 $arr['mobileno'] = (string)$value['mobileno'];
						 $arr['field2'] = (string)$value['field2'];
						 $arr['apirefid'] = (string)$value['apirefid'];
						 $arr['op_transaction_id'] = (string)$value['op_transaction_id'];
						 $arr['operatorname'] = (string)$value['operatorname']; 
						 $arr['add_date'] = (string)date('d-M-Y h:i:s A',strtotime($value['add_date']));
						 $arr['ag_comi'] = (string)$value['ag_comi'];
						 $arr['ag_tds'] = (string)$value['ag_tds'];
						 $arr['status_update'] = (string)(!empty($value['status_update'])? date('d-M-Y h:i:s A',strtotime($value['status_update'])):'');
						 $arr['image'] = (string)$value['image'];
						 $arr['complaint'] = (string)$value['complaint'];
						 $arr['id'] = (string)$value['id'];
						 $arr['orderid'] = (string)$value['reqid'];

						 $arr['printurl'] = (string) ADMINURL.'ag/'.($serviceid==3 ? 'dth_reciept':'prepaid_reciept').'?utp='.md5($value['reqid']).'&apptype=app&id='.md5($value['user_id']) ;
						 array_push($getdata, $arr);
						}

						 


			 $status = 1; 
			}else{ $status = 2; }
	
	       
			 
			 
			
			if($status == 1 ){ 
			$response['status'] = TRUE;
			$response['data'] = $getdata;
		    $response['message'] = "Request Successful";
			}else if($status == 2 ){ 
			$response['status'] = FALSE;
		    $response['message'] = "No record match!";		
			} 
			
		 
		
/*token check end*/	
}else{ 
	$response['status'] = FALSE;
    $response['message'] = "Bad Request!";
}
		
	   //header("Content-Type:application/json");
		echo json_encode( $response );
		
	
	 }
			
}
?>