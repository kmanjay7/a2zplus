<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Pancard_histroy extends CI_Controller{
	var $serviceid;
	 public function __construct() {
		parent::__construct();
		$this->serviceid = 8;
	 }
	 
		
	public function index() {
			
			$response = array();
			    $data = array();  
			  
			   $table = 'dt_aeps';  
			$request = requestJson();
			
 /****  check Request  Status start ****/ 
 if( ($_SERVER['REQUEST_METHOD'] == 'POST') ){
			
	           
	           
	           $agentid = $request['agentid'];
	           $attemptstatus = $request['attemptstatus'];
	           $transaction = $request['transaction'];
	           $filterby = $request['filterby'];
	           $requestparam = $request['requestparam'];
	           $daterange = $request['daterange'];
	           $limit = $request['limit'];
			   $start = $request['start'];
			   $start = $limit && $start ? $start*$limit : 0;
			   $orderby = $request['orderby']?$request['orderby']:'DESC';  
			   $serviceid = $this->serviceid; 

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
			   }else if(!$filterby && !$requestparam && !$daterange ){
					$where['DATE(a.add_date) >='] = date('Y-m-d', strtotime(date('Y-m-d').' -30 days'));
					$where['DATE(a.add_date) <='] = date('Y-m-d');
			   }


			   if( ($filterby == 'orderid') && $requestparam ){
					$where['a.orderid'] = $requestparam;
			   }





			   if( $transaction && in_array($transaction,['new','correction']) ){ 
            	$where['a.category'] = $transaction; 
               }else if( is_numeric($transaction) ){ 
            	$where['a.applicant_type'] = $transaction; 
               }



               $where['a.attemptstatus'] = $attemptstatus;
               $where['a.addby_userid'] = $agentid; 
			
			$limit = !empty($limit) ? $limit : NULL;
			$start = !empty($start) ? $start : NULL; 

		     
              
		$inkey = null;
		$inkeyvalue = null; 
        $extrakey = '';
      if(in_array($attemptstatus, ['approved','complete']) ){
       	$where['b.usertype'] = 'PAN';
       	$where['b.documenttype'] = 'Ack Slip'; 
       	$extrakey = ',b.documentorimage';
       }
			 
		$select = 'a.id, a.orderid,a.pancardno,d.pancardtype,a.ackno,a.proccessing_fee,a.category,a.name_on_aadhar,a.contact,a.add_date as fill_date,a.approvaldate,a.holdon,a.reuploadon,a.reapprveon,a.rejecton,a.remark,a.status,a.name_on_pancard,a.c_stateid_ut as statename'.$extrakey ; 
			
			$from = 'dt_pancard AS a';

            $join[0]['table'] = 'dt_pancardtype as d';
            $join[0]['joinon'] = 'a.applicant_type = d.id' ; 
            $join[0]['jointype'] = 'LEFT';

            if(in_array($attemptstatus, ['approved','complete']) ){
            $join[1]['table'] = 'dt_uploads as b';
            $join[1]['joinon'] = 'a.id = b.tableid' ; 
            $join[1]['jointype'] = 'LEFT';
            }
 
 
            $groupby = null;
            $orderby = 'a.id '.$orderby ; 
            $getorcount = 'get';
            $getdataarr = $this->c_model->joinmultiple( $select, $where, $from, $join, $groupby ,$orderby, $limit, $start, $getorcount,$inkey,$inkeyvalue );
 

 //print_r( $getdataarr ); exit;
 
			$getdata = array();
			if( !empty($getdataarr) && !is_null($getdataarr) ){ 

					foreach ($getdataarr as $key => $value) {
						 $arr['id'] = (string)$value['id'];
						 $arr['orderid'] = (string)$value['orderid']; 
						 $arr['pancardno'] = (string)$value['pancardno'];
						 $arr['pancardtype'] = (string) $value['pancardtype'];
						 $arr['ackno'] = (string)$value['ackno'];
						 $arr['proccessing_fee'] = (string)$value['proccessing_fee'];
						 $arr['category'] = (string)$value['category']; 
						 $arr['name_on_aadhar'] = (string)!empty($value['name_on_aadhar'])?$value['name_on_aadhar']:$value['name_on_pancard'];
						 $arr['contact'] = (string)$value['contact'];
						 $arr['fill_date'] = (string)date('d-M-Y',strtotime($value['fill_date']));
						 $arr['approvaldate'] = (string) ($value['approvaldate']?date('d-M-Y h:i:s A',strtotime($value['approvaldate'])):'');
						 $arr['holdon'] = (string)($value['holdon']?date('d-M-Y h:i:s A',strtotime($value['holdon'])):'');
						 $arr['reuploadon'] = (string)($value['reuploadon']?date('d-M-Y h:i:s A',strtotime($value['reuploadon'])):'');
						 $arr['reapprveon'] = (string)($value['reapprveon']?date('d-M-Y h:i:s A',strtotime($value['reapprveon'])):'');
						 $arr['rejecton'] = (string)($value['rejecton']?date('d-M-Y h:i:s A',strtotime($value['rejecton'])):'');
						 $arr['remark'] = (string)$value['remark'];
						 $arr['status'] = (string)$value['status'];
						 $arr['statename'] = (string)$value['statename'];
						 $arr['ackfile'] = (string)$value['statename'];
						 if(in_array($attemptstatus, ['approved','complete']) ){ 
						 $arr['ackfile'] = (string)ADMINURL.'panuploads/'.$value['documentorimage'];
						 }else{
						 $arr['ackfile'] = (string)'';
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
		
	    header("Content-Type:application/json");
		echo json_encode( $response );
		
	
	 }


			
}
?>