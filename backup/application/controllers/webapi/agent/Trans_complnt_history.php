<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Trans_complnt_history extends CI_Controller{
	
	 public function __construct() {
		parent::__construct();
	 }
	 
		
	public function index() {
			
			$response = array();
			    $data = array(); 
			    $where = null; 
			  
			   $table = 'dt_complaints';  
			$request = requestJson();
			
 /****  check Request  Status start ****/ 
 if( ($_SERVER['REQUEST_METHOD'] == 'POST') ){
			
	           
	           $userid = $request['userid'];
	           $usertype = $request['usertype']; 
	           $filterby = $request['filterby'];
	           $requestparam = $request['requestparam'];
	           $limit = $request['limit'];
			   $start = $request['start'];
			   $start = $limit && $start ? $start*$limit : 0;
			   $orderby = $request['orderby']?$request['orderby']:'DESC'; 
			   $orderbykey = 'id'; 

			   if(!$userid){
				$response['status'] = FALSE;
				$response['message'] = 'User Id is Blank!'; 
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
					$where['DATE(complainton) >='] = $fromdate;
					$where['DATE(complainton) <='] = $todate;
				    }else{
				    $where['DATE(complainton) >='] = date('Y-m-d');
					$where['DATE(complainton) <='] = date('Y-m-d');
				    }
			   } 
			
			$limit = !empty($limit) ? $limit : NULL;
			$start = !empty($start) ? $start : NULL; 
 
		$where['addby'] = $userid;
		if($usertype == 'AGENT'){
			$where['userid'] = $userid;
		} 
 
			 
		      
            $whereor = null;
            $whereorkey = null;
            $like = null;
            $likekey = null;
            $getorcount = 'get';
            $infield = null;
            $invalue = null;
            $keys = '*';
            $groupby = null;

           
            $getdata = $this->c_model->getfilter($table,$where,$limit,$start, $orderby, $orderbykey, $whereor, $whereorkey, $like, $likekey,  $getorcount, $infield, $invalue, $keys ,$groupby );
			 
 
			$out = [];
			if( !empty($getdata) && !is_null($getdata) ){ 
				foreach ($getdata as $key => $value) {
					$arr['id'] = $value['id'];
					$arr['userid'] = $value['userid'];
					$arr['ticketno'] = $value['ticketno'];
					$arr['orderinfo'] = $value['orderinfo'];
					$arr['amount'] = $value['amount'];
					$arr['txnid'] = $value['txnid'];
					$arr['txndate'] = date('d/m/Y h:i:s A',strtotime($value['txndate']));
					$arr['txnstatus'] = $value['txnstatus'];
					$statusinfo = date('d/m/Y h:i:s A',strtotime($value['statusinfo']));
					$arr['statusinfo'] = ($statusinfo!='01/01/1970 05:30:00 AM')?$statusinfo:'';
					$arr['complainton'] = date('d/m/Y h:i:s A',strtotime($value['complainton']));
					$replyon = date('d/m/Y h:i:s A',strtotime($value['replyon']));
					$arr['replyon'] = ($replyon!='01/01/1970 05:30:00 AM')?$replyon:'';
					$arr['status'] = $value['status'];
					$arr['addby'] = $value['addby'];
					array_push($out, $arr);
				}
			 $status = 1; 
			}else{ $status = 2; }
	
	       
			 
			 
			
			if($status == 1 ){ 
			$response['status'] = TRUE;
			$response['data'] = $out; 
		    $response['message'] = "Request was Successfull";
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