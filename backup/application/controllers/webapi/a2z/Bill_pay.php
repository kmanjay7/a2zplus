<?php defined("BASEPATH") OR exit("No Direct Access Allowed!");

class Bill_pay extends CI_Controller{

	private $serviceid;
	private $apiid = 14;
	
	public function __construct()
	{
		parent::__construct(); 
		$this->load->library('bbps_recharge');
		$this->load->model("general_model");
		$this->load->library('curl');
	}



private function get_bal($uid,$utype){
		$where['userid'] = $uid;
		$where['usertype'] = $utype; 
		$where['status !='] = 'failed'; 
		$inkey = 'credit_debit';
		$invalue = 'credit,debit';
		$res = $this->c_model->getSingle('wallet' ,$where, 'id,finalamount','id DESC', 1, $inkey, $invalue );
		$amt = !empty($res)?$res['finalamount']: 0;
		return $amt;
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
		    
			$this->serviceid = !empty($request['serviceid'])?$request['serviceid']:"";
			$operator_code = !empty($request['operator'])?$request['operator']:""; 
			$cust_mobile_no = !empty($request['mobile_no'])?$request['mobile_no']:""; 
			$longitude = !empty($request['longitude'])?$request['longitude']:""; 
			$latitude = !empty($request['latitude'])?$request['latitude']:""; 
			$uniqueid = !empty($request['uniqueid'])?$request['uniqueid']:"";
			$usertype = !empty($request['usertype'])?$request['usertype']:"";
			$amount = !empty($request['amount'])?$request['amount']:"0";
			$userid = !empty($request['userid'])?$request['userid']:"";
			$customerid = !empty($request['customerid'])?$request['customerid']:"";
			$billno = !empty($request['billno'])?$request['billno']:"";
			$consumername = !empty($request['consumername'])?$request['consumername']:"";
			$duedate = !empty($request['duedate'])?$request['duedate']:"";
			$knumber = !empty($request['number'])?$request['number']:"";
			//echo '<pre>'; print_r($request); exit;

			if( $operator_code && $uniqueid && $usertype && $cust_mobile_no && $userid)
			{
	        	$user=$this->general_model->getSingle("users", ["uniqueid"=>$uniqueid, "user_type"=>$usertype, "id"=>$userid], "id, uniqueid, pincode, scheme_type");
	            
	        	if(!empty($user["id"]))
	        	{
	        
	        		$scheme = $user['scheme_type'];
	                /*check processing request start*/
	                $wherepending['mobileno'] = $cust_mobile_no;
	                $wherepending['status'] = 'PROCESSED';
	                $countpending = $this->c_model->getcolumnRech('dt_rech_history', $wherepending, 'id');
	                
	                if ($countpending) 
	                {
	                    $response['status'] = false;
	                    $response['message'] = 'Transaction Already Pending...';
	                    echo json_encode($response);
	                    exit;
	                }
	        		/*check wallet balance start here */ 
	                $wallet = $this->get_bal($user['id'],$usertype);
	                /*check wallet balance end here */

	                if ($wallet >= $amount) 
	                {
	                    
	                	$operator_code=$this->general_model->getSingle("operators_code", ["serviceid"=>$this->serviceid, "UPPER(op_code)"=>strtoupper($operator_code)], "operatorid, op_code");
	                	if(empty($operator_code))
	                	{
	        				$response['status'] = FALSE;
	                        $response['message'] = 'No API set for this payment!';
	                        echo json_encode($response);
	                        exit;
	                	}

	                	$refid_prefix="";
	                	if($this->serviceid==4) $refid_prefix="ELC";
	                	elseif($this->serviceid==9) $refid_prefix="MOB";
	                	elseif($this->serviceid==11) $refid_prefix="GAS";
	                	elseif($this->serviceid==12) $refid_prefix="FTG";
	                	elseif($this->serviceid==13) $refid_prefix="LON";
	                	elseif($this->serviceid==10) $refid_prefix="WAT";
	               // 	if($this->serviceid==14) $refid_prefix="A2ZELC";
    	               if($this->serviceid==14) $refid_prefix="ELC";

	                	$ref_id=$refid_prefix.date("YmdHis").$user["id"].rand(100, 999);
	                	
	                	

	                	$operator = $this->c_model->getSingle('operators', ["id"=>$operator_code['operatorid']], 'id,operator,currentapiid');
						/*get Comission For Agent start code*/
                        // $apiid = $operator['currentapiid'];
                        $comi_Arr = $this->get_commision($amount, $user['scheme_type'], $this->apiid, $operator["id"]);
                        
                        /*Note If database configured with apiid in tabel set comission remove false status form above function */
                        $ag_comi = isset($comi_Arr['ag_comi']) ? $comi_Arr['ag_comi'] : false;
                        $ag_tds = isset($comi_Arr['ag_tds']) ? $comi_Arr['ag_tds'] : false;
                        $finalAmount = ((float)$amount - (float)$ag_comi);
                                                 
                        
                        /*get Comission For Agent start code*/

                        $history['user_id'] = $user['id'];
                        $history['apiid'] = $operator['currentapiid'];
                        $history['serviceid'] = $this->serviceid;
                        $history['reqid'] = $ref_id;
                        $history['status'] = "PROCESSED";
                        $history['remark'] = "-";
                        $history['balaftrech'] = '';
                        $history['amount'] = $amount;
                        $history['mobileno'] = $cust_mobile_no;
                        $history['ec'] = '';
                        $history['apirefid'] = '';
                        $history['operatorid'] = $operator['id'];
                        $history['add_date'] = date('Y-m-d H:i:s');
                        $history['operatorname'] = $operator["operator"];
                        $history['ag_comi'] = $ag_comi;
                        $history['ag_tds'] = $ag_tds;
                        $history['cust_account_no'] = $customerid;
                        $history['billno'] = $billno;
                        $history['cons_name'] = $consumername;
                        $history['duedate'] = date('Y-m-d',strtotime($duedate));  
                        $history['nextupdate'] = date('Y-m-d H:i:s',strtotime(date('Y-m-d H:i:s').' +31 seconds'));
				        $history['noofround'] = 0;

				        
                        $dupl_entry = [];
                        $dupl_entry['reqid'] = $ref_id;
                        

                        $insertid="";
                        if ($operator_code['operatorid'] && $operator_code['op_code']) 
                        {
                            $insertid = $this->c_model->saveupdate('rech_history', $history, $dupl_entry);
                            if (!$insertid) {
                                $response['status'] = FALSE;
                                $response['message'] = 'Some Error Occured!';
                                echo json_encode($response);
                                exit;
                            }
                        }

                        /* check wallet for this transaction start */ 
				        $wtsave['userid'] = $user['id'];
				        $wtsave['usertype'] = $usertype;
				        $wtsave['uniqueid'] = $uniqueid;
				        $wtsave['paymode'] = 'wallet';
				        $wtsave['transctionid'] = $insertid;
				        $wtsave['credit_debit'] = 'debit';
				        $wtsave['upiid'] = NULL;
				        $wtsave['bankname'] = NULL; 
				        $wtsave['remark'] = $this->getservicename().' Bill'; 
				        $wtsave['status'] = 'success'; 
				        $wtsave['amount'] = (float)($finalAmount); 
				        $wtsave['subject'] = 'bbps';
				        $wtsave['addby'] = $user['id']; 
				        $wtsave['orderid'] = $ref_id;
				        $wtsave['odr'] = $amount;
				        $wtsave['comi'] = $ag_comi;
				        $wtsave['tds'] = $ag_tds;
				        $wtsave['flag'] = 1;
				        

				        $wallet_apiurl = base_url('webapi/wallet/Creditdebit');
				        $upwtwhere['dts'] = $wtsave;  
				        $header = array('auth: Access-Token='.WALLETOKEN );  
				        $upwt = $finalAmount ? curlApis($wallet_apiurl,'POST', $upwtwhere,$header ):array('status'=>false);

				        if(!$upwt['status']){
				            $response['status']= FALSE;
				            $response['message']= $upwt['message'];
				            echo json_encode( $response );
				            exit; 
				        }

				
				
						$arr["api_token"]='eVHGnU59YQEo1Rvwa7cNyF9pTRNeGQQ5Lbi0EWQHW5uso4rnKLkttCKfky6w';
						$arr["userId"]='12816';
						$arr["secretKey"]='12816yvRbT7rBYqwPAUQqVdNb0P4xbs8cVBY6S95PqDSVdC7bcihRyEkFxqoCh2cM';
						$arr["number"]=$knumber;
						$arr["provider"]=$operator_code['op_code'];
						$arr["amount"]=$amount;
						$arr["clientId"]=$ref_id;
						$arr["billerName"]=$consumername;
						$arr["bill_due_date"]=$duedate;
						
						
						$post_data = json_encode($arr, JSON_UNESCAPED_SLASHES);
						
						
						$log = $this->pushlog($ref_id,'rech','I',$post_data);
						
						
				        $responses = $this->curl->simple_post('https://partners.a2zsuvidhaa.com/api/v3/bill-recharge',$arr);
				        
				        // $response = $responses;
						$bbps_response = json_decode($responses);
						

						$log = $this->pushlog($ref_id,'rech','O',$responses);

						if(!empty($bbps_response->statusId) && in_array($bbps_response->statusId, [24,3,1]))
						{		


								if($bbps_response->statusId==3){
									$bbps_response->status = 'PROCESSED';
								}

								if($bbps_response->statusId==24){
									$bbps_response->status = 'SUCCESS';
								}
							$this->general_model->update("rech_history", ["status"=>$bbps_response->status,"op_transaction_id"=>$bbps_response->txnId], ["id"=>$insertid]);
							$response['status'] = TRUE;
							$response["paid_amount"] = $amount;
							$response['message']= 'Request Accepted!';
							$response['data'] = $ref_id ;
							$response['statusCheck'] = $bbps_response->status ;
						}
						else
						{
							
							$response['status']= FALSE;
							$response['message']= $bbps_response->message;//$msg;
							$response['data'] = $ref_id ;
						}
					}
					else
					{
						$response['status'] = FALSE;
	                    $response['message'] = 'Wallet balance is low for this transaction!';
					}
				}
				else
				{
					$response['status']= FALSE;
					$response['message']= "Invalid User";
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



private function get_commision($amount = false, $scheme = false, $apiid = false, $opid = false) 
	{
	        $table = 'dt_operators';
	        $commision = false;
	        $serviceid = $this->serviceid;
	        $output['commision'] = false;
	        $comwhere['user_type'] = 'AGENT';
	        $comwhere['operatorid'] = $opid;
	        if ($scheme) {
	            $comwhere['scheme_type'] = $scheme;
	        }
	        if ($apiid) {
	            //$comwhere['apiid'] = $apiid;
	        }
	        $comwhere['serviceid'] = $serviceid;
	        $commssionarray = $this->c_model->getSingle('dt_set_commission', $comwhere, 'commision_fixed, commision_percent');
	        
	        if (!is_null($commssionarray) && !empty($commssionarray)) {
	            if ($commssionarray['commision_fixed'] > 0) {
	                $commision = (float)$commssionarray['commision_fixed'];
	            } else {
	                $percent = percentage($amount, $commssionarray['commision_percent']);
	                $commision = (float)$percent;
	            }
	        }
	        /*get TDS */
	        $tds = (float)percentage($commision, TDS);
	        $output['commision'] = $commision;
	        $output['ag_comi'] = (float)($commision - $tds);
	        $output['ag_tds'] = $tds;
	        return $output;
	}

private function getservicename(){
	    $serviceid = $this->serviceid;
	    $refid_prefix = '';
		if($serviceid==4) $refid_prefix="Electricity";
		elseif($serviceid==9) $refid_prefix="Postpaid";
		elseif($serviceid==11) $refid_prefix="Gas";
		elseif($serviceid==12) $refid_prefix="FASTag";
		elseif($serviceid==13) $refid_prefix="Loan";
		elseif($serviceid==10) $refid_prefix="Water";
// 		elseif($serviceid==14) $refid_prefix="A2Z Electricity";
        elseif($serviceid==14) $refid_prefix="Electricity";
return $refid_prefix;
}	


public function pushlog($odr,$type,$io,$payload){
	$insert = [];
	$insert['odr'] = $odr;
	$insert['type'] = $type;
	$insert['io'] = $io;
	$insert['req_res'] = $payload;
	$insert['timeon'] = date('Y-m-d H:i:s'); 
	return $this->c_model->saveupdate('dt_pushlog',$insert );
}

}
?>