<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Checkloginstatus extends CI_Controller{
	
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
			
			 $request = requestJson();
			
			
			
			
	/****  check token  start ****/ 
    if( ($_SERVER['REQUEST_METHOD'] != 'POST') ){
			$response['status'] = FALSE;
			$response['message'] = "Bad Request!";
			header("Content-Type:application/json");
			echo json_encode( $response );
			exit;
	}
		
	  
			
	
  	$imeidevice = !empty($request['imeidevice']) ? $request['imeidevice'] : '';
  	$firebaseid = !empty($request['firebaseid']) ? $request['firebaseid'] : ''; 


			
			if(!$imeidevice ){
			$response['status'] = FALSE;
			$response['message'] = 'Please enter imei or device id!';
			header("Content-Type: application/json");
			echo json_encode($response);
			exit;
			}else if(!$firebaseid){
			$response['status'] = FALSE;
			$response['message'] = 'Please enter firebase id!';
			header("Content-Type: application/json");
			echo json_encode($response);
			exit;
			}
			

  $wherearray['imeidevice'] = $imeidevice;
  $wherearray['status'] = 'yes';
  $wherearray['loginstatus'] = 'yes';
  $wherearray['user_type'] = 'AGENT';
  $wherearray['kyc_status'] = 'yes';  
  $checkuser = $this->c_model->countitem($table,$wherearray  );
  
	  
			
  if( $checkuser != 1 ){ 
	$response['status'] = FALSE;
	$response['message'] = "User not exists!";
	header("Content-Type:application/json");
	echo json_encode( $response );
	exit;
  }
			
			 
			$updatearray['firebaseid'] = $firebaseid; 	
			$updatedata = $this->c_model->saveupdate($table,$updatearray,null,$wherearray ) ;
			 
			
    $select = 'a.id,a.uniqueid,a.user_type,a.uniquecode,a.parenttype,a.parentid,a.ownername,a.mobileno,a.alt_mobileno,a.emailid,a.pancard,a.aadharno,a.stateid,c.statename,a.cityid, d.cityname,a.pincode,a.address,a.firmname, a.capamount,a.register_date,a.kyc_updated_by, a.kyc_status, a.otp,a.otp_time,a.status, a.password, a.aeps, a.acct_holder,a.acct_no,a.acct_type,a.bank_name,a.ifsc_code,a.branchname,a.comment,a.dob,a.fromdate,a.todate,a.firebaseid,a.imeidevice,a.loginstatus,a.scheme_type AS scheme_id, b.sch_name as scheme_name';
	$where['a.imeidevice'] = $imeidevice;
	$where['a.user_type'] = 'AGENT'; 
	$where['a.status'] = 'yes';
	$where['a.loginstatus'] = 'yes';
	//$where['a.kyc_status'] = 'yes'; 
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


     if( in_array($getdata['kyc_status'],['reject','no']) ){
			$response['status'] = FALSE;
			$response['message'] = $getdata['comment'].'. Contact to your parent user!';
			header("Content-Type: application/json");
			echo json_encode($response);
			exit;
	 }



    $validity = 'no';
    /*check plan validity script start here */
    $now = strtotime( date('Y-m-d H:i:s') ); 
    $vfrom = strtotime($getdata['fromdate']); 
    $vto = strtotime($getdata['todate']);
    if( ( $now >= $vfrom ) && ($now <= $vto) ){
    	$validity = 'yes';
    }
    /*check plan validity script end here */
		 $kyc_status = $getdata['kyc_status'];
		 if(in_array($getdata['kyc_status'], ['complete','yes'])){
		 $kyc_status = 'complete';
		 }

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
				'kyc_status'=>(string)$kyc_status,
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
				'dob'=> (string) (strtotime($getdata['dob'])?$getdata['dob']:''),
				'fromdate'=> (string) (strtotime($getdata['fromdate'])?$getdata['fromdate']:''),
				'todate'=> (string) (strtotime($getdata['todate'])?$getdata['todate']:''),
				'firebaseid'=> (string) $getdata['firebaseid'],
				'imeidevice'=> (string) $getdata['imeidevice'],
				'loginstatus'=> (string) $getdata['loginstatus'],
				'scheme_id'=> (string) $getdata['scheme_id'],
				'scheme_name'=> (string) $getdata['scheme_name'],
				'plan_validity'=> (string) $validity );
 
			 
			$response['status'] = TRUE;
			$response['data'] = $data;  
			$response['message'] = "You are logged in successfully!"; 

			header("Content-Type:application/json");
			echo json_encode( $response ); 
	 }
		
}
?>