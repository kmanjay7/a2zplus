<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Get_face_match_percentage extends CI_Controller{
	
	 public function __construct() {
		parent::__construct();
	 }
		
	public function index() {

		header("Pragma: no-cache");
		header("Cache-Control: no-cache");
		header("Expires: 0");
			
			$response = array(); 
			    $data = array(); 
			  
	   /****  check Method  start ****/ 
	   if( ($_SERVER['REQUEST_METHOD'] != 'GET') ){ 
			$response['status'] = FALSE;
			$response['message'] = "Bad Request!"; 
			header("Content-Type:application/json");
			echo json_encode( $response );
			exit;
		}	 
		/****  check Method  start ****/ 


		$faceMatchPercentage= $this->c_model->getSingle('common_settings',['id'=>1],'*')['aadhaar_selfi_match'];

		$faceMatchPercentage=($faceMatchPercentage>0) ? $faceMatchPercentage : 60; 

        
        if($faceMatchPercentage)
        {
        	$response['status'] = true;
	        $response['data'] = ['faceMatchPercentage' => round($faceMatchPercentage)];
	        $response['message'] = 'Request was Successfull';
	        header("Content-Type:application/json");
	        echo json_encode($response);
	        exit;
        }else{
        	$response['status'] = FALSE;
            $response['message'] = "Some Technical Issue, Please try again!";
            header("Content-Type:application/json");
            echo json_encode($response);
            exit;
        }		
	}

}
?>