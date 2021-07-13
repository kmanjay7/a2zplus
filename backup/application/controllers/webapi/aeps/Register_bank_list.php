<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Register_bank_list extends CI_Controller{
	var $serviceid;
	 public function __construct() {
		parent::__construct();
		$this->serviceid = 1;
	 }
	 
		
	public function index() {
			
			$response = array();
			    $data = array();  
			  
			   $table = 'dt_aeps_bank_details';  
			$request = requestJson();
			
 /****  check Request  Status start ****/ 
 if( ($_SERVER['REQUEST_METHOD'] == 'POST') ){
			
	           
	           $user_id = $request['user_id'];
	           $usertype = $request['usertype'];

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
			   


				$where['id'] = $user_id ;
				$where['user_type'] = $usertype; 

				$countitem = $this->c_model->countitem('dt_users',$where);

			if( $countitem != 1 ){
				$response['status'] = FALSE;
				$response['message'] = 'User not exists!';
				header("Content-Type: application/json");
				echo json_encode( $response );
				exit;
			} 

           
            $bkwhere['a.user_id'] = $user_id;
            $bkwhere['a.user_type'] = $usertype;

            $from = 'dt_aeps_bank_details as a';
            $select = 'a.id,a.account_name,a.account_no,b.bankname,a.status'; 

            $join[0]['table'] = 'dt_bank as b';
            $join[0]['joinon'] = 'a.bankid = b.id';
            $join[0]['jointype'] = 'LEFT';

            $groupby = null;
            $orderby = 'a.id ASC';
            $limit = '10';
            $offset = '';
            $getorcount = 'get';


            $getdataarr = $this->c_model->joinmultiple( $select, $bkwhere, $from, $join, $groupby ,$orderby, $limit, $offset, $getorcount );
            ; 
 
//print_r($getdataarr);
 
 
			$getdata = array();
			if( !empty($getdataarr) && !is_null($getdataarr) ){ 

					foreach ($getdataarr as $key => $value) {
						 $arr['id'] = (string)$value['id'];
						 $arr['account_name'] = (string)$value['account_name'];
						 $arr['account_no'] = (string)$value['account_no'];
						 $arr['bankname'] = (string)$value['bankname'];
						 $arr['status'] = (string)$value['status'];
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