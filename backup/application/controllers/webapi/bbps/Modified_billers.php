<?php defined("BASEPATH") OR exit("No Direct Access Allowed!");

class Modified_billers extends CI_Controller{

	public function __construct()
	{
		parent::__construct(); 
		$this->load->model("general_model"); 
		$this->load->library('bbps_recharge');
	}

	public function index()
	{

		header("Pragma: no-cache");
		header("Cache-Control: no-cache");
		header("Expires: 0");
		header("Content-Type: application/json");

		$response = array();
		$data = array();
		$request = requestJson();

		if( ($_SERVER['REQUEST_METHOD'] == 'POST'))
		{
			$biller_id=!empty($request["biller_id"])?$request["biller_id"]:false;
			
 			$data = $this->bbps_recharge->billers_info();
 			if($data["status"]=="SUCCESS")
 			{
 				$billers=$data["data"]["billers_details"];
 			}
			
			if(!empty($billers))
			{
				if($biller_id)
				{
					$biller_id=strtoupper($biller_id);
					$flag=0;
					foreach ($billers as $biller) 
					{
					    if($biller_id==$biller["biller_id"])
						{
							$flag=1;
							break;
						}
					}

					if($flag)
					{
					    //print_r($biller);exit;
					    $inputs=[];
					    foreach ($biller["bill_details_input_parameters"] as $field) 
                        {   
                            if(empty($field["values"]))
                            {
                                $input=[];
                                $input["required"]=$field["required"];
                                $input["field_name"]=$field["field_name"];
                                $input["reg_exp"]=$field["reg_exp"];
                                $input["display_value"]=$field["display_value"];
                                $inputs[]=$input;
                            }
                
                            if(!empty($field["values"]))
                            {
                                $input=[];
                                $input["required"]=$field["required"];
                                $input["field_name"]=$field["values"]["groupingKeys"][0];
                                $input["display_value"]=$field["values"]["groupingKeys"][0];
                                
                                if(!empty($field["values"]["groupingHeirarchy"]))
                                {
                                    $vals=[];
                                    foreach ($field["values"]["groupingHeirarchy"] as $key => $value) {
                                        $vals[]=["name"=>$key];
                                    }
                                    $input["values"]=$vals;
                                }
                                $inputs[]=$input;
                            } 
                            
                        }
                        $biller["bill_details_input_parameters"]=$inputs;
						$response['status']= TRUE;
						$response["data"]=$biller;
						$response['message']= 'Success!';	
					}
					else
					{
						$response['status']= FALSE;
						$response['message']= 'Invalid Biller!';	
					}
				}
				else
				{
					$response['status']= FALSE;
					$response['message']= 'Invalid Biller!';
				}
				
			}
			else
			{
				$response['status']= FALSE;
				$response['message']= 'Unable to Fetch!';
			}
		}
		else{ 
			$response['status']= FALSE;
			$response['message']= 'Bad request!'; 
		}

		echo json_encode( $response );
	}


}
?>