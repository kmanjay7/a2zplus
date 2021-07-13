<?php defined("BASEPATH") OR exit("No Direct Access Allowed!");

class Fetch_mobile_plan extends CI_Controller{
var $serviceid;	
public function __construct(){
	parent::__construct(); 
	$this->load->library('rechargeapi'); 
	$this->serviceid = 5;
	}
		

public function index(){

		header("Pragma: no-cache");
		header("Cache-Control: no-cache");
		header("Expires: 0");

		$response = array();
		$data = array();
		$request = requestJson();

		if( ($_SERVER['REQUEST_METHOD'] == 'POST') ){
				$circle = $request['circle'];
				$operator = urlencode( trim($request['operator']));
				$mobile = isset($request['mobile'])?$request['mobile']:null;
				$offer = isset($request['type'])?$request['type']:null;
				$apptype = isset($request['apptype'])?$request['apptype']:null; 
				$amount = isset($request['amount'])?$request['amount']:null; 
			
            
            
			if( ($circle || $mobile) && $operator ){
				$obj = new $this->rechargeapi;
				$post['cricle'] = $circle;
				$post['operator'] = $operator;
				$post['mobile'] = $mobile;
				$post['offer'] = $offer;
				$buffer = $obj->mplan_checkplan($post); 
				if(!is_null($buffer) && $buffer['status']){
					$response['status'] = TRUE;
					$response['data'] = $this->makelisting($buffer['records'],$offer, $apptype,$amount);
					$response['message'] = 'Result macthed!';
					if(empty($response['data'])){
						unset($response['data']);
						$response['status'] = FALSE; 
						$response['message'] = 'No plan found!';
					}
					
				}else if(!is_null($buffer) && !$buffer['status']){
					$response['status'] = FALSE; 
					$response['message'] = $buffer['records']['msg'];
				}else{
					$response['status'] = FALSE;
					$response['message'] = 'Something is not right!';
				}

			}else{
					$response['status'] = FALSE;
					$response['message'] = 'Please fill the required fields!';
				}
		}
		else{ 
			$response['status'] = FALSE;
			$response['message'] = 'Bad request!'; 
		}

   		header("Content-Type: application/json");
		echo json_encode( $response );
	}

public function makelisting($array,$offer,$apptype,$amount){
  if( $apptype == 'app' && !empty($array)){
  	  $output = array();
      if($offer == 'simple'){
		$arraykeys = array_keys($array);   
		foreach ($arraykeys as $menu){
			$push = array();
	      	foreach ($array[$menu] as $key => $value) {
	      		 $arr['talktime'] = $this->gettaktime($value['desc']);
	      		 $arr['validity'] = $value['validity'];
	      		 $arr['rs'] = $value['rs'];
	      		 $arr['desc'] = $value['desc'];
	      		 if(!$amount){
					array_push($push, $arr );
	      		 }else if($amount && ($amount==$arr['rs'])){
					array_push($push, $arr );
	      		 }
	      		 
	      	}

	      	$newarr['planname'] = $menu;
	      	$newarr['planlist'] = $push;
	      	     if(!$amount){
					array_push($output, $newarr );
	      		 }else if($amount){
	      		 	if(!empty($newarr['planlist'])){
					array_push($output, $newarr );
				    } 
	      		 }

	      	
        }

      return $output;

      }else if($offer == 'roffer'){
      	  $push = array();
          foreach ($array as $key => $value){
          	     $arr['talktime'] = $this->gettaktime($value['desc']);
	      		 $arr['validity'] = $this->getvalidyt($value['desc']);
	      		 $arr['rs'] = $value['rs'];
	      		 $arr['desc'] = $value['desc'];
	      		 if(!$amount){
					array_push($push, $arr );
	      		 }else if($amount && ($amount==$arr['rs'])){
					array_push($push, $arr );
	      		 }
          }
          return $push;
      }
  }else{
  	return $array;
  }
} 

public function gettaktime($value){
	$output = null;
	if(strpos($value,'Get Talktime of Rs.') !== false){ 
        $output = 'Rs. '.(float)explodeme($value,'Get Talktime of Rs.',1 );
    }else if(strpos($value,'Full Talktime Rs.') !== false){ 
        $output = 'Rs. '.(float)explodeme($value,'Full Talktime Rs.',1 );
    }else if(strpos($value,'Talktime Rs.') !== false){ 
        $output = 'Rs. '.(float)explodeme($value,'Talktime Rs.',1 );
    }else if(strpos($value,'Talktime of Rs.') !== false){ 
        $output = 'Rs. '.(float)explodeme($value,'Talktime of Rs.',1 );
    }else if(strpos($value,'IUC Minutes') !== false){
    $str = 'Rs. '.explodeme($value,'IUC Minutes',0 ); 
        if(strpos( $str,'Rs. Talktime') !== false){
            $output = 'Rs. '.(float)explodeme($str,'Rs. Talktime',1 );
        } 
    }else{ $output = 'NA';}
    return $output;
}

public function getvalidyt($value){
	$output = $value;
	if(strpos($value,'28 din ke liye') !== false){
		$output = "28 Days";
	}else if(strpos($value,'56 din ke liye') !== false){
		$output = "56 Days";
	}else if(strpos($value,'84 din ke liye') !== false){
		$output = "84 Days";
	} 

	return $output;
}

 
}
?>