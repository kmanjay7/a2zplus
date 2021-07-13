<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Domestic_money_transfer extends CI_Controller{
	public function __construct(){
        parent::__construct();
        $this->load->library('session'); 
                agsession_check();
                sessionunique();  
        }


    public function index(){ 

        $data['title'] = 'Domestic Money Transfer';
        $data['folder'] = 'ag';
        $data['pagename'] = 'Dthrecharge';

        if($this->session->userdata('dmtusers')){
             $this->session->unset_userdata('dmtusers');
        }

        




        /********* Get recent transactions script start here *******/
                $serviceid = '6'; 
                $table = 'dt_dmtlog';
                $limit = 5;
                $where['userid'] = getloggeduserdata('id');
                $where['usertype'] = getloggeduserdata('user_type'); 
                $where['status !='] = 'REQUEST';
                $base_url = ADMINURL.'ag/Domestic_money_transfer/index/';
                $countItem = $this->c_model->countitem($table,$where);
                $data["pagination"] = pagination($base_url, $countItem, $limit);
                $urllimit = $this->uri->segment(4)?$this->uri->segment(4):0;
                $offset = $urllimit;

            $trwhere['dt_dmtlog.userid'] = getloggeduserdata('id'); 
            $trwhere['dt_dmtlog.usertype'] = getloggeduserdata('user_type'); 
            $trwhere['dt_dmtlog.status !='] = 'REQUEST';   

            $select = 'dt_dmtlog.id,dt_dmtlog.sys_orderid,dt_sender.name as s_name, dt_sender.mobile as s_mobile, dt_benificiary.name as b_name, dt_benificiary.ac_number, dt_dmtlog.mode, dt_dmtlog.apiname, dt_dmtlog.orderid, dt_dmtlog.amount, dt_bank.bankname, dt_dmtlog.add_date, dt_dmtlog.status, dt_dmtlog.sur_charge, dt_dmtlog.ag_comi, dt_dmtlog.ag_tds, dt_dmtlog.banktxnid,dt_dmtlog.operatorname,dt_dmtlog.mode, dt_dmtlog.ptm_rrn, dt_dmtlog.status_update, dt_scheme.sch_name,dt_users.uniqueid as agent_uniqueid,dt_dmtlog.usertype,dt_users.ownername,dt_sender.kyc_status,dt_dmtlog.complaint'; 
            $from = 'dt_dmtlog';
            $join[0]['table'] = 'dt_sender';
            $join[0]['joinon'] = 'dt_dmtlog.sender_id = dt_sender.id';
            $join[0]['jointype'] = 'LEFT';

            $join[1]['table'] = 'dt_benificiary';
            $join[1]['joinon'] = 'dt_benificiary.id = dt_dmtlog.benifi_id' ; 
            $join[1]['jointype'] = 'LEFT';

            $join[2]['table'] = 'dt_bank';
            $join[2]['joinon'] = 'dt_bank.id = dt_benificiary.bankname' ;  
            $join[2]['jointype'] = 'LEFT';

            $join[3]['table'] = 'dt_users';
            $join[3]['joinon'] = 'dt_users.id = dt_dmtlog.userid' ; 
            $join[3]['jointype'] = 'LEFT';

            $join[4]['table'] = 'dt_scheme';
            $join[4]['joinon'] = 'dt_users.scheme_type = dt_scheme.id' ; 
            $join[4]['jointype'] = 'LEFT'; 

            $groupby = null;//'dt_dmtlog.sys_orderid' ;
            $orderby = 'dt_dmtlog.id DESC' ; 
            $getorcount = 'get';

            $data['recentdisbarsul'] = $this->c_model->joinmultiple( $select, $trwhere, $from, $join, $groupby ,$orderby, $limit, $offset, $getorcount );

 
 //echo '<pre/>'; print_r($data['recentdisbarsul']);
        /********* Get recent transactions script end here *********/

//exit;

		  
        agview('domestic-money-transfer',$data );
        }  



}