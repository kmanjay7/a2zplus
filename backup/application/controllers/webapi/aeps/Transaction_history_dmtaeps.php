<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Transaction_history_dmtaeps extends CI_Controller{
	var $serviceid;
	 public function __construct() {
		parent::__construct();
		$this->serviceid = 1;
	 }
	 
		
	public function index() {
			
			$response = array();
			    $data = array(); 
			    $where = array();

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
					$jwhere['DATE(a.add_date) >='] = $fromdate;
					$jwhere['DATE(a.add_date) <='] = $todate;
				    }
			   }


            /*filter by start script*/
            if( ($filterby == 'orderid') && $requestparam ){
 				$where['a.referenceid'] = $requestparam; 
 				$jwhere['a.orderid'] = $requestparam; 
            }else if( ($filterby == 'acno') && $requestparam ){
 				$where['a.upiid'] = $requestparam; 
 				$jwhere['b.upiid'] = $user_id; 
            }

		 
		    /*Transaction condition start script*/
            if( $transaction == 'WALLET' ){
 				$where['a.subject'] = 'aeps_tr_wt';
            }else if( $transaction == 'BANK' ){
 				$where['a.subject'] = 'aeps_tr_bk';
            } 

  
			

			if( ($user_id != 'admin') && ($usertype != 'admin') ){
		    $where['a.userid'] = $user_id; 
            $where['a.usertype'] = $usertype; 
            $jwhere['a.userid'] = $user_id; 
            $jwhere['a.usertype'] = $usertype;
            }

            if( ($filterby == 'uid') && $requestparam && ($user_id == 'admin') && ($usertype == 'admin') ){
            	$nuid = $this->c_model->getSingle('dt_users',['uniqueid'=>$requestparam],'id,uniqueid,user_type');
 				$where['a.userid'] = $nuid['id'];
 				$where['a.usertype'] = $nuid['user_type']; 
 				$jwhere['b.userid'] = $nuid['id']; 
 				$jwhere['b.usertype'] = $nuid['user_type'];
            }   
           //  print_r($where);      

