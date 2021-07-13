<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Online_fund_report extends CI_Controller{
	var $serviceid;
	 public function __construct() {
		parent::__construct();
		$this->serviceid = 1;
	 }
	 
		
	public function index() {
			
			$response = array();
			    $data = array(); 
			    $where = array();

				$inkey = null;
				$invalue = null;

			$request = requestJson();
			
 /****  check Request  Status start ****/ 
 if( ($_SERVER['REQUEST_METHOD'] == 'POST') ){
			
	           
	           $user_id = trim($request['user_id']);
	           $usertype = trim($request['usertype']);
	           $transaction = trim($request['transaction']); 
	           $filterby = trim($request['filterby']);
	           $requestparam = trim($request['requestparam']);
	           $daterange = trim($request['daterange']);
	           $limit = trim($request['limit']);
			   $start = trim($request['start']);
			   $start = $limit && $start ? $start*$limit : 0;
			   $orderby = $request['orderby']?$request['orderby']:'DESC';    

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


            /*filter by start script*/ 

             if( $filterby=='orderid' && $requestparam ){ 
                $where['a.orderid'] = $requestparam;
             
             }else if( $filterby=='txnid' && $requestparam ){ 
                $where['a.banktxnid'] = $requestparam;

             }else if( $filterby=='ccdc' && $filterby ){ 
                

             } 

		 
		    /*Transaction condition start script*/
            if( $transaction == 'success' ){
                unset($where['a.status !=']); 
                $where['a.status'] = 'SUCCESS';

             }else if( $transaction == 'failed' ){
                unset($where['a.status !=']); 
                $where['a.status'] = 'FAILURE';

             }else if( $transaction == 'pending' ){
                unset($where['a.status !=']);  
                $inkey = 'a.status';
                $invalue = 'PENDING,ACCEPTED';

             }else if( in_array($transaction, ['UPI','RD','DC','CC','PPI','NB']) ){ 
                $where['a.paymode'] = $transaction; 
             } 

  
			

		 
		    $where['a.userid'] = $user_id; 
            $where['a.usertype'] = $usertype;  
            

           //  print_r($where);      


     $select = 'a.id, a.orderid, a.add_date, a.paymode, a.gatewayname, a.transctionid, a.bankname,a.respmsg, a.amount, a.banktxnid, a.status, a.sur_charge, a.tds, a.status_update, b.operatorid, c.operator,a.complaint';

		$from = 'dt_paytmlog as a';  

		$join[0]['table'] = 'dt_operators_code as b';
		$join[0]['joinon'] = 'a.paymode = b.op_code';
		$join[0]['jointype'] = 'LEFT';

		$join[1]['table'] = 'dt_operators as c';
		$join[1]['joinon'] = 'b.operatorid = c.id';
		$join[1]['jointype'] = 'LEFT';

     $groupby = null;
     $orderby = 'a.id '.$orderby;
     $limit = !empty($limit) ? $limit : NULL;
     $offset = null;
     $getorcount = 'get';  

	 $getdataarr = $this->c_model->joinmultiple( $select, $where, $from, $join, $groupby ,$orderby, $limit, $offset, $getorcount, $inkey, $invalue );


 
			$getdata = array();
			if( !empty($getdataarr) && !is_null($getdataarr) ){ 

					foreach ($getdataarr as $key => $value) { 
					
					 $arr['id'] = (string)$value['id'];
					 $arr['orderid'] = (string)$value['orderid']; 
					 $arr['add_date'] = (string)date('d-M-Y h:i:s A',strtotime($value['add_date']));
					 $arr['status_on'] = (string) $value['status_update']!='0000-00-00 00:00:00'?date('d-M-Y h:i:s A',strtotime($value['status_update'])):'';
					 $arr['bankinfo'] = (string)($value['bankname']?$value['bankname']:''); 
					 $arr['paymode'] = (string)$value['operator'];
					 $arr['txnid'] = (string)$value['banktxnid'];
					 $arr['amount'] = $value['amount'];
					 $arr['surcharge'] = (string)$value['sur_charge'];
					 $arr['status'] = (string)statusbtn_c($value['status']);
					 $arr['statushtm'] = (string)$value['status']; 
					 $arr['complaint'] = (string)$value['complaint'];
					 $arr['respmsg'] = (string)'Transaction Successful';  
						  
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