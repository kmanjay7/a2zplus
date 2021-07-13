<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller{
	
	public function __construct(){
		parent::__construct();
		$this->load->library('session');  
                agsession_check();
                sessionunique();
                $this->load->model("general_model");

		}



	
	public function index(){ 
		$data = array();
		$data['title'] = 'Dashboard Panel | Agent';

		$data['folder'] = 'ag';
        $data['pagename'] = 'dashboard';
		

	



 /********* Get recent transactions script start here *******/


  /*recent recharge 10 records start here*/  
         $search['user_id'] = getloggeduserdata('id');
         $search['usertype'] = getloggeduserdata('user_type');
         $search['filterby'] = '';
         $search['requestparam'] = '';
         $search['limit'] = 5;
         $search['start'] = '';
         $search['orderby'] = "DESC"; 


         $searchurl = APIURL.('webapi/wallet/Wallet_history');
         $searchlist = curlApis( $searchurl,'POST',$search );
         $data['trans_list'] = array();
         if($searchlist['status']){
            $data['trans_list'] = $searchlist['data'];
         }
         
         $where="(`usertype` like '".$this->session->userdata("user_type")."' OR usertype='') AND ";
            $where.="((CONCAT(`from_date`,' ', `from_time`) <='".date("Y-m-d H:i:s")."' AND CONCAT(`end_date`,' ', `end_time`)>='".date("Y-m-d H:i:s")."') OR (`from_date` is NULL AND `end_date` is NULL))";
            $data["notifications"]=$this->general_model->getAll("notification", $where, "", "id desc");
            
        $where="(`usertype` like '".$this->session->userdata("user_type")."' OR usertype='') AND ";
            $where.="((CONCAT(`from_date`,' ', `from_time`) <='".date("Y-m-d H:i:s")."' AND CONCAT(`end_date`,' ', `end_time`)>='".date("Y-m-d H:i:s")."') OR (`from_date` is NULL AND `end_date` is NULL))";
            $data["banner"]=$this->general_model->getAll("banner", $where, "", "id desc");
            
            
    //   echo  $this->db->last_query();
    //     die();
        /*recent recharge 10 records start here*/

            

//echo '<pre>';
//print_r($data['trans_list']);


//exit;

		agview('dashboard',$data);
	}
}
?>