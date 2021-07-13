<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Mrobotics_lapu_balance extends CI_Controller{
	 
	public  function __construct() {
         parent::__construct();
         $this->load->library('rechargeapi');  
      }


public function index(){

 header("Pragma: no-cache");
 header("Cache-Control: no-cache");
 header("Expires: 0");
 $request = requestJson();
        
if( ($_SERVER['REQUEST_METHOD'] == 'POST') ){


			$uniqueid = isset($request['uniqueid']) ? trim($request['uniqueid']) : false;
			$operatorname = isset($request['operatorname']) ? trim($request['operatorname']) : false; 

			if( $uniqueid ==  md5(8115171716) && $operatorname ){  
				 
				$balance = false;
				$message = 'fetched Data !';
				$response = array();
				
				/* initialize an array */ 

$op_where['operator'] = $operatorname;
$op_data_arr = $this->c_model->getSingle('operators',$op_where,'id,currentapiid,service'); 

$whereop['serviceid'] = $op_data_arr['service'];
$whereop['operatorid'] = $op_data_arr['id'];
$whereop['apiid'] = $op_data_arr['currentapiid'];
$op_code_data = $this->c_model->getSingle('operators_code',$whereop,'operatorid,op_code');
$arr['operator'] = $op_code_data['op_code']; 



				$obj = new $this->rechargeapi;
				$buffer = $obj->mrobotics_lapu_balance( $operatorname );

				if(isset($buffer['error']) && !$buffer['error']){
					$buffer = $buffer['data'];
				} 
				
				//print_r($buffer);

				 $response['status'] = false;
				 $response['message'] = 'Failure';

				if(isset($buffer['status'])){
					$balance = $buffer['balance'];
					$response['status'] = true;
					$response['balance'] = (float) $balance; 
					$response['message'] = 'Success';
				} 

				
			 
			}else{ 
				$response['status'] = false; 
			    $response['message'] = 'Something went wrong !';
			}


}else{
	$response['status'] = false;
	$response['message'] = 'Bad Request!';
}
	header('Content-Type:application/json');
	echo json_encode($response);
} 

}
?>