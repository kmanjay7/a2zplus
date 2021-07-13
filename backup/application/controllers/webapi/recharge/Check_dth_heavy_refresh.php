<?php defined("BASEPATH") OR exit("No Direct Access Allowed!");

class Check_dth_heavy_refresh extends CI_Controller{
	

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

		if( ($_SERVER['REQUEST_METHOD'] == 'POST') ){
			$customercv = isset($request['customercv'])?trim($request['customercv']):false;
			$operator = isset($request['operator'])?trim($request['operator']):false;
			$offer = isset($request['offer'])?trim($request['offer']):false;  
            
			if( $customercv && $operator && $offer){
				$obj = new $this->rechargeapi; 
				$arr['customerid'] =  $customercv;
				$arr['operator'] =  $operator;
				$arr['offer'] =  $offer;
				$buffer = $obj->mplan_dth_heavy_refresh($arr);  
				if(!is_null($buffer) && isset($buffer['records']['status'])){ 
					$response['status'] = TRUE;
					$response['data'] = $buffer['records'];
					$response['message'] = 'Result matched!';
				}else if(!is_null($buffer) && !$buffer['status']){
					$response['status'] = FALSE; 
					$response['message'] = $buffer['records']['msg'];
				}else{
					$response['status'] = FALSE;
					$response['message'] = 'No match found!';
				}

			}else{
					$response['status'] = FALSE;
					$response['message']= 'Please enter customer number and operator!';
				}
		}
		else{ 
			$response['status'] = FALSE;
			$response['message']= 'Bad request!'; 
		}

   		header("Content-Type: application/json");
		echo json_encode( $response );
	}

  
}
?>