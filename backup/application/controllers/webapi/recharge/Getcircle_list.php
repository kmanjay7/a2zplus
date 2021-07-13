<?php defined("BASEPATH") OR exit("No Direct Access Allowed!");

class Getcircle_list extends CI_Controller{
	
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

		if( ($_SERVER['REQUEST_METHOD'] == 'GET') ){ 

				$where['serviceid'] = 5;
				$where['status'] = 'yes';
				$keys = 'id,circle';
				$orderby = 'circle ASC';
				$buffer = $this->c_model->getAll('operator_circle',$orderby,$where, $keys ); 

				if(!is_null($buffer)){
					$response['status'] = TRUE;
					$response['data'] = $buffer;
					$response['message'] = 'Result macthed!';
				}else{
					$response['status'] = FALSE;
					$response['message'] = 'No record found!';
				}

			
		}else{ 
			$response['status']= FALSE;
			$response['message']= 'Bad request!'; 
		}

   		header("Content-Type: application/json");
		echo json_encode( $response );
	}

	

}
?>