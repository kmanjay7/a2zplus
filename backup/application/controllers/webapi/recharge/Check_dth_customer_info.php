<?php defined("BASEPATH") OR exit("No Direct Access Allowed!");

class Check_dth_customer_info extends CI_Controller{
	
public function __construct(){
	parent::__construct(); 
	$this->load->library('rechargeapi'); 
	}
		

public function index(){

		header("Pragma: no-cache");
		header("Cache-Control: no-cache");
		header("Expires: 0");
		$serviceid = 3;

		$response = array();
		$data = array();
		$request = requestJson();

		if( ($_SERVER['REQUEST_METHOD'] == 'POST') ){
				
				$operator = trim($request['operator']);
				$customercv = isset($request['customercv'])?trim($request['customercv']):null;
				$offer = isset($request['offer'])?trim($request['offer']):null;


				$op_where['service'] = $serviceid;
				$op_where['operator'] = $operator;
				$op_data_arr = $this->c_model->getSingle('operators',$op_where,'id,currentapiid'); 

				$whereop['serviceid'] = $serviceid;
				$whereop['operatorid'] = $op_data_arr['id'];
				$whereop['apiid'] = 4;//$op_data_arr['currentapiid'];
				$op_code_data = $this->c_model->getSingle('operators_code',$whereop,'operatorid,op_code');
				$arr['operator'] = $op_code_data['op_code']; 
 
            
			if( $operator && $customercv){
				$obj = new $this->rechargeapi; 
				$post['operator'] = $arr['operator'];
				$post['customerid'] = $customercv;
				$post['offer'] = $offer;
				$buffer = $obj->mplan_check_dth_customer_info($post); 

				if(!is_null($buffer)){
					$response['status']= TRUE;
					$response['data'] = $buffer;
					$response['message']= 'Result macthed!';
				}else{
					$response['status']= FALSE;
					$response['message']= 'No match found!';
				}

			}else{
					$response['status']= FALSE;
					$response['message']= 'Please fill the required fields!';
				}
		}
		else{ 
			$response['status']= FALSE;
			$response['message']= 'Bad request!'; 
		}

   	   header("Content-Type: application/json");
		echo json_encode( $response );
	}
 
}
?>