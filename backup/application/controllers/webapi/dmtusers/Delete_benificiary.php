<?php defined("BASEPATH") OR exit("No Direct Access Allowed!");

class Delete_benificiary extends CI_Controller{
	
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
		$table = 'dt_benificiary';

	if( ($_SERVER['REQUEST_METHOD'] == 'POST') ){

			 
	    $benifi_id = isset($request['benifi_id'])?$request['benifi_id']:fasle;
	    $loginuser_id = isset($request['loginuser_id'])?$request['loginuser_id']:fasle;

	    if(!$benifi_id){
			$response['status'] = FALSE; 
			$response['message'] = 'Benificiary Id is Blank!';
			header("Content-Type: application/json");
			echo json_encode( $response );
			exit;
	    }else if(!$loginuser_id){
			$response['status'] = FALSE; 
			$response['message'] = 'Logged User Id is Blank!';
			header("Content-Type: application/json");
			echo json_encode( $response );
			exit;
	    }  

        $where['id'] = $benifi_id;
        $where['add_by'] = $loginuser_id;

        $countitem = $this->c_model->countitem($table ,$where);
         

	        if( $countitem ){ 
	            $this->c_model->saveupdate($table,['status'=>'no'],null,$where ); 
            	$response['status'] = TRUE; 
			    $response['message'] = 'Benificiary removed successfully!';
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