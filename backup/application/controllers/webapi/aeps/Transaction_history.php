<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Transaction_history extends CI_Controller{
	var $serviceid;
	 public function __construct() {
		parent::__construct();
		$this->serviceid = 1;
	 }
	 
		
	public function index() {
			
			$response = array();
			    $data = array();  
			  
			   $table = 'dt_aeps';  
			$request = requestJson();
			
 /****  check Request  Status start ****/ 
 if( ($_SERVER['REQUEST_METHOD'] == 'POST') ){
			
	           
	           $user_id = $request['user_id'];
	           $usertype = $request['usertype'];
	           $transaction = $request['transaction'];
	           $filterby = $request['filterby'];
	           $requestparam = $request['requestparam'];
	           $daterange = $request['daterange'];
	           $limit = $request['limit'];
			   $start = $request['start'];
			   $start = $limit && $start ? $start*$limit : 0;
			   $orderby = $request['orderby']?$request['orderby']:'DESC';  
			   $serviceid = $this->serviceid; 

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
			   

			   /*date function start*/
			   if( $daterange ){
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


			   if( ($filterby == 'orderid') && $requestparam ){
					$where['a.sys_orderid'] = $requestparam;
			   }else if( ($filterby == 'txnid') && $requestparam ){
					$where['a.banktxnid'] = $requestparam;
			   }else if( ($filterby == 'mobile') && $requestparam ){
					$where['a.mobileno'] = $requestparam;
			   }else if( ($filterby == 'aadhaar') && $requestparam ){
			   		$where['a.aadharuid'] = $requestparam;
			   	    if(strlen($requestparam)==4){
					$where['a.aadharuid'] = 'xxxx-xxxx-'.$requestparam;
			   	    } 
			   }





			   if( $transaction == 'BAP' ){ 
            	$where['a.mode'] = $transaction; 
               }else if( $transaction == 'SAP' ){ 
            	$where['a.mode'] = $transaction; 
               }else if( $transaction == 'WAP' ){ 
            	$where['a.mode'] = $transaction; 
               }else if( $transaction == 'SUCCESS' ){ 
            	$where['a.status'] = $transaction; 
               }else if( $transaction == 'FAILED' ){ 
            	$where['a.status'] = $transaction; 
               }else if( $transaction == 'PENDING' ){ 
            	$where['a.status'] = $transaction; 
               }

			
			$limit = !empty($limit) ? $limit : NULL;
			$start = !empty($start) ? $start : NULL; 

		    $where['a.userid'] = $user_id; 
            $where['a.usertype'] = $usertype;  
            

            $select = 'a.id,a.userid, a.sys_orderid, a.aadharuid , a.mobileno, a.mode, b.bankname as bank, a.banktxnid, a.amount, a.ag_comi, a.ag_tds, a.api_status_on, a.api_orderid, a.add_date,a.status,a.complaint '; 
            $from = 'dt_aeps as a';

            $join[0]['table'] = 'dt_bank as b';
            $join[0]['joinon'] = 'a.bankname = b.bank_iin' ;  
            $join[0]['jointype'] = 'LEFT'; 

            $groupby = null;//'sys_orderid' ; 
            $orderby = 'id '.$orderby ; 
            $getorcount = 'get';

           
            $getdataarr = $this->c_model->joinmultiple( $select, $where, $from, $join, $groupby ,$orderby, $limit, $start, $getorcount ); 
 

 			$s_total = false;
 			$s_total_comi = false;
 			$s_total_tds = false;
 			$s_total_surch = false;
 
			$getdata = array();
			if( !empty($getdataarr) && !is_null($getdataarr) ){ 

					foreach ($getdataarr as $key => $value) {
						 $arr['id'] = (string)$value['id'];
						 $arr['orderid'] = (string)$value['sys_orderid'];
						 $arr['aadharuid'] = (string)$value['aadharuid'];
						 $arr['mobileno'] = (string)$value['mobileno'];
						 $arr['mode'] = (string)$value['mode'];
						 $arr['modename'] = (string) $this->modifymode($value['mode']);
						 $arr['bank'] = (string)$value['bank'];
						 $arr['banktxnid'] = (string)$value['banktxnid'];
						 $arr['amount'] = (string)$value['amount'];
						 $arr['ag_comi'] = (string)$value['ag_comi'];
						 $arr['ag_tds'] = (string)$value['ag_tds'];
						 $arr['api_status_on'] = (string)date('d-M-Y h:i:s A',strtotime($value['api_status_on']));
						 $arr['add_date'] = (string)date('d-M-Y h:i:s A',strtotime($value['add_date']));
						 $arr['status'] = (string)statusbtn_c($value['status']);
						 $arr['statushtm'] = (string)($value['status']);
						 $arr['api_orderid'] = (string)$value['api_orderid'];
						 $arr['complaint'] = (string)$value['complaint'];
						 
						 $arr['printurl'] = (string) ADMINURL.'ag/Print_reciept_aeps?utp='.md5($value['sys_orderid']).'&apptype=app&id='.md5($value['userid']).'&pdf=yes'; 
						 array_push($getdata, $arr);

						 if($value['status']=='SUCCESS'){
							$s_total += $arr['amount'];
							$s_total_comi += $arr['ag_comi'];
							$s_total_tds += $arr['ag_tds'];
							$s_total_surch += false;
						 }
						
					} 

			 $status = 1; 
			}else{ $status = 2; } 
			 
			
			 

			
			if($status == 1 ){ 
			$response['status'] = TRUE;
			$response['data'] = $getdata ; 
			
			$response['s_total'] = $s_total ;
			$response['s_total_comi'] = $s_total_comi ;
			$response['s_total_tds'] = $s_total_tds ;
			$response['s_total_surch'] = $s_total_surch ;

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



public function modifymode($dt){
	$output = 'CASH WITHDRAWAL';
	if($dt=='SAP'){ $output = 'MINI STATEMENT'; }
	else if($dt=='BAP'){ $output = 'BALANCE INQUIRY'; }
	return $output; 
}	 
			
}
?>