/*************** innitlize query for complex serach   ******************/
if( in_array( $transaction , ['SUCCESS','FAILED','PENDING','IMPS','NEFT'] ) ){
     $admorekey = '';
	 if( $user_id == 'admin' ){
       $admorekey = ',e.ownername,e.uniqueid,f.sch_name';
	 }

     $select = 'a.mode,a.amount,a.add_date,a.status,a.sur_charge,c.account_no,d.bankname,a.status_update,b.id,b.referenceid,b.remark,b.subject,b.complaint,a.respmsg,c.account_name,c.ifsccode,a.banktxnid'.$admorekey;
     
     if($transaction == 'SUCCESS'){
		$jwhere['a.status'] = 'SUCCESS';
     }else if($transaction == 'FAILED'){
		$jwhere['a.status'] = 'FAILURE';
     }else if($transaction == 'PENDING'){
		$jwhere['a.status'] = 'ACCEPTED'; //PENDING
     }else if($transaction == 'IMPS'){
		$jwhere['a.mode'] = 'IMPS';
     }else if($transaction == 'NEFT'){
		$jwhere['a.mode'] = 'NEFT';
     }

     $from = 'dt_dmtlog_aeps as a';
      
     $join[0]['table'] = 'dt_wallet_aeps as b';
     $join[0]['joinon'] = 'a.orderid = b.referenceid';
     $join[0]['jointype'] = 'LEFT';

     $join[1]['table'] = 'dt_aeps_bank_details as c';
     $join[1]['joinon'] = 'a.bank_id = c.id';
     $join[1]['jointype'] = 'LEFT';

     $join[2]['table'] = 'dt_bank as d';
     $join[2]['joinon'] = 'c.bankid = d.id';
     $join[2]['jointype'] = 'LEFT';

	     if( $user_id == 'admin' ){
			$join[3]['table'] = 'dt_users as e';
			$join[3]['joinon'] = 'a.userid = e.id';
			$join[3]['jointype'] = 'LEFT';

			$join[4]['table'] = 'dt_scheme as f';
			$join[4]['joinon'] = 'e.scheme_type = f.id';
			$join[4]['jointype'] = 'LEFT';
	     }	

     $groupby = null;
     $orderby = 'b.id '.$orderby;
     $limit = !empty($limit) ? $limit : NULL;
     $offset = null;
     $getorcount = 'get';

     $inkey = 'b.subject'; 
     $inkeyvalue = ['aeps_tr_wt','aeps_tr_bk'];




	 $getdataarr = $this->c_model->joinmultiple( $select, $jwhere, $from, $join, $groupby ,$orderby, $limit, $offset, $getorcount, $inkey, $inkeyvalue );
	 
	// print_r($getdataarr);
 
}else{ 

     $selectt = 'a.id,a.referenceid,a.remark,a.add_date,a.subject,a.amount,a.complaint,b.ownername,b.uniqueid,c.sch_name';

     $fromm = 'dt_wallet_aeps as a'; 
     		$joinn[0]['table'] = 'dt_users as b';
			$joinn[0]['joinon'] = 'a.userid = b.id';
			$joinn[0]['jointype'] = 'LEFT';

			$joinn[1]['table'] = 'dt_scheme as c';
			$joinn[1]['joinon'] = 'b.scheme_type = c.id';
			$joinn[1]['jointype'] = 'LEFT';

     $groupbyy = null;
     $orderbyy = 'a.id '.$orderby;
     $limitt = !empty($limit) ? $limit : NULL;
     $offsett = null;
     $getorcountt = 'get'; 
     $inkeyy = 'a.subject'; 
     $inkeyvaluee = ['aeps_tr_wt','aeps_tr_bk'];


	 $getdataarr = $this->c_model->joinmultiple( $selectt, $where, $fromm, $joinn, $groupbyy ,$orderbyy, $limitt, $offsett, $getorcountt, $inkeyy, $inkeyvaluee );

}





