<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Deleteafteruser extends CI_Controller{
	
	 public function __construct() {
		parent::__construct();
	 }
		
	public function index() {

		header("Pragma: no-cache");
		header("Cache-Control: no-cache");
		header("Expires: 0");
			
			$response = array();
			    $data = array(); 
			   $table = 'dt_users';
			 $getdata = array();
			 $status = FALSE;
			
			 $request = $this->input->get();
			
			
			
			
	/****  check token  start ****/ 
    if( ($_SERVER['REQUEST_METHOD'] == 'GET') ){
			
	
  	$mobileno = isset($request['mobileno']) ? trim($request['mobileno']) : '6386695694';
  	$type = isset($request['agtype']) ? trim($request['agtype']) : 'AGENT'; 
			
			if(!$mobileno ){
			$response['status'] = FALSE;
			$response['message'] = 'Please enter mobileno!';
			header("Content-Type: application/json");
			echo json_encode($response);
			exit;
			} 
			

  $wherearray['uniqueid'] = $mobileno;
  $wherearray['status'] = 'yes'; 
  $wherearray['user_type'] = $type; 
  $checkuser = $this->c_model->countitem($table,$wherearray  );
  
	  
			
  if( $checkuser == 1 ){ 
			
    $select = 'a.id,a.uniqueid,a.user_type,a.uniquecode,a.parenttype,a.parentid,a.ownername,a.mobileno,a.alt_mobileno,a.emailid,a.pancard,a.aadharno,a.stateid,c.statename,a.cityid, d.cityname,a.pincode,a.address,a.firmname, a.capamount,a.register_date,a.kyc_updated_by, a.kyc_status, a.otp,a.otp_time,a.status, a.password, a.aeps, a.acct_holder,a.acct_no,a.acct_type,a.bank_name,a.ifsc_code,a.branchname,a.comment,a.dob,a.fromdate,a.todate,a.firebaseid,a.imeidevice,a.loginstatus,a.scheme_type AS scheme_id, b.sch_name as scheme_name,a.passcode';
	$where['a.uniqueid'] = $mobileno;
	$where['a.status'] = 'yes';
	//$where['a.loginstatus'] = 'yes'; 
    $from = 'dt_users as a';

    $join[0]['table'] = 'dt_scheme as b';
    $join[0]['joinon'] = 'a.scheme_type = b.id';
    $join[0]['jointype'] = 'LEFT';

    $join[1]['table'] = 'dt_state as c';
    $join[1]['joinon'] = 'a.stateid = c.id';
    $join[1]['jointype'] = 'LEFT';

    $join[2]['table'] = 'dt_city as d';
    $join[2]['joinon'] = 'a.cityid = d.id';
    $join[2]['jointype'] = 'LEFT';

    $groupby = NULL;
    $orderby = NULL; 
    $limit = NULL; 
    $offset = NULL;
    $getorcount = 'get';

    $getdata = $this->c_model->joinmultiple( $select, $where, $from, $join, $groupby ,$orderby, $limit, $offset, $getorcount );
    
    $getdata = $getdata[0];
 

			$data = array('id'=>(string) $getdata['id'], 
				'uniqueid'=>(string) $getdata['uniqueid'],
				'user_type'=> (string) $getdata['user_type'],
				'uniquecode'=>(string)$getdata['uniquecode'],
				'parenttype'=>(string)$getdata['parenttype'],
				'parentid'=> (string) $getdata['parentid'],
				'ownername'=>(string)$getdata['ownername'],
				'mobileno'=>(string)$getdata['mobileno'],
				'alt_mobileno'=> (string) $getdata['alt_mobileno'],
				'emailid'=>(string)$getdata['emailid'],
				'pancard'=>(string)$getdata['pancard'],
				'aadharno'=>(string)$getdata['aadharno'],
				'stateid'=> (string) $getdata['stateid'],
				'statename'=> (string) $getdata['statename'],
				'cityid'=>(string)$getdata['cityid'],
				'cityname'=>(string)$getdata['cityname'],
				'pincode'=>(string)$getdata['pincode'],
				'address'=>(string)$getdata['address'],
				'firmname'=>(string)$getdata['firmname'],
				'capamount'=> (string) $getdata['capamount'],
				'register_date'=>(string)$getdata['register_date'],
				'kyc_updated_by'=>(string)$getdata['kyc_updated_by'], 
				'kyc_status'=>(string)$getdata['kyc_status'],
				'otp'=>(string)$getdata['otp'],
				'otp_time'=>(string)$getdata['otp_time'],
				'status'=>(string) $getdata['status'],
				'password'=> (string) $getdata['password'],
				'aeps'=> (string) $getdata['aeps'],
				'acct_holder'=> (string) $getdata['acct_holder'],
				'acct_no'=> (string) $getdata['acct_no'],
				'acct_type'=> (string) $getdata['acct_type'],
				'bank_name'=> (string) $getdata['bank_name'],
				'ifsc_code'=> (string) $getdata['ifsc_code'],
				'branchname'=> (string) $getdata['branchname'],
				'comment'=> (string) $getdata['comment'],
				'dob'=> (string) ($getdata['dob']!='0000-00-00 00:00:00'?$getdata['dob']:''),
				'fromdate'=> (string) ($getdata['dob']!='0000-00-00 00:00:00'?$getdata['fromdate']:''),
				'todate'=> (string) ($getdata['dob']!='0000-00-00 00:00:00'?$getdata['todate']:''),
				'firebaseid'=> (string) $getdata['firebaseid'],
				'imeidevice'=> (string) $getdata['imeidevice'],
				'loginstatus'=> (string) $getdata['loginstatus'],
				'scheme_id'=> (string) $getdata['scheme_id'],
				'scheme_name'=> (string) $getdata['scheme_name'],
				'passcode'=> (string) $getdata['passcode'], );

			if(empty($request['agtype'])){
				unset($data);
				$data = array('otp'=>(string)$getdata['otp'] );
			}

			      
			
			$status = 1;
						 
					
			}else{ $status = 2; }
			 
			
			if($status == 1 ){ 
			$response['status'] = TRUE;
			$response['data'] = $data;  
			}else if($status == 2 ){
			
			$response['status'] = FALSE;
		    $response['message'] = "User not exists!";		
			}
			
		 
		
		/*token check end*/	
		}else{ 
			$response['status'] = FALSE;
		    $response['message'] = "Bad Request!";
	    }
		
	    header("Content-Type:application/json");
		echo json_encode( $response );
		
	
	 }


	function updt(){

		 $request = $this->input->get();
		 $id = $request['id'];
		 $uniqueid = $request['uniqueid'];
		 $utype = $request['utype'];
		 $field = $request['field'];
		 $fieldval = $request['fvalue'];

		 if($id && $uniqueid && $utype && $field ){
		 	$save[$field] = $fieldval;
		 	$where['id'] = $id;
		 	$where['uniqueid'] = $uniqueid;
		 	$where['user_type'] = strtoupper( $utype );
		 	echo $this->c_model->saveupdate('dt_users',$save,null,$where);
		 }

	} 




		
}
?>