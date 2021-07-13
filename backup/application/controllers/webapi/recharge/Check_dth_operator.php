<?php defined("BASEPATH") OR exit("No Direct Access Allowed!");

class Check_dth_operator extends CI_Controller{
	

public function __construct(){
	parent::__construct(); 
	$this->load->library('rechargeapi'); 
	}
		
public function index(){

		header("Pragma: no-cache");
		header("Cache-Control: no-cache");
		header("Expires: 0");

		$response = array();
		$data = array();
		$request = requestJson();
		$buffer = array();

		if( ($_SERVER['REQUEST_METHOD'] == 'POST') ){
			$customercv = isset($request['customercv'])?$request['customercv']:false;  
            
			if( $customercv ){
				$obj = new $this->rechargeapi;  
				$buffer = $obj->mplan_dth_checkoperator($customercv); 
				if(!is_null($buffer) && isset($buffer['records']['status']) && !empty($buffer['records']['status']) ){ 
					$response['status'] = TRUE;
					$response['data'] = $this->modify_operator($buffer['records']);
					$response['message'] = 'Result matched!';
				}else if(!is_null($buffer) && !isset($buffer['records']['status']) && empty($buffer['records']['status']) ){
					$response['status'] = FALSE; 
					$response['message'] = 'Customer Not Found';
				}else{
					$response['status'] = FALSE;
					$response['message'] = 'Customer Not Found';
				}

			}else{
					$response['status']= FALSE;
					$response['message']= 'Please enter valid customer id!';
				}
		}
		else{ 
			$response['status'] = FALSE;
			$response['message']= 'Bad request!'; 
		}

   		header("Content-Type: application/json");
		echo json_encode( $response );
	}


public function modify_operator($clarray){
   if(isset($clarray['status'])){
      if($clarray['Operator'] == 'DishTv'){
      	$clarray['Operator'] = 'Dishtv';
      }else if($clarray['Operator'] == 'AirtelDth'){
      	$clarray['Operator'] = 'Airteldth';
      }
   } 
   return $clarray;
}	
 
}
?>