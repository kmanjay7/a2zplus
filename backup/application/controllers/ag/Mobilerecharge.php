<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Mobilerecharge extends CI_Controller{
	var $panename;
    var $folder;
	public function __construct(){
		parent::__construct(); 
		 $this->load->library('session');  
                agsession_check();
                sessionunique();
                $this->pagename = 'Mobilerecharge';
                $this->folder = 'ag';  
		}
	
	public function index(){ 

		$data['operator'] = array();
		$data = array();
		$data['title'] = 'Mobile-recharge';
		$data['folder'] = $this->folder ;
		$data['pagename'] = $this->pagename ;
		$serviceid = 5;



        $data['mobileno'] = '';
        $data['operatorcode'] = '';
        $data['circle'] = '';
        $data['operatorlist'] = array();


		 /*get operator start*/
		 $post['serviceid'] = $serviceid;  
		 $url = APIURL.('webapi/recharge/Getoperators');
		 $operator = curlApis($url,'POST',$post);  
		 //print_r($operator);
		 if(isset($operator['data']) && $operator['status']){
		 	$data['operatorlist'] = create_dropdownfrom_array($operator['data'],'operator','operator','operator--');
		 }  
		 /*get operator end*/  
 

        /*get recharge plan start here */
        $postdata = $this->input->get()?$this->input->get():null; 
        if( isset($postdata['operator']) && !is_null($postdata)){ 
        $data['mobileno'] = $postdata['mobile'];
        $data['operatorcode'] = $postdata['operator'];
        $data['circle'] = $postdata['circle'];
        }
        /*get recharge plan start here */
         


				$urluri = ADMINURL.$this->folder.'/'.$this->pagename.'/index/?';
				/********* Get recent transactions script start here *******/ 
                $urllimit = $this->input->get('per_page')?$this->input->get('per_page'):1;
                $urllimit = $urllimit > 1 ? ($urllimit-1) : 0;
                $table = 'dt_rech_history';
                $limit = 10;
                $where['user_id'] = getloggeduserdata('id');
                $where['serviceid'] = $serviceid;    
                $countItem = 0;//$this->c_model->countitem($table,$where);
                $pgarr['baseurl'] = $urluri;
                $pgarr['total'] = $countItem;
                $pgarr['limit'] = $limit;
                $pgarr['segmenturi'] =  $urllimit;
                $data["pagination"] = '';//my_pagination($pgarr); 
               
                $offset = $urllimit*$limit;

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
 

		agview('mobile-recharge',$data);
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
		 $postdata['uniqueid'] = getloggeduserdata('uniqueid');
		 $postdata['usertype'] = getloggeduserdata('user_type'); 
		 $postdata['apptype'] = 'W';
		 $posturl = APIURL.('webapi/recharge/Rechargemobile'); 
		 $data['status'] = false;
		 $data['Something went wrong!'] = false;

		 $data = curlApis($posturl,'POST',$postdata,null, 100 );  

         if(isset($data['status']) && !is_null($data) ){

         	if(isset($data['data']['remark']) && $data['data']['remark']){
         		$data['message'] = 'Request Accepted';
         	} 
           
         }

         echo json_encode($data);
         //print_r( $data ); 

	}


	public function fetch_plan(){    
		 $postdata = $this->input->post()?$this->input->post():$this->input->get();
		 $posturl = APIURL.('webapi/recharge/Fetch_mobile_plan');
		 $response = curlApis($posturl,'POST',$postdata);

		 $output = array(); 
		 if(isset($response['status']) && isset($response['data'])){
		 	$output['records'] = $response['data'];  
		 } 
 
		 $data['plans'] = $output;
		 $data['type'] = $postdata['type']; 
         $this->load->view('ag/ajax/mobile_plan_list', $data ); 
	}


	public function check_operator(){  
	     $postdata = $this->input->post()?$this->input->post():$this->input->get(); 
		 $pdata['mobileno'] = $postdata['mobileno']; 
		 $posturl = APIURL.('webapi/recharge/Check_mobile_operator'); 
		 $response = curlApis($posturl,'POST',$pdata,null,60); 
		 
		 if($response['status']){
		 	$response['data']; 
		 } 
         
         echo json_encode($response);
	}



}
?>