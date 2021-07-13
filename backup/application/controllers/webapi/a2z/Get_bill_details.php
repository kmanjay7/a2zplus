<?php defined("BASEPATH") OR exit("No Direct Access Allowed!");

class Get_bill_details extends CI_Controller{

	private $serviceid;
	private $apiid = 14;
	
	public function __construct()
	{
		parent::__construct(); 
		$this->load->library('bbps_recharge');
		$this->load->model("general_model");
		$this->load->library('curl');
	}

	


	public function index()
	{
		header("Pragma: no-cache");
		header("Cache-Control: no-cache");
		header("Expires: 0");
		header("Content-Type: application/json");

		$response = array();
		$data = array();
			if( ($_SERVER['REQUEST_METHOD'] == 'POST') )
			{
			    
				if(!empty($this->input->post('operator')) && !empty($this->input->post('customerid')))
				{
		        	
		        	$data = array(
			            'api_token' => 'eVHGnU59YQEo1Rvwa7cNyF9pTRNeGQQ5Lbi0EWQHW5uso4rnKLkttCKfky6w',
			            'provider' => $this->input->post('operator'),
			            'number' => $this->input->post('customerid'),
			            'userId'    => '12816',
			            'secretKey' => '12816yvRbT7rBYqwPAUQqVdNb0P4xbs8cVBY6S95PqDSVdC7bcihRyEkFxqoCh2cM',
			        );
			        
			        $apires = $this->curl->simple_post('https://partners.a2zsuvidhaa.com/api/v3/fetch/bill-details',$data);
		        	if(!empty($apires))
		        	{
		        		$response['status']= TRUE;
		        		$response['message']= "Bill details fetched successfully";
		        		$response['bill_details']=$apires;
		        		$response['customerid']=$this->input->post('customerid');
		        		$response['operator']=$this->input->post('customerid');					
					}else
					{
						$response['status']= FALSE;
						$response['message']= "Some error occured, Please try again.";
					}
				}else
				{
					$response['status']= FALSE;
					$response['message']= 'Please fill the required fields!';
				}
			}else
			{ 
				$response['status']= FALSE;
				$response['message']= 'Bad request!'; 
			}

		echo json_encode( $response );
	}
}
?>