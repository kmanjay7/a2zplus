<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Wallet_history_aeps extends CI_Controller{
	
	 public function __construct() {
		parent::__construct();
	 }
	 
		
	public function index() {
			
			$response = array();
			    $data = array();  
			  
			   $table = 'dt_wallet_aeps';  
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
			   }else if(!$daterange){
				$response['status'] = FALSE;
				$response['message'] = 'Date Range is Blank!'; 
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
					$where['DATE(add_date) >='] = $fromdate;
					$where['DATE(add_date) <='] = $todate;
				    }
			   }

			   if( ($filterby == 'orderid') && $requestparam ){
					$where['referenceid'] = $requestparam;
			   }

			   if( $transaction ){
					$where['subject'] = strtolower( $transaction );
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
            $keys = "id,credit_debit,subject,referenceid, add_date,beforeamount ,amount,remark,finalamount,transctionid,odr,surch,comi,tds,flag";
            $groupby = null;

           
            $getdata = $this->c_model->getfilter($table,$where,$limit,$start, $orderby, $orderbykey, $whereor, $whereorkey, $like, $likekey,  $getorcount, $infield, $invalue, $keys ,$groupby); 
 
			$t_successfull = false;
			$t_comission = false;
			$t_surcharge = false;
			$t_tds = false;

	if( !empty($getdata) && !is_null($getdata) ){ 
		foreach ($getdata as $key => $value) {

			
			 $plusminus = '';
			 if($value['credit_debit']=='debit'){ $plusminus = '-'; }

			 $arr['id'] =  (string)$value['id'];
			 $arr['credit_debit'] = (string)$value['credit_debit'];
			 $arr['subject'] = (string)$value['subject'];
			 $arr['remark'] = (string)$value['remark'];
			 $arr['referenceid'] = (string)$value['referenceid'];
			 $arr['add_date'] = (string)date('d-m-Y h:i:s A',strtotime($value['add_date']));
			 $arr['referenceid'] = (string)$value['referenceid'];
			 $arr['open_bal'] = (string)$value['beforeamount']; 
			 $arr['odr_amount'] = (string)$value['amount'];  
			 $arr['crdr_amount'] = (string) $value['amount']; 
			 
			 if( $value['flag'] ){
				$arr['odr_amount'] = (string) $value['odr'];
				$arr['crdr_amount'] = (string) $plusminus.$value['amount'];
				$arr['surcharge'] = (string) $value['surch'];
				$arr['tds'] = (string) $value['tds'];
				$arr['comission'] = (string) ($value['comi']); 
			 }else{
 					$details = $this->getmainwalt($value);
					if($arr['subject'] == 'aeps_m_t' ){ 
					$arr['odr_amount'] = (string)$details['amount'];
					}else if($arr['subject'] == 'aeps_tr_bk' ){
					$arr['odr_amount'] = (string)$details['amount'];
					$arr['crdr_amount'] = (string) $plusminus.$value['amount']; 
					}else if($arr['subject'] == 'aeps_tr_wt' ){ 
					$arr['crdr_amount'] = (string) $plusminus.$value['amount']; 
					} 

					$arr['surcharge'] = (string) '0.000';
					$arr['tds'] = (string) $details['tds'];
					$arr['comission'] = (string) ($details['comission']); 
			 }

			 $arr['close_bal'] = (string) fourdecimal($value['finalamount']);
			 
			 //$arr['dt'] = $details;

			 //$t_successfull += (float)$arr['odr_amount'];
			 //$t_comission += (float)$arr['comission'];
			 //$t_surcharge += (float)$arr['surcharge'];
			 //$t_tds += (float)$arr['tds'];
			
			 array_push($data, $arr);
			 $arr = [];
			 $details = [];
		}
	 $status = 1; 
	}else{ $status = 2; }
	
	       
			 
			 
			
			if($status == 1 ){ 
			$response['status'] = TRUE;
			$response['data'] = $data;
			$response['t_successfull'] = (string)$t_successfull;
			$response['t_comission'] = (string)$t_comission;
			$response['t_surcharge'] = (string)$t_surcharge;
			$response['t_tds'] = (string)$t_tds;
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
		
	   //header("Content-Type:application/json");
		echo json_encode( $response );
		
	
	 }


public function getmainwalt($value){ 
	   $referenceid = $value['referenceid'];
	   $subject = $value['subject'];
	   $transctionid = $value['transctionid'];
	   
       $data['surcharge'] = 0;
       $data['tds'] = 0;
       $data['comission'] = 0;
       $data['amount'] = 0;
       

        
          $dwhere = ''; $dkey = '';
          if( in_array($subject, ['aeps_m_t']) ){  //money transfer  
          	$dkey = 'amount,sur_charge,ag_comi,ag_tds'; 
          	$dtmarr = $this->c_model->getSingle('dt_aeps',array('sys_orderid'=>$referenceid), $dkey );
			$data['surcharge'] = $dtmarr['sur_charge'];
			$data['tds'] = $dtmarr['ag_tds'];
			$data['comission'] = $dtmarr['ag_comi'];
			$data['amount'] = $dtmarr['amount']; 
          }
          else if( in_array($subject, ['aeps_tr_bk']) ){  //dmt transfer  
			$dkey = 'amount,sur_charge,ag_comi,ag_tds'; 
			$dtmarr = $this->c_model->getSingle('dt_dmtlog_aeps',array('id'=>$transctionid), $dkey );
			$data['surcharge'] = $dtmarr['sur_charge']; 
			$data['amount'] = $dtmarr['amount'];   
          }
          
        


       return $data;
}	 
			
}
?>