<?php defined("BASEPATH") OR exit("No Direct Access Allowed!");

class Check_transaction_limit extends CI_Controller{
	
public function __construct(){
	parent::__construct();
	$this->load->model('Fundtransfer_model','fnd_model');   
	}
		

public function index(){

		header("Pragma: no-cache");
		header("Cache-Control: no-cache");
		header("Expires: 0");

		$response = array();
		$data = array();
		$request = requestJson();
		$table = 'sender';

	if( ($_SERVER['REQUEST_METHOD'] == 'POST') ){

			 
		$senderid = isset($request['sender_id'])?trim($request['sender_id']):null; 
		$ckwhere['id'] = $senderid;	 
	    $checkaddby = $this->fnd_model->countitem_fnd($table,$ckwhere );
        
         if( $checkaddby == 1 ){ 
        	 
 
	        $keys = 'SUM(`amount`) AS total, sender_id ';
	        $where['sender_id'] = $senderid;
	        $where["DATE_FORMAT(add_date, '%Y-%m')="] = date('Y-m');
	       // $where['status !='] = 'FAILURE';
	        $not_inkey = 'status';
	        $not_invalue = 'FAILURE,REQUEST';
	        $data = $this->fnd_model->getSingle_fnd('dmtlog',$where,$keys , null,null,null,null,$not_inkey,$not_invalue ); 

	        $total = $data['total'];
	       
            	$response['status'] = TRUE;
				$response['total_limit'] = (string) 25000;
				$response['available_limit'] = (string) ( 25000 - $total );
				$response['used_limit'] = (string) $total;
			    $response['message'] = 'Data matched in our database!'; 

        }else{ 
			$response['status'] = FALSE;
			$response['message'] = 'User not found in our database!';
        }
     
        
	}else{ 
		$response['status'] = FALSE;
		$response['message'] = 'Bad request!'; 
	}

   		header("Content-Type: application/json");
		echo json_encode( $response );
	}
 
}
?>