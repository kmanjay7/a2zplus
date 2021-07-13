<?php defined("BASEPATH") OR exit("No Direct Access Allowed!");

class Bill_fetch extends CI_Controller{

	private $serviceid;
	private $apiid=7;
	
	public function __construct()
	{
		parent::__construct(); 
		$this->load->library('bbps_recharge');
		$this->load->model("general_model");
	}
		

	public function index()
	{
		header("Pragma: no-cache");
		header("Cache-Control: no-cache");
		header("Expires: 0");
		// header("Content-Type: application/json");	

		$response = array();
		$data = array();
		$request = requestJson();

		if( ($_SERVER['REQUEST_METHOD'] == 'POST') )
		{
			$this->serviceid = !empty($request['serviceid'])?$request['serviceid']:'';
			$operator_code = !empty($request['operator'])?$request['operator']:'';
			$cust_mobile_no = !empty($request['mobile_no'])?$request['mobile_no']:'';
			$longitude = !empty($request['longitude'])?$request['longitude']:'';
			$latitude = !empty($request['latitude'])?$request['latitude']:'';
			$uniqueid = !empty($request['uniqueid'])?$request['uniqueid']:'';
			$usertype = !empty($request['usertype'])?$request['usertype']:'';

			if( $operator_code && $uniqueid && $usertype && $cust_mobile_no && $longitude && $latitude)
			{
				unset($request['usertype']);
				unset($request['uniqueid']);
				unset($request['latitude']);
				unset($request['longitude']);
				unset($request['mobile_no']);
				unset($request['operator']);
				unset($request['serviceid']);

	        	$user=$this->general_model->getSingle("users", ["uniqueid"=>$uniqueid, "user_type"=>$usertype], "id, uniqueid, pincode, scheme_type");
		        if(isset($user["id"]))
		        {
		        	$operator_code=$this->general_model->getSingle("operators_code", ["serviceid"=>$this->serviceid, "UPPER(op_code)"=>strtoupper($operator_code)], "operatorid, op_code");
		        	
	            	if(empty($operator_code))
	            	{
	            		$response['status'] = FALSE;
	                    $response['message'] = 'No API set for this payment!';
	                    echo json_encode($response);
	                    exit;
	            	}
	        
					$arr["ref_id"]="ELC".date("YmdHis").$user["id"].rand(100, 999);
					$arr["bill_details"]=$request;
					$arr["biller_details"]["biller_id"]=strtoupper($operator_code["op_code"]);
					$arr["additional_info"]["agent_id"]=BBPS_NPCI_AGENT_ID;
					$arr["additional_info"]["initiating_channel"]="AGT";
					$arr["additional_info"]["terminal_id"]=$user["id"];
					$arr["additional_info"]["mobile"]=$user["uniqueid"];
					$arr["additional_info"]["geocode"]="$longitude, $latitude";
					$arr["additional_info"]["postal_code"]=$user["pincode"];
					$arr["additional_info"]["customer_mobile"]=$cust_mobile_no;
					$arr["additional_info"]["bbpsAgentId"]=BBPS_AGENT_ID;
					$arr["additional_info"]["si_txn"]="Yes";
					// echo json_encode($arr); 
					// exit;
					$bbps_response=$this->bbps_recharge->bill_fetch($arr);
					if($bbps_response["status"]=="SUCCESS")
					{
						$response['status']= TRUE;
						$response['data']= $bbps_response["data"];
						$response["message"]="Bill found of INR. ".$bbps_response["data"]["bill_details"]["amount"];
					}
					else
					{
						$response['status']= FALSE;
						$response['message']= $bbps_response["message"];
					}

				}
			}
			else
			{
				$response['status']= FALSE;
				$response['message']= 'Please fill the required fields!';
			}
		}
		else
		{ 
			$response['status']= FALSE;
			$response['message']= 'Bad request!'; 
		}

		echo json_encode( $response );
	}

}
?>