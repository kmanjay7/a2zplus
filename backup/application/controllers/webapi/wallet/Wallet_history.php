<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Wallet_history extends CI_Controller{
	
	 public function __construct() {
		parent::__construct();
	 }
	 
		
	public function index() {
			
			$response = array();
			    $data = array();  
			  
			   $table = 'dt_wallet';  
			$request = requestJson();
			
 /****  check Request  Status start ****/ 
 if( ($_SERVER['REQUEST_METHOD'] == 'POST') ){
			
	           
	           $user_id = $request['user_id'];
	           $usertype = $request['usertype'];
	           $filterby = $request['filterby'];
	           $requestparam = $request['requestparam'];
	           $limit = $request['limit'];
			   $start = $request['start'];
			   $start = $limit && $start ? $start*$limit : 0;
			   $orderby = $request['orderby']?$request['orderby']:'DESC'; 
			   $orderbykey = 'id'; 

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
			   }else if( ($filterby == 'cardtype') && $requestparam ){
					$where['credit_debit'] = $requestparam;
			   }
			
			$limit = !empty($limit) ? $limit : NULL;
			$start = !empty($start) ? $start : NULL; 

		$where['usertype'] = $usertype; 
        $where['userid'] = $user_id;   
			 
		      
            $whereor = null;
            $whereorkey = null;
            $like = null;
            $likekey = null;
            $getorcount = 'get';
            $infield = null;
            $invalue = null;
            $keys = 'id,credit_debit,remark,subject,referenceid,add_date,beforeamount,amount,finalamount';
            $groupby = null;

           
            $getdata = $this->c_model->getfilter($table,$where,$limit,$start, $orderby, $orderbykey, $whereor, $whereorkey, $like, $likekey,  $getorcount, $infield, $invalue, $keys ,$groupby );
			 
 
			
			if( !empty($getdata) && !is_null($getdata) ){ 
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