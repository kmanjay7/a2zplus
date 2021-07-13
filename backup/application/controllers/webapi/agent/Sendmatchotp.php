<?php defined("BASEPATH") OR exit("No Direct Access Allowed!");

class Sendmatchotp extends CI_Controller{
	
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
			
			
		$uniqueid = !empty($request['mobileno']) ? trim($request['mobileno']) : '';
		$action = !empty($request['action']) ? trim($request['action']) : ''; 
		$otp = !empty($request['otp']) ? trim($request['otp']) : ''; 
		 
		 $uniqueid = filter_var( $uniqueid,FILTER_SANITIZE_NUMBER_INT );
		 if( strlen($uniqueid) != 10 ){
			$response['status'] = FALSE;
			$response['message'] = 'Please enter valid 10 digit mobile number!';
			header("Content-Type: application/json");
		    echo json_encode($response);
		    exit;
		 }else if(!$action ){
			$response['status'] = FALSE;
			$response['message'] = 'Please enter action!';
			header("Content-Type: application/json");
		    echo json_encode($response);
		    exit;
		 }else if(!$otp && ($action=='match')){
			$response['status'] = FALSE;
			$response['message'] = 'Please enter 4 digit OTP!';
			header("Content-Type: application/json");
		    echo json_encode($response);
		    exit;
		 }





		 $sendotpat = date('Y-m-d H:i:s');
		 $update = '';
		
		
		$where['uniqueid'] =  $uniqueid;
		$where['user_type'] = 'AGENT';
		$where['status'] = 'yes';

		$checkuser = $this->c_model->countitem($table,$where);
			
			
			
		/* Check User  start*/
		if( $checkuser == 1 ){

		$getdata = $this->c_model->getSingle($table,$where,' * '); 	
				
				    
			             
			if($action == 'match'){ 
				
				 if( $otp ){  
				 $timedeff = gettimedeffrence($getdata['otp_time'],$sendotpat);
				 $status = ( ($getdata['otp'] == $otp) && ( $timedeff <= 4 ) ) ? 1  : '';
				 $status =  empty($status) && ( $getdata['otp'] != $otp ) ?  2  : $status; 
				 $status =  empty($status) && ( $timedeff > 4  ) ?  3  : $status;  
				 }else{ $status = 4; }
				 
				 
				 
			}else if($action == 'resend'){
			$otp = generateOtp();	
            $insert['otp'] = $otp;
            $insert['otp_time'] = $sendotpat; 
	        $insertq = $this->c_model->saveupdate($table,$insert,null,$where) ;
		    $status = 5;  
			}
					
		}else{ $status = 6; }
			
			$mobileno = !empty($getdata['mobileno']) ? $getdata['mobileno'] : $uniqueid ;
			
			if( $status == 1  || $status == 5 ){
			$select = 'a.id,a.uniqueid,a.user_type,a.uniquecode,a.parenttype,a.parentid,a.ownername,a.mobileno,a.alt_mobileno,a.emailid,a.pancard,a.aadharno,a.stateid,c.statename,a.cityid, d.cityname,a.pincode,a.address,a.firmname, a.capamount,a.register_date,a.kyc_updated_by, a.kyc_status, a.otp,a.otp_time,a.status, a.password, a.aeps, a.acct_holder,a.acct_no,a.acct_type,a.bank_name,a.ifsc_code,a.branchname,a.comment,a.dob,a.fromdate,a.todate,a.firebaseid,a.imeidevice,a.loginstatus,a.scheme_type AS scheme_id, b.sch_name as scheme_name';
	$wheredt['a.uniqueid'] = $uniqueid;
	$wheredt['a.user_type'] = 'AGENT';
	$wheredt['a.status'] = 'yes'; 
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
    
    $getdata = array();
    $getdata = $this->c_model->joinmultiple( $select, $wheredt, $from, $join, $groupby ,$orderby, $limit, $offset, $getorcount );
    $getdata = $getdata[0];


    $validity = 'no';
    /*check plan validity script start here */
    $now = strtotime( date('Y-m-d H:i:s') ); 
    $vfrom = strtotime($getdata['fromdate']); 
    $vto = strtotime($getdata['todate']);
    if( ( $now >= $vfrom ) && ($now <= $vto) ){
    	$validity = 'yes';
    }
    /*check plan validity script end here */
	$kyc_status = '';
	if( ($getdata['kyc_status'] == 'no') || ($getdata['kyc_status'] == '') ){
     $kyc_status = 'pending';
 	}else if( ($getdata['kyc_status'] == 'reject') ){
     $kyc_status = 'pending';
 	}else if( ($getdata['kyc_status'] == 'pending') ){
     $kyc_status = 'onscreening';
 	}else if( ($getdata['kyc_status'] == 'yes') ){
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
				'plan_validity'=> (string) $validity, );
			
			
			}
			
			if( ($status == 1) && ($kyc_status != 'onscreening') ){  
			$updatearray['loginstatus'] = 'yes';
			$update = $this->c_model->saveupdate($table,$updatearray, NULL, $where) ;
						 
			$response['status'] = TRUE;
			$response['data'] = $data; 
		    $response['message'] = "OTP verification done successfully!";
			}else if(  ($status == 1) && ($kyc_status == 'onscreening') ){ 

			$response['status'] = FALSE;
		    $response['message'] = "Your KYC is onscreening!";	
			}else if($status == 2 ){
			
			$response['status'] = FALSE;
		    $response['message'] = "OTP not matched!";	
			}else if($status == 3 ){
			
			$response['status'] = FALSE;
		    $response['message'] = "OTP expired!";	
			}else if($status == 4 ){
			
			$response['status'] = FALSE;
		    $response['message'] = "OTP is blank!";		
			}else if($status == 5 ){
			$msgbody = 'Dear '.strtoupper($getdata['ownername']).', Your OTP to login your DigiCash India Account is - '.$otp.'. Never share your Login Password or OTP with anyone.
Regards,
DigiCash India.'; 	
			$sendsms = simplesms( $mobileno , $msgbody); 			
			$response['status'] = TRUE;
			$response['data'] = $data;
		    $response['message'] = "OTP send successfully at your registered mobile number!";
			}else if($status == 6 ){ 
			$response['status'] = FALSE;
		    $response['message'] = "User not exists!";		
			}
			 
		
		
		
		}else{ 
		        $response['status'] = FALSE;
				$response['message'] = 'Bad Request!';
				}
		
		
		header("Content-Type: application/json");
		echo json_encode($response);
	
	}
		
}
?>