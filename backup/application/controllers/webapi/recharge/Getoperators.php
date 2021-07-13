<?php defined("BASEPATH") OR exit("No Direct Access Allowed!");

class Getoperators extends CI_Controller{
	
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
			$serviceid = $request['serviceid']; 

			if( $serviceid ){
 			$apiid = $this->c_model->getSingle('services',array('id'=>$serviceid),'currentapiid');
			$where['operators_code.serviceid'] = $serviceid;
			$where['operators_code.apiid'] = $apiid;
			$where['operators_code.status'] = 'yes';

			$select = 'operators_code.op_code, operators.operator'; 
			$from = 'operators_code';
			$jointable = 'services';
			$joinon = 'operators_code.serviceid = services.id ';
			$jointype = 'LEFT';

			$jointable3 = 'operators';
			$joinon3 = 'operators_code.operatorid = operators.id '; 
			$jointype3 = 'LEFT';


            $buffer = $this->c_model->joindata( $select, $where, $from, $jointable, $joinon, $jointype,null,null,$jointable3,$joinon3,$jointype3 ); 
            
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