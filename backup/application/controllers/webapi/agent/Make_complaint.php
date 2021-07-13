<?php defined("BASEPATH") OR exit("No Direct Access Allowed!");

class Make_complaint extends CI_Controller{
	
	public function __construct(){
		parent::__construct();
		}
		
	
	
	public function index(){

		header("Pragma: no-cache");
		header("Cache-Control: no-cache");
		header("Expires: 0");
				
				$response = array();
				    $data = array();  
				   $table = 'dt_users';
				 
				 $request = requestJson();
		
		
if( ($_SERVER['REQUEST_METHOD'] == 'POST') ){
			
		$userid = !empty($request['userid']) ? trim($request['userid']) : '';	
		$servicename = !empty($request['servicename']) ? trim($request['servicename']) : '';
		$tableid = !empty($request['tableid']) ? trim($request['tableid']) : ''; 
		 
		 
		 if( !$userid ){
			$response['status'] = FALSE;
			$response['message'] = 'Please enter userid!';
			header("Content-Type: application/json");
		    echo json_encode($response);
		    exit;
		 }else if(!$servicename ){
			$response['status'] = FALSE;
			$response['message'] = 'Please enter servicename!';
			header("Content-Type: application/json");
		    echo json_encode($response);
		    exit;
		 }else if(!$tableid ){
			$response['status'] = FALSE;
			$response['message'] = 'Please enter table id!';
			header("Content-Type: application/json");
		    echo json_encode($response);
		    exit;
		 }





		 $sendotpat = date('Y-m-d H:i:s');
		 $update = '';
		
		
		$where['id'] =  $userid;   
		$checkuser = $this->c_model->countitem($table,$where);
			
			
			$status = false;
		/* Check User  start*/
		if( $checkuser == 1 ){ 
				
			$output = [];
			$where = []; 
			$token = null;

			if($servicename == 'dmt_1'){
			$where['id'] = $tableid; 
			$keys = 'id,orderid,add_date,amount,status_update,status,ptm_rrn,userid';
			$getdata = $this->c_model->getSingle('dt_dmtlog',$where,$keys);  
			
			$token = preg_replace('#[0-9 ]*#', '', $getdata['orderid']);
            
            $output['userid'] = (string)$getdata['userid'];
            $output['orderinfo'] = (string)$getdata['orderid'];
            $output['txndate'] = (string)$getdata['add_date'];
            $output['amount'] = (string)$getdata['amount'];
            $output['txnstatus'] = (string)$getdata['status'];
            $output['statusinfo'] = (string)$getdata['status_update'];
            $output['txnid'] = (string)$getdata['ptm_rrn']; 
            	if($getdata){
	            $this->c_model->saveupdate('dt_dmtlog',['complaint'=>'y'],null,$where);
	            } 	
			}

			else if(in_array($servicename, ['pre_mobile','bbps'])){
			$where['id'] = $tableid; 
			$keys = 'id,reqid,add_date,amount,status_update,status,op_transaction_id,user_id';
			$getdata = $this->c_model->getSingle('dt_rech_history',$where,$keys); 

            $token = preg_replace('#[0-9 ]*#', '', $getdata['reqid']);
            $output['userid'] = (string)$getdata['user_id'];
            $output['orderinfo'] = (string)$getdata['reqid'];
            $output['txndate'] = (string)$getdata['add_date'];
            $output['amount'] = (string)$getdata['amount'];
            $output['txnstatus'] = (string)$getdata['status'];
            $output['statusinfo'] = (string)$getdata['status_update']; 
            $output['txnid'] = (string)$getdata['op_transaction_id']; 
	            if($getdata){
	            $this->c_model->saveupdate('dt_rech_history',['complaint'=>'y'],null,$where);
	            } 
			}		    
			
			else if($servicename == 'onl_fund'){
			$where['id'] = $tableid; 
			$keys = 'id,orderid,add_date,amount,status_update,status,banktxnid,userid';
			$getdata = $this->c_model->getSingle('dt_paytmlog',$where,$keys);  

            $token = preg_replace('#[0-9 ]*#', '', $getdata['orderid']);
            $output['userid'] = (string)$getdata['userid'];
            $output['orderinfo'] = (string)$getdata['orderid'];
            $output['txndate'] = (string)$getdata['add_date'];
            $output['amount'] = (string)$getdata['amount'];
            $output['txnstatus'] = (string)$getdata['status'];
            $output['statusinfo'] = (string)$getdata['status_update']; 
            $output['txnid'] = (string)$getdata['banktxnid']; 
                if($getdata){
	            $this->c_model->saveupdate('dt_paytmlog',['complaint'=>'y'],null,$where);
	            }	
			}	

			else if($servicename == 'aeps'){
			$where['id'] = $tableid; 
			$keys = 'id,sys_orderid,add_date,amount,api_status_on,status,banktxnid,userid';
			$getdata = $this->c_model->getSingle('dt_aeps',$where,$keys);  

            $token = preg_replace('#[0-9 ]*#', '', $getdata['sys_orderid']);
            $output['userid'] = (string)$getdata['userid'];
            $output['orderinfo'] = (string)$getdata['sys_orderid'];
            $output['txndate'] = (string)$getdata['add_date'];
            $output['amount'] = (string)$getdata['amount'];
            $output['txnstatus'] = (string)$getdata['status'];
            $output['statusinfo'] = (string)$getdata['api_status_on']; 
            $output['txnid'] = (string)$getdata['banktxnid']; 
                if($getdata){
	            $this->c_model->saveupdate('dt_aeps',['complaint'=>'y'],null,$where);
	            }	
			}

	else if($servicename == 'aepssettle'){
	$where['id'] = $tableid; 
	$keys = 'id, userid, add_date,amount, subject, status, referenceid ';
	$getdata = $this->c_model->getSingle('dt_wallet_aeps',$where,$keys);  

    $token = preg_replace('#[0-9 ]*#', '', $getdata['referenceid']);
    $output['userid'] = (string)$getdata['userid'];
    $output['orderinfo'] = (string)$getdata['referenceid'];
    $output['txndate'] = (string)$getdata['add_date'];
    $output['amount'] = (string)$getdata['amount'];
    $output['txnstatus'] = (string)$getdata['status'];
    $output['statusinfo'] = (string)$getdata['add_date']; 
    $output['txnid'] = '';
		if( ($getdata['subject'] == 'aeps_tr_bk') && $getdata['referenceid'] ){
					$dmt = $this->dmtdata( $getdata['referenceid'] );
					if(!empty($dmt)){
					$dmt = $dmt[0];
					$output['txndate'] = (string)$dmt['add_date'];
					$output['amount'] = (string)$dmt['amount'];
					$output['txnstatus'] = (string)$dmt['status'];
					$output['statusinfo'] = (string)$dmt['status_update']; 
					$output['txnid'] = (string)$dmt['ptm_rrn']; 
					}

		} 
        if($getdata){
        $this->c_model->saveupdate('dt_wallet_aeps',['complaint'=>'y'],null,$where);
        }	
	}


			$output['addby'] = $userid;
			$output['status'] = 'pending';
			$validate = $output;
			unset($validate['statusinfo']);
			$output['complainton'] = date('Y-m-d H:i:s'); 
				 
            $coutiem = $this->c_model->countitem('dt_complaints',$validate);
			$insertid = !$coutiem?$this->c_model->saveupdate('dt_complaints',$output):false; 

		
			if( $insertid ){  
			$token = $token.'-'.$insertid;	
			$output['ticketno'] = $token;
			$this->c_model->saveupdate('dt_complaints',['ticketno'=>$token],null,['id'=>$insertid]); 
			$response['status'] = TRUE; 
			$response['data'] = $output;
		    $response['message'] = "Request submitted successfully!";
			}else if(!$status){
			
			$response['status'] = FALSE;
		    $response['message'] = "Duplicate entry!";	
			} 



		}else{ 
			$response['status'] = FALSE;
		    $response['message'] = "User not exists!";		
			}
			 
	
		
		}else{ 
		        $response['status'] = FALSE;
				$response['message'] = 'Bad Request!';
				}
		
		
		//header("Content-Type: application/json");
		echo json_encode($response);
	
	}


public function dmtdata( $orderid ){
     $select = 'a.mode,a.amount,a.add_date,a.status,a.sur_charge,b.account_no,c.bankname,a.status_update,a.ptm_rrn';
     $where['a.orderid'] = $orderid;
     $from = 'dt_dmtlog_aeps as a';
      
     $join[0]['table'] = 'dt_aeps_bank_details as b';
     $join[0]['joinon'] = 'a.bank_id = b.id';
     $join[0]['jointype'] = 'LEFT';

     $join[1]['table'] = 'dt_bank as c';
     $join[1]['joinon'] = 'a.bank_id = c.id';
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