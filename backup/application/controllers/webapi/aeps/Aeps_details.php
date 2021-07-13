<?php defined('BASEPATH') OR exit('No direct script access allowed');



class Aeps_details extends CI_Controller{
     
	public function __construct(){
		parent::__construct();   
	}


	public function index(){

		header("Pragma: no-cache");
		header("Cache-Control: no-cache");
		header("Expires: 0");

		$response = array();
		$data = array();
		$request = requestJson(); 

		if( ($_SERVER['REQUEST_METHOD'] == 'POST') ){

		$userid = isset($request['userid'])?$request['userid']:null; 
		$user_type = isset($request['user_type'])?$request['user_type']:null;

			if( !$userid ){
				$response['status'] = FALSE;
				$response['message'] = 'User Id is blank!';
				header("Content-Type: application/json");
				echo json_encode( $response );
				exit;
			}else if( !$user_type ){
				$response['status'] = FALSE;
				$response['message'] = 'User type is blank!';
				header("Content-Type: application/json");
				echo json_encode( $response );
				exit;
			}



			$where['id'] = $userid;
			$where['user_type'] = $user_type; 

			$countitem = $this->c_model->countitem('dt_users',$where);

			if( $countitem != 1 ){
				$response['status'] = FALSE;
				$response['message'] = 'User not exists!';
				header("Content-Type: application/json");
				echo json_encode( $response );
				exit;
			} 



	$profile = $this->c_model->getSingle( 'dt_users', $where, 'devicename,aeps_status,outlet_id,aepspan_no,pancard,uniqueid' );

    $data['outletid'] = $profile['outlet_id'];
    //$data['aepsstatus'] = $profile['aeps_status'];
    $data['devicename'] = $profile['devicename'];
    $data['panno'] = strtoupper( $profile['aepspan_no']?$profile['aepspan_no']:$profile['pancard']);
    $data['openview'] = '';
    $data['text'] = '';
    $data['textcolor'] = '#000';
    $data['openview'] = 'aeps';

	if(!$profile['outlet_id'] && empty($profile['aeps_status']) ){
		$data['openview'] = 'entermobileno'; 
	}else if( $profile['outlet_id'] && empty($profile['aeps_status']) ){
		$data['openview'] = 'kycdetails'; 
	}else if( $profile['outlet_id'] && ($profile['aeps_status'] == 'reject') ){
		$data['openview'] = 'changemobile'; 
	}else if( $profile['outlet_id'] && ($profile['aeps_status'] == 'doc') ){
		$data['openview'] = 'uploaddoc'; 
	}


	if( $profile['aeps_status'] == 'pending'){
	  $data['openview'] = 'textmessage';
      $data['text'] = 'Your AEPS activation is in Proccess';
      $data['textcolor'] = '#000';

      		 $pdocs['userid'] = $userid;
             $pdocs['user_type'] = $user_type; 
             $pdocs['uniqueid'] = $profile['uniqueid']; 
             $pdocs['outletid'] = $profile['outlet_id'];
             $pdocs['pan_no'] = $data['panno'];

             $dcsurl = APIURL.('webapi/aeps/require_docs');
             $dcbuffer = curlApis($dcsurl,'POST',$pdocs);

                 if( $dcbuffer['status'] && isset($dcbuffer['data']['statuscode']) && $dcbuffer['data']['statuscode']=='TXN' ){
                     $bfdata = $dcbuffer['data']['data']; 
                     if($bfdata['SCREENING']){
                        $data['text'] = 'Your AEPS activation is in Proccess.';
                        $data['textcolor'] = '#000';
                     }else if($bfdata['REQUIRED']){
                        $data['text'] = 'Your AEPS activation has been marked as REJECTED.';
                        $data['textcolor'] = '#ff003b';
                     }

                 } 


	}else if( $profile['aeps_status'] == 'no'){
		      $data['openview'] = 'textmessage';
              $data['text'] = 'Your AEPS Service is Blocked';
              $data['textcolor'] = '#000'; 
    }else if( ($profile['aeps_status'] == 'yes') && empty($profile['devicename']) ){
              $data['openview'] = 'setdevice';  
    }


		$whereupload['usertype'] = $user_type;
		$whereupload['tableid'] = $userid; 
		$whereupload['documenttype'] = 'Aadhaar Card';

		$aadhaar = $this->c_model->getSingle('dt_uploads',$whereupload,'documentorimage');
		$data['aadhaar'] = $aadhaar ? ADMINURL.'uploads/'.$aadhaar : '';




    $datalist[0] = ['name'=>'Morpho','value'=>'Morpho RD Service'];
    $datalist[1] = ['name'=>'Mantra','value'=>'Mantra RD Service']; 

    $trsnlist[0] = ['name'=>'WAP','value'=>'Cash Withdrawal'];
    $trsnlist[1] = ['name'=>'SAP','value'=>'Mini Statement'];
    $trsnlist[2] = ['name'=>'BAP','value'=>'Balance Enquiry']; 



	$response['status'] = true;
	$response['data'] = $data;
	$response['devicelist'] = $datalist;
	$response['transactiontype'] = $trsnlist;
	$response['message']= 'Success!';



		}else{ 
		$response['status'] = FALSE;
		$response['message']= 'Bad request!'; 
		}

   		header("Content-Type: application/json");
		echo json_encode( $response );


	}

}
?>