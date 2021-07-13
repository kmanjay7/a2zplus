<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller{
	
	public function __construct(){
		parent::__construct(); 
		$this->load->library('session');
                bpsession_check();
                sessionunique();
                $this->load->model("general_model");
		}



public function checkTranStatus($type){

        $where['userid'] = getloggeduserdata('id');
        $where['usertype'] = getloggeduserdata('user_type'); 
        $where['status'] = 'SUCCESS';
        $key = 'id';

        $output = false;  
        if($type == 'total'){
              
        }else if($type == 'today'){
              $where['DATE(add_date)'] = date('Y-m-d');
        }else if($type == 'thismonth'){
			  $where['DATE_FORMAT(add_date,"%Y-%m")'] = date('Y-m'); 
        }else if($type == 'thisyear'){
            $where['DATE_FORMAT(add_date,"%Y")'] = date('Y');
        }

        $output =  $this->c_model->countitem('dt_dmtlog',$where ); 
        return $output;
 }  



	
	public function index(){ 
		
		$data = array();
		$data['title'] = 'Dashboard Page';

		$data['folder'] = 'bp';
        $data['pagename'] = 'Dashboard';
		

		$data['total_transaction'] = $this->checkTranStatus('total');
		$data['total_bars'] = $data['total_transaction'];

		$data['todays_transaction'] = $this->checkTranStatus('today');
		$data['todays_bars'] = $data['todays_transaction'];

		$data['months_transaction'] = $this->checkTranStatus('thismonth');
		$data['months_bars'] = $data['months_transaction'];

		$data['years_transaction'] = $this->checkTranStatus('thisyear');
		$data['years_bars'] = $data['years_transaction'];




 /********* Get recent transactions script start here *******/
                $serviceid = '6'; 
                $table = 'dt_dmtlog';
                $limit = 5;
                $where['userid'] = getloggeduserdata('id');
                $where['usertype'] = getloggeduserdata('user_type'); 
                $where['status !='] = 'REQUEST';
                $base_url = ADMINURL.'ag/dashboard/index/';
                $countItem = $this->c_model->countitem($table,$where);
                $data["pagination"] = pagination($base_url, $countItem, $limit);
                $urllimit = $this->uri->segment(4)?$this->uri->segment(4):0;
                $offset = $urllimit;

            $trwhere['dt_dmtlog.userid'] = getloggeduserdata('id'); 
            $trwhere['dt_dmtlog.usertype'] = getloggeduserdata('user_type'); 
            $trwhere['dt_dmtlog.status'] = 'SUCCESS';   

            $select = 'dt_dmtlog.apiname, dt_dmtlog.operatorname, dt_benificiary.ac_number, dt_benificiary.ifsc_code,dt_sender.mobile, dt_wallet.beforeamount, dt_wallet.amount, dt_wallet.finalamount, dt_dmtlog.status, dt_dmtlog.banktxnid,dt_dmtlog.add_date, dt_wallet.subject,dt_dmtlog.sys_orderid,dt_dmtlog.ptm_rrn '; 

			$from = 'dt_dmtlog';

            $jointable = 'dt_wallet' ;
            $joinon = 'dt_wallet.referenceid = dt_dmtlog.sys_orderid' ;  
            $jointype = 'inner';
  

            $jointable3 = 'dt_benificiary' ;
            $joinon3 = 'dt_benificiary.id = dt_dmtlog.benifi_id' ;  
            $jointype3 = 'LEFT';

            $jointable4 = 'dt_sender';
            $joinon4 = 'dt_dmtlog.sender_id = dt_sender.id';
            $jointype4 = 'LEFT'; 

            $groupby = null;//'dt_dmtlog.sys_orderid' ;
            $orderby = 'dt_dmtlog.id DESC' ; 
            $getorcount = 'get';

           
          $data['recentdisbarsul'] = $this->c_model->joindata( $select, $trwhere, $from, $jointable, $joinon, $jointype, $groupby, $orderby, $jointable3, $joinon3, $jointype3, $limit, $offset, $getorcount, $jointable4, $joinon4, $jointype4  );

            $where="(`usertype` like '".$this->session->userdata("user_type")."' OR usertype='') AND ";
            $where.="((CONCAT(`from_date`,' ', `from_time`) <='".date("Y-m-d H:i:s")."' AND CONCAT(`end_date`,' ', `end_time`)>='".date("Y-m-d H:i:s")."') OR (`from_date` is NULL AND `end_date` is NULL))";
            $data["notifications"]=$this->general_model->getAll("notification", $where, "", "id desc");
            
            $where="(`usertype` like '".$this->session->userdata("user_type")."' OR usertype='') AND ";
            $where.="((CONCAT(`from_date`,' ', `from_time`) <='".date("Y-m-d H:i:s")."' AND CONCAT(`end_date`,' ', `end_time`)>='".date("Y-m-d H:i:s")."') OR (`from_date` is NULL AND `end_date` is NULL))";
            $data["banner"]=$this->general_model->getAll("banner", $where, "", "id desc");

		bpview('dashboard',$data);
	}
}
?>