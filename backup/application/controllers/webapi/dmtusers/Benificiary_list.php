<?php defined("BASEPATH") OR exit("No Direct Access Allowed!");

class Benificiary_list extends CI_Controller{
	
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
		$table = 'benificiary';

	if( ($_SERVER['REQUEST_METHOD'] == 'POST') ){

			 
	    $sender_id = isset($request['sender_id'])?$request['sender_id']:null;
			


	    $checkaddby = $this->c_model->countitem('sender',array('id'=>$sender_id));
        
        if( $checkaddby == 1 ){

        	   $filterby = isset($request['filterby'])?$request['filterby']:null;
			   $requestparam = isset($request['requestparam'])?$request['requestparam']:null;
			   
			   $limit = isset($request['limit'])?$request['limit']:10;
			   $start = isset($request['start'])?$request['start']:0;
			   $start = $limit * $start;
			   $orderby = isset($request['orderby'])?$request['orderby']:'DESC'; 
			   
			   $whereorkey = NULL; 
			   $whereor    = NULL; 
			   

 
                 
                $where['dt_benificiary.sender_id'] = $sender_id;
                $where['dt_benificiary.status !='] = 'no';
				$select = 'dt_benificiary.id,dt_benificiary.add_by,dt_benificiary.name,dt_benificiary.mobile,dt_benificiary.add_date,dt_benificiary.ac_number,dt_benificiary.ifsc_code,dt_benificiary.acc_verification, dt_bank.bankname,dt_sender.name AS sender_name,dt_bank.id as bankid';
				$from = 'dt_benificiary';
				$jointable = 'dt_sender';
				$joinon = 'dt_benificiary.sender_id = dt_sender.id';
				$jointype = 'INNER';
				$groupby = null;
				$orderby = 'id '.$orderby;  
				$jointable3 = 'dt_bank';
				$joinon3 = 'dt_bank.id = dt_benificiary.bankname';
				$jointype3 = 'LEFT';

                $count = $this->c_model->joindata( $select, $where, $from, $jointable, $joinon, $jointype,$groupby,$orderby,$jointable3,$joinon3, $jointype3 ,null, null,'count');
                $data = $this->c_model->joindata( $select, $where, $from, $jointable, $joinon, $jointype,$groupby,$orderby,$jointable3,$joinon3, $jointype3 ,$limit, $start,'get');

 


	        if( !is_null($data) ){  
            	$response['status'] = TRUE;
            	$response['data'] = $data;
            	$response['count'] = $count;
			    $response['message'] = ' Records matched!';
            }else{ 
            	$response['status'] = FALSE;
            	$response['count'] = $count;
			    $response['message'] = 'No records matched!';
            }


            


        }else{ 
			$response['status']= FALSE;
			$response['count'] = '';
			$response['message']= 'This logged user not found in our database!';
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