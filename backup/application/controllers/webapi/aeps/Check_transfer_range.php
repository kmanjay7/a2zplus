<?php defined("BASEPATH") OR exit("No Direct Access Allowed!");

class Check_transfer_range extends CI_Controller{
	
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
		$table = 'operators'; 
		$pertransaction = 5000;
		$total = false;


	if( ($_SERVER['REQUEST_METHOD'] == 'POST') ){

			
			$amount = isset($request['amount'])?trim($request['amount']):false; 
			$schemetype = isset($request['schemetype'])?trim($request['schemetype']):false; 
			$amountinput = $amount;   
        if( $amount > 0 ){  

        	  

$output = array();

	 

				 

					$checkamt['min <='] = $amount;
					$checkamt['max >='] = $amount;
					$checkamt['service'] = 1;
					$checkamt['currentapiid'] = 10;
					$dbdata = ci()->c_model->getSingle($table,$checkamt,'*');

					$comwhere['user_type'] = 'AGENT';
					$comwhere['operatorid'] = $dbdata['id'];
					$comwhere['serviceid'] = 1;
					if($schemetype){
						$comwhere['scheme_type'] = $schemetype;
					} 
					$commssionarray = ci()->c_model->getSingle('dt_set_commission', $comwhere, 'surcharge_fixed, surcharge_percent');

					$arr['operatorid'] = $dbdata['id'];
					$arr['operatorname'] = $dbdata['operator'];

							if(!is_null($dbdata) && !empty($dbdata)){

							  if( $commssionarray['surcharge_fixed'] > 0 ){
								$total = $total + $commssionarray['surcharge_fixed'];
								$arr['surcharge'] = $commssionarray['surcharge_fixed'];
							  }else{

							    $percent = percentage($amount,$commssionarray['surcharge_percent'] );
							  	$total = $total + $percent;
							  	$arr['surcharge'] = $percent;
							  } 

							}

							$arr['amount'] = $amount;
						array_push($output, $arr );	
		        
		 

            $response['status'] = TRUE;
            $response['debitamount'] = ( ( $total + $amountinput ));
            $response['totalsurcharge'] = ( $total );
            $response['dataarray'] = $output;
			$response['message'] = 'Amount calculated!';


        }else{ 
			$response['status'] = FALSE;
			$response['message'] = 'Amount is blank!';
        }
     
        
	}else{ 
		$response['status'] = FALSE;
		$response['message'] = 'Bad request!'; 
	}

   		//header("Content-Type: application/json");
		echo json_encode( $response );
	}
 
}
?>