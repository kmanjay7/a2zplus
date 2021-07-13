<?php defined("BASEPATH") OR exit("No Direct Access Allowed!");

class Check_dth_offer extends CI_Controller{
	
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
			$customercv = isset($request['customercv'])?trim($request['customercv']):false;
			$operator = isset($request['operator'])?trim($request['operator']):false;
			$offer = isset($request['offer'])?trim($request['offer']):false; 
			$apptype = isset($request['apptype'])?trim($request['apptype']):null;  
            
			if( $customercv && $operator && $offer){
				$obj = new $this->rechargeapi; 
				$arr['customerid'] =  $customercv;
				$arr['operator'] =  $this->getOperator($operator,$offer);
				$arr['offer'] =  $offer; 
				$buffer = $obj->mplan_dth_plan($arr); 
				 
				if(!is_null($buffer) && isset($buffer['status']) && !empty($buffer['records'])){ 
					$response['status'] = TRUE;
					$response['data'] = $this->makelisting($buffer['records'],$offer, $apptype);
					$response['message'] = isset($buffer['records']['desc'])? ($buffer['records']['desc']) : 'Data Matched!';
				}else if(!is_null($buffer) && !$buffer['status']){
					$response['status'] = FALSE; 
					$response['message'] = 'Plan not Available!';
				}else{
					$response['status'] = FALSE;
					$response['message'] = 'Plan not Available!';
				}

			}else{
					$response['status'] = FALSE;
					$response['message']= 'Please enter customer number and operator!';
				}
		}
		else{ 
			$response['status'] = FALSE;
			$response['message']= 'Bad request!';
		}

   		header("Content-Type: application/json");
		echo json_encode( $response );
	}



public function getOperator($operator,$type){ 
	if($type=='simple'){
		if($operator=='Airteldth'){ $operator = 'Airtel dth'; } 
		else if($operator=='Dishtv'){ $operator = 'Dish TV'; }
		else if($operator=='Sundirect'){ $operator = 'Sun Direct'; }
		else if($operator=='TataSky'){ $operator = 'Tata Sky'; }
		else if($operator=='Videocon'){ $operator = 'Videocon'; }  
	}else if($type=='channel'){
		if($operator=='Airteldth'){ $operator = 'Airtel dth'; } 
		else if($operator=='Dishtv'){ $operator = 'Dish TV'; }
		else if($operator=='Sundirect'){ $operator = 'Sun Direct'; }
		else if($operator=='TataSky'){ $operator = 'Tata Sky '; }
		else if($operator=='Videocon'){ $operator = 'Videocon '; } 
		
	}else if($type=='roffer'){
		if($operator=='Airteldth'){ $operator = 'AirtelDTH'; } 
		else if($operator=='Sundirect'){ $operator = 'Sundirect '; } 
		
	}

	return urlencode($operator);

}	



public function makelisting($array,$offer,$apptype){
	 
  if( $apptype == 'app' && !empty($array)){
  	  $output = array(); 

		$arraykeys = array_keys($array);   
		foreach ($arraykeys as $menu){
			$push = array();
	      	foreach ($array[$menu] as $key => $value) { 
	      		 	 foreach ($value['rs'] as $keyy => $rmvalue) {
	      		     $arr['rs'] = $rmvalue;
	      		     $arr['validity'] = $keyy;
	      		 	 }  

	      		 $arr['plan_name'] = $value['plan_name']; 
	      		 $arr['desc'] = $value['desc']; 
	      		 //$arr['last_update'] = $value['last_update'];
	      		 
	      		 array_push($push, $arr );
	      	}

	      	$newarr['planname'] = $menu;
	      	$newarr['planlist'] = $push;
	      	array_push($output, $newarr );
        }

      return $output; 

  }else{
  	return $array;
  }
} 

  
}
?>