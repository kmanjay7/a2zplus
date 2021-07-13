<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Postpaidrecharge extends CI_Controller{
	
	public function __construct(){
		parent::__construct();
		 $this->load->library('session');   
                agsession_check();
                sessionunique(); 
                
		}
	
	public function index(){ 

		$data['operator'] = array();
		$data = array();
		$data['title'] = 'Postpaid Recharge';
		$data['folder'] = 'ag';
		$data['pagename'] = 'Postpaidrecharge';
		$serviceid = 9;



        $data['mobileno'] = '';
        $data['operatorcode'] = '';
        $data['circle'] = '';
        $data['operatorlist'] = array();


		 /*get operator start*/
		 $post['serviceid'] = $serviceid;  
		 $url = APIURL.('webapi/recharge/Getoperators');
		 $operator = curlApis($url,'POST',$post); 
		 //print_r( $operator['data'] );
		 if(isset($operator['data']) && $operator['status']){
		 	$data['operatorlist'] = create_dropdownfrom_array($operator['data'],'op_code','operator','operator--') ;
		 }  
		 /*get operator end*/  
 

        /*get recharge plan start here */
        $postdata = $this->input->get()?$this->input->get():null;
        if( isset($postdata['operator']) && !is_null($postdata)){
        $data['plans'] = $this->checkplan($postdata);
        $data['mobileno'] = $postdata['mobile'];
        $data['operatorcode'] = $postdata['operator'];
        $data['circle'] = $postdata['circle'];
        }/*get recharge plan start here */
         


        /*recent recharge 10 records start here*/
        
            $rechwhere['rech_history.serviceid'] = $serviceid; 
            $rechwhere['rech_history.user_id'] = getloggeduserdata('id');  

			$select = 'rech_history.reqid, rech_history.amount, rech_history.mobileno, operators.operator,operators.image'; 
			$from = 'rech_history';
			$jointable = 'operators';
			$joinon = 'rech_history.operatorid = operators.id';
			$jointype = 'LEFT';  

           
        $data['recentrecharge'] = $this->c_model->joindata( $select, $rechwhere, $from, $jointable, $joinon, $jointype,null,null,null,null,null,5 ); 
        /*recent recharge 10 records start here*/

        /* message container start here */
        if($postdata['error_message']){
        $this->session->set_flashdata('success',$postdata['error_message']); 
        }
        /* message container start here */

		agview('postpaid-recharge',$data);
	}







	public function getcircle(){ 
		 $post['mobileno'] = $this->input->post('mobileno');
		 $post['operatorcode'] = $this->input->post('operator'); 
		 $url = APIURL.('webapi/recharge/getcircle');
		 $buffer = curlApis($url,'POST',$post);

		  if( isset($buffer['data']['state']) && $buffer['status'] ){
		  	  $str = rtrim($buffer['data']['state'],'"');
		  	  $str = ltrim($buffer['data']['state'],'"');
		  	  echo '<option value="'.$buffer['data']['cid'].'">'.$str.'</option>'; 
		  }else{  echo '<option value="">--Select Circle--</option>'; } 
		  

	}




	public function recharge(){ 
		 $postdata = $this->input->post();
		 unset($postdata['filter']); 
		 $postdata['uniqueid'] = getloggeduserdata('uniqueid');
		 $postdata['usertype'] = getloggeduserdata('user_type'); 
		 $posturl = APIURL.('webapi/recharge/Recharge_postpaid'); 

		 $data = curlApis($posturl,'POST',$postdata,null,'100'); 
         $message = '';
         if(isset($data['status']) && !is_null($data) ){

         	if(isset($data['data']['remark']) && $data['data']['remark']){
         		$message = $data['data']['remark'];
         	}else{
         		$message = $data['message']; 
         	} 
           
         }
         //print_r( $data );
 
		 redirect( base_url('ag/Postpaidrecharge?error_message='.$message));

	}


	public function checkplan($postdata){   
		 $postdata['operatorcode'] = $postdata['operator']; 
		 $posturl = APIURL.('webapi/recharge/Checkplan');
		 $response = curlApis($posturl,'POST',$postdata);

		 $output = array(); 
		 if($response['status']){
		 	$output['records'] = $response['data']['records']; 
		 } 
         return $output; 
	}



}
?>