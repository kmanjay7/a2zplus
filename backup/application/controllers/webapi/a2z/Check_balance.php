<?php
defined("BASEPATH") OR exit("No Direct Access Allowed!");

class Check_balance extends CI_Controller
{
    
    public function __construct()
    {
        parent::__construct();
        $this->load->library('bbps_recharge');
    }
    
    public function index()
    {
        header("Pragma: no-cache");
        header("Cache-Control: no-cache");
        header("Expires: 0");
        // header("Content-Type: application/json");
        
        $request  = requestJson();
        $response = array();
    
        if (($_SERVER['REQUEST_METHOD'] == 'POST')) 
        {
            $bbps_response=$this->bbps_recharge->balance();

            if(isset($bbps_response["code"]) && $bbps_response["code"]==200)
            {
                $response['status']=TRUE;
                $response['balance']= $bbps_response["balance"];
                $response["message"]=$bbps_response["message"];
            }
            else
            {
                $response['status']=False;
                $response["message"]=$bbps_response["message"];
            }
        } 
        else 
        {
            $response['status']  = FALSE;
            $response['message'] = 'Bad request!';
        }
        
        echo json_encode($response);
        
    }
    
    
    
}
?>