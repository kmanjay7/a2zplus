<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Dthrecharge extends CI_Controller{
	
	public function __construct(){
		parent::__construct();
		$this->load->library('session');   
                agsession_check();
                sessionunique();  
		}
	

	public function index(){ 

		$data['operator'] = array();
		$data = array();
		$data['title'] = 'DTH Recharge';
		$data['folder'] = 'ag';
		$data['pagename'] = 'Dthrecharge';
		$serviceid = 3;
		$data['opstatus'] = false;



        $data['customercv'] = '';
        $data['operator'] = '';
        $data['circle'] = '';
        $data['operatorlist'] = array();


		 /*get operator start*/
		 $post['serviceid'] = $serviceid;  
		 $url = APIURL.('webapi/recharge/Getoperators');
		 $operator = curlApis($url,'POST',$post);  
		 if(isset($operator['data']) && $operator['status']){
		 	$data['operatorlist'] = create_dropdownfrom_array($operator['data'],'operator','operator','operator--') ;
		 }  
		 /*get operator end*/   
 

    /*get recharge plan start here */
    $postdata = $this->input->get()?$this->input->get():null;
    $data['customercv'] = isset($postdata['customercv'])?$postdata['customercv']:'';
    $data['operator'] = isset($postdata['operator'])?$postdata['operator']:''; 
    /*get recharge plan start here */


        /*fetch operator start*/
        if($data['customercv']){
		 $post_fetch['customercv'] = $data['customercv'];  
		 $url = APIURL.('webapi/recharge/Check_dth_operator');
		 $operator_arra = curlApis($url,'POST',$post_fetch); 
		 $data['opstatus'] = false; 
		 if(isset($operator_arra['data']) && $operator_arra['status']){ 
		 	$data['operator'] = $operator_arra['data']['Operator'];
		 }else if(!$operator_arra['status']){ $data['opstatus'] = true; }
		}
		/*fetch operator end*/ 
         


        /*recent recharge 10 records start here*/  
		 $search['user_id'] = getloggeduserdata('id');
		 $search['serviceid'] = $serviceid;
		 $search['filterby'] = '';
		 $search['requestparam'] = '';
		 $search['limit'] = 10;
		 $search['start'] = '';
		 $search['orderby'] = "DESC"; 

		 $searchurl = APIURL.('webapi/recharge/Recharge_histroy');
		 $searchlist = curlApis( $searchurl,'POST',$search );
		 $data['trans_list'] = array();
		 if($searchlist['status']){
			$data['trans_list'] = $searchlist['data'];
		 }   
        /*recent recharge 10 records start here*/

        /* message container start here */
        if(isset($postdata['error_message']) && $postdata['error_message']){
        $this->session->set_flashdata('success',$postdata['error_message']); 
        }
        /* message container start here */

		agview('dth-recharge',$data);
	}





	public function recharge(){ 
		 $postdata = $this->input->post();
		 unset($postdata['filter']); 
		 $postdata['uniqueid'] = getloggeduserdata('uniqueid');
		 $postdata['usertype'] = getloggeduserdata('user_type'); 
		 $posturl = APIURL.('webapi/recharge/Rechargedth'); 

		 $data = curlApis($posturl,'POST',$postdata,null,'100'); 
         $message = '';
         if(isset($data['status']) && !is_null($data) ){

         	if(isset($data['data']['remark']) && $data['data']['remark']){
         		//$message = $data['data']['remark'];
         		$message = 'Request Accepted';
         		
         	}else{
         		//$message = $data['message']; 
         		$message = 'Request Accepted';
         	} 
           
         }

         echo json_encode($data);
         //print_r( $data );
 
		 

	}


	public function customer_info(){  
	     $postdata = $this->input->post();
		 $postdata['offer'] = 'roffer'; 
		 $posturl = APIURL.('webapi/recharge/Check_dth_customer_info'); 
		 $response = curlApis($posturl,'POST',$postdata);

		 $output = array(); 
		 if($response['status'] && isset($response['data']['records']) ){
		 	$output = $response['data']['records']; 
		 } 
 		 
 		 $data['details'] = $output; 
         $this->load->view('ag/ajax/dth_customer_details', $data );
	}


   public function heavyrefresh(){  
	     $postdata = $this->input->post();
		 $postdata['offer'] = 'roffer'; 
		 $posturl = APIURL.('webapi/recharge/Check_dth_heavy_refresh'); 
		 $response = curlApis($posturl,'POST',$postdata); 
		 $output = array(); 
		 if($response['status'] && isset($response['data']) ){
		 	$output = $response['data']; 
		 }  
 		 $data['details'] = $output; 
         $this->load->view('ag/ajax/dth_heavy_refresh', $data );
	}

	public function plan_offer(){  
	     $postdata = $this->input->post()?$this->input->post():$this->input->get();
		 $postdata['offer'] = $postdata['type']; 
		 $posturl = APIURL.('webapi/recharge/Check_dth_offer'); 
		 $response = curlApis($posturl,'POST',$postdata);  
		 
		 $output = array(); 
		 if($response['status'] && isset($response['data']['Plan']) ){
		 	$output = $response['data']['Plan']; 
		 }  
 
 		 $data['plans'] = $output; 
         $this->load->view('ag/ajax/dth_special_offer', $data );
	}


}
?>