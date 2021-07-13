<?php defined("BASEPATH") OR exit("No Direct Access Allowed!");

class Bank_list extends CI_Controller{
	
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
		$table = 'bank';
		$where = [];

		if( ($_SERVER['REQUEST_METHOD'] == 'POST') ){
			 if($request['aeps_enable'] =='yes'){
				$where['bank_iin !='] = '';
			 }
			  
	    }else{ $where['status'] = 'yes'; }		
    
        /*$where['status'] = 'yes';*/
        $keys = 'id,bankname,master_ifsc,bank_iin';
        $dataarray = $this->c_model->getAll($table,'bankname ASC',$where,$keys );


	        if( !is_null($dataarray)){  
            	$response['status'] = TRUE;
            	$response['data'] = $dataarray;
			    $response['message'] = 'Records matched!';
            }else{ 
            	$response['status'] = FALSE;
			    $response['message'] = 'No records matched!';
            }
 

   		header("Content-Type: application/json");
		echo json_encode( $response );
	}
 
}
?>