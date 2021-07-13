<?php
defined("BASEPATH") OR exit("No Direct Access Allowed!");

class Check_balance extends CI_Controller
{
    
    public function __construct()
    {
        parent::__construct();
     
    }
    
    public function index()
    {
        header("Pragma: no-cache");
        header("Cache-Control: no-cache");
        header("Expires: 0");
        
        $response = array();
    
        if (($_SERVER['REQUEST_METHOD'] == 'POST')) 
        {

            $datas = array( 
                'username'    => DIGIUPAY_USERNAME,
            );
        

        $header =['accept'=>'application/json','Apitoken'=>DIGIUPAY_APITOKEN,'cache-control'=>'no-cache'];

        $a2z_response =curlApis(DIGIUPAY_URL.'api-balance','POST',$datas,$header,50); 

            

            if(isset($a2z_response["Response"]))
            {
                $response['status']=TRUE;
                $response['balance']= (isset($a2z_response["currentBalance"])) ? $a2z_response["currentBalance"] : 0;
                $response["message"]=$a2z_response["Message"];
            }
            else
            {
                $response['status']=False;
                $response["message"]=$a2z_response["Message"];
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