<?php defined("BASEPATH") OR exit("No Direct Access Allowed!");

class Check_mobile_operator extends CI_Controller{
	
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
				$mobileno = isset($request['mobileno'])?$request['mobileno']:null;  
            
			if( strlen($mobileno) == 10  && $mobileno ){
				$obj = new $this->rechargeapi;  
				$buffer = $obj->mplan_checkoperator($mobileno); 
				if(!is_null($buffer) && isset($buffer['records']['status'])){
					/*modify circle name start*/
					$response['status']= TRUE;
					$response['data'] = $this->modify_operator($buffer['records']);
					$response['message']= 'Result matched!';
				}else if(!is_null($buffer) && !$buffer['status']){
					$response['status']= FALSE; 
					$response['message']= $buffer['records']['msg'];
				}else{
					$response['status']= FALSE;
					$response['message']= 'No match found!';
				}

			}else{
					$response['status']= FALSE;
					$response['message']= 'Please enter 10 digit mobile number!';
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
      if($clarray['circle'] == 'UP(E)'){
      	$clarray['circle'] = 'UP East';
      }else if($clarray['circle'] == 'UP(W)'){
      	$clarray['circle'] = 'UP West';
      }else if($clarray['circle'] == 'UPEast'){
      	$clarray['circle'] = 'UP East';
      }else if($clarray['circle'] == 'UPWest'){
      	$clarray['circle'] = 'UP West';
      }else if($clarray['circle'] == 'UPE'){
      	$clarray['circle'] = 'UP East';
      }else if($clarray['circle'] == 'Bihar and Jharkhand'){
      	$clarray['circle'] = 'Bihar Jharkhand';
      }
   }
   return $clarray;
}	
 
}
?>