//print_r($getdataarr);
// exit;
 
			$getdata = array();
			if( !empty($getdataarr) && !is_null($getdataarr) ){ 

					foreach ($getdataarr as $key => $value) {

					if( in_array( $transaction , ['SUCCESS','FAILED','PENDING','IMPS','NEFT'] ) ){
					 $arr['id'] = (string)$value['id'];
					 $arr['orderid'] = (string)$value['referenceid'];
					 $arr['remark'] = (string)$value['remark'];
					 $arr['add_date'] = (string)date('d-M-Y h:i:s A',strtotime($value['add_date']));
					 $arr['status_on'] = (string) date('d-M-Y h:i:s A',strtotime($value['add_date']));
					 $arr['bankinfo'] = ($value['subject']=='aeps_tr_bk')?'Transfer To bank':'Main Wallet';
					 $arr['mode'] = (string)$value['mode'];
					 $arr['accountno'] = (string)$value['account_no'];
					 $arr['amount'] = (string)$value['amount'];
					 $arr['surcharge'] = (string)$value['sur_charge'];
					 $arr['status'] = (string)statusbtn_c($value['status']);
					 $arr['statushtm'] = (string)$value['status'];
					 $arr['complaint'] = (string)$value['complaint'];
					 $arr['respmsg'] = (string)$value['respmsg'];
					 $arr['ac_holder'] = (string)isset($value['account_name'])?$value['account_name']:'';
					 $arr['ifsc'] = (string)isset($value['ifsccode'])?$value['ifsccode']:'';
					 $arr['banktxnid'] = (string)isset($value['banktxnid'])?$value['banktxnid']:'';
					 $arr['printurl'] = ADMINURL.'';


						 if( $user_id == 'admin' ){
						 $arr['ownername'] = (string)$value['ownername'];
						 $arr['agent_uniqueid'] = (string)$value['uniqueid'];
						 $arr['sch_name'] = (string)$value['sch_name'];
						 }

					}else{	
					 $arr['id'] = (string)$value['id'];
					 $arr['orderid'] = (string)$value['referenceid'];
					 $arr['remark'] = (string)$value['remark'];
					 $arr['add_date'] = (string)date('d-M-Y h:i:s A',strtotime($value['add_date']));
					 $arr['status_on'] = (string) date('d-M-Y h:i:s A',strtotime($value['add_date']));
					 $arr['bankinfo'] = 'Main Wallet';
					 $arr['mode'] = 'Main Wallet';
					 $arr['accountno'] = '';
					 $arr['amount'] = $value['amount'];
					 $arr['surcharge'] = '';
					 $arr['status'] = (string)statusbtn_c('SUCCESS');
					 $arr['statushtm'] = (string)'SUCCESS'; 
					 $arr['complaint'] = (string)$value['complaint'];
					 $arr['respmsg'] = (string)'Transaction Successful'; 
					 $arr['ac_holder'] = '';
					 $arr['ifsc'] = '';
					 $arr['banktxnid'] = '';
					 $arr['printurl'] = ADMINURL.'';

					/*get bank dmt data*/
					if( $value['subject'] == 'aeps_tr_bk' && $arr['orderid'] ){
					$dmt = $this->dmtdata( $arr['orderid'] );
						if(!empty($dmt)){
						$dmt = $dmt[0];
						$arr['bankinfo'] = $dmt['bankname'];
						$arr['mode'] = $dmt['mode'];
						$arr['accountno'] = $dmt['account_no'];
						$arr['amount'] = $dmt['amount'];
						$arr['surcharge'] = $dmt['sur_charge'];
						$arr['status'] = (string)statusbtn_c($dmt['status']);
						$arr['statushtm'] = $dmt['status'];  
						$arr['add_date'] = (string)date('d-M-Y h:i:s A',strtotime($dmt['add_date']));
					    $arr['status_on'] = (string) date('d-M-Y h:i:s A',strtotime($dmt['status_update']));
					    $arr['respmsg'] = (string)$dmt['respmsg'];
					    $arr['ac_holder'] = (string)isset($dmt['account_name'])?$dmt['account_name']:'';
					    $arr['ifsc'] = (string)isset($dmt['ifsccode'])?$dmt['ifsccode']:'';
					    $arr['banktxnid'] = (string)isset($dmt['banktxnid'])?$dmt['banktxnid']:'';
					    $arr['printurl'] = ADMINURL.'';
						}
 
					} 

					     if( $user_id == 'admin' ){
						 $arr['ownername'] = (string)$value['ownername'];
						 $arr['agent_uniqueid'] = (string)$value['uniqueid'];
						 $arr['sch_name'] = (string)$value['sch_name'];
						 }

				    }



						  
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
		
	   // header("Content-Type:application/json");
		echo json_encode( $response );
		
	
	 }



public function dmtdata( $orderid ){
     $select = 'a.mode,a.amount,a.add_date,a.status,a.sur_charge,b.account_no,c.bankname,a.status_update,a.respmsg,b.account_name,b.ifsccode,a.banktxnid';
     $where['a.orderid'] = $orderid;
     $from = 'dt_dmtlog_aeps as a';
      
     $join[0]['table'] = 'dt_aeps_bank_details as b';
     $join[0]['joinon'] = 'a.bank_id = b.id';
     $join[0]['jointype'] = 'LEFT';

     $join[1]['table'] = 'dt_bank as c';
     $join[1]['joinon'] = 'b.bankid = c.id';
     $join[1]['jointype'] = 'LEFT';


     $groupby = null;
     $orderby = null;
     $limit = null;
     $offset = null;
     $getorcount = 'get';


	 $dmt = $this->c_model->joinmultiple( $select, $where, $from, $join, $groupby ,$orderby, $limit, $offset, $getorcount );
	 return $dmt;
}	 
			
}
?>