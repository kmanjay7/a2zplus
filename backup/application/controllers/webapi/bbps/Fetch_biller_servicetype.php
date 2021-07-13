<?php defined("BASEPATH") OR exit("No Direct Access Allowed!");

class Fetch_biller_servicetype extends CI_Controller{

	public function __construct()
	{
		parent::__construct(); 
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

		if( ($_SERVER['REQUEST_METHOD'] == 'POST'))
		{
 			$biller_servicetypes = $this->general_model->getAll('biller_servicetype',["bbps_billerid"=>$request["billerid"]]);
			if(!empty($biller_servicetypes))
			{
				$response['status']= TRUE;
				$response['data'] = $biller_servicetypes;
				$response['message']= 'Success!';
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