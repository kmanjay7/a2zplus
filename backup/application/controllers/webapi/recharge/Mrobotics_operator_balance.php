<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Mrobotics_operator_balance extends CI_Controller{
	 
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

			if( $uniqueid ==  md5(8115171716) ){  
				 
				$balance = false;
				$message = 'fetched Data !';
				/* initialize an array */ 
				$obj = new $this->rechargeapi;
				$buffer = $obj->mrobotics_operator_balance(); 


				$response['status'] = false;
				$response['message'] = 'Failure';

				if(isset($buffer['error']) && !$buffer['error']){
					$buffer = $buffer['data'];
					$response['status'] = true;
					$response['data'] = $buffer; 
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
	//header('Content-Type:application/json');
	echo json_encode($response);
} 

}
?>