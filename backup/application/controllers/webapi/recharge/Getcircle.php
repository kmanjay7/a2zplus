<?php defined("BASEPATH") OR exit("No Direct Access Allowed!");

class Getcircle extends CI_Controller{
	
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
				 $mobileno = $request['mobileno'];
				 $operator = $request['operatorcode'];

			if( $mobileno && ( strlen($mobileno) == 10 ) && $operator ){
				$obj = new $this->rechargeapi;
				$buffer = $obj->getcircle($mobileno,$operator); 
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