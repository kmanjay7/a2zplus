<?php defined("BASEPATH") OR exit("No Direct Access Allowed!");

class Getifsc_code extends CI_Controller{
	
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
		$table = 'dt_bank';

	if( ($_SERVER['REQUEST_METHOD'] == 'POST') ){

			 
	    $bankid = isset($request['bankid'])?$request['bankid']:fasle;

	    if(!$bankid){
			$response['status'] = FALSE; 
			$response['message'] = 'No records matched!';
			header("Content-Type: application/json");
			echo json_encode( $response );
			exit;
	    }  


        $arr = $this->c_model->getSingle($table ,array('id'=>$bankid),'id,master_ifsc');
        $data['master_ifsc'] = isset($arr['master_ifsc'])?$arr['master_ifsc']:'';

	        if( !empty($arr) && !is_null($arr) ){  
            	$response['status'] = TRUE;
            	$response['data'] = $data; 
			    $response['message'] = 'Bank Id is blank!';
            }else{ 
            	$response['status'] = FALSE; 
			    $response['message'] = 'No records matched!';
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