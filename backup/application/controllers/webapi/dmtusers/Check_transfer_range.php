<?php defined("BASEPATH") OR exit("No Direct Access Allowed!");

class Check_transfer_range extends CI_Controller{
	
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
		$table = 'operators'; 
		$pertransaction = 5000;
		$total = false;


	if( ($_SERVER['REQUEST_METHOD'] == 'POST') ){

			
			$amount = isset($request['amount'])?trim($request['amount']):false; 
			$schemetype = isset($request['scheme_type'])?trim($request['scheme_type']):false; 
			$apiid = isset($request['apiid'])?trim($request['apiid']):false; 
			$amountinput = $amount;   
        if( $amount > 0 ){  

        	    $runloop = 1;
				$totalrunloop = $runloop;
				$restamount = 0; 

				if( $amount > $pertransaction){ 
					$runloop = floor( $amount / $pertransaction );
					$totalrunloop = $runloop;
					$restamount = $amount - ($runloop*$pertransaction) ;
					if( $restamount > 0 ){ $totalrunloop += 1;  }
					$amount = $pertransaction;
				} 

$output = array();
$commision = false;

		for ($i=1; $i <= $totalrunloop; $i++) { //start for loop

				if( ($i > $runloop) && $restamount ) { $amount = $restamount; } 

					$checkamt['min <='] = $amount;
					$checkamt['max >='] = $amount;
					$checkamt['service'] = '6';
					$dbdata = $this->fnd_model->getSingle_fnd($table,$checkamt,'*');

					$comwhere['user_type'] = 'AGENT';
					$comwhere['operatorid'] = $dbdata['id'];
					$comwhere['serviceid'] = '6';
					if($schemetype){
						$comwhere['scheme_type'] = $schemetype;
					}
					if($apiid){
						$comwhere['apiid'] = $apiid;
					} 
					$commssionarray = $this->fnd_model->getSingle_fnd('dt_set_commission', $comwhere, 'surcharge_fixed, surcharge_percent, commision_fixed, commision_percent');
//print_r($commssionarray); exit;
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

	  /*start of calculate Agent comission */
	  if( $commssionarray['commision_fixed'] > 0 ){
		$commision =(float)$commssionarray['commision_fixed']; 
	  }else{

	    $percent_comi = percentage($arr['surcharge'],$commssionarray['commision_percent'] );
	  	$commision = (float)$percent_comi; 
	  }

	  $tds = (float) percentage($commision,TDS );
	  $arr['total_ag_commision'] = $commision;
	  $arr['ag_comi'] = ($commision - $tds);
	  $arr['ag_tds'] = $tds;
	  /*end of calculate Agent comission */

							}

						$arr['amount'] = $amount;
						array_push($output, $arr );	
		        
		} //end for loop

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

   		header("Content-Type: application/json");
		echo json_encode( $response );
	}
 
}
?>