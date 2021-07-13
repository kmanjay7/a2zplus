<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Money_transaction_report extends CI_Controller{
    var $panename;
    var $folder;
	public function __construct(){
        parent::__construct();
        $this->load->library('session');
        $this->load->library('pagination');     
                agsession_check();
                sessionunique();  
                $this->pagename = 'money_transaction_report';
                $this->folder = 'ag';
        }



    public function index(){ 

        $data['title'] = 'Money Transaction Details';
        $data['folder'] = $this->folder;
        $data['pagename'] = $this->pagename;
        $data['posturl'] = $this->folder.'/'.$this->pagename;

        $inkey = null;
        $invalue = null;
 

        $data['from_date'] = '';
        $data['to_date'] = '';
        $data['transaction'] = '';
        $data['filterby'] = '';
        $data['fvalue'] = '';




        $where['a.userid'] = getloggeduserdata('id');
        $where['a.usertype'] = getloggeduserdata('user_type');   
        $where['DATE(a.add_date)'] = date('Y-m-d');
        $where['a.status !='] = 'REQUEST';
/********* filter script start here  *******/ 
        if( $this->input->get() ){
            $getinput = $this->input->get(); 

            $data['from_date'] = $getinput['from_date'];
            $data['to_date'] = $getinput['to_date'];
            $data['transaction'] = $getinput['transaction'];
            $data['filterby'] = $getinput['filterby'];
            $data['fvalue'] = $getinput['fvalue'];


             if($getinput['from_date'] && $getinput['to_date'] ){ 
                unset($where['DATE(a.add_date)']);
                $where['DATE(a.add_date) >='] = date('Y-m-d',strtotime($data['from_date'])); 
                $where['DATE(a.add_date) <='] = date('Y-m-d',strtotime($data['to_date'])); 
             }

             

             if( $getinput['transaction'] == 'success' ){
                unset($where['a.status !=']); 
                $where['a.status'] = 'SUCCESS';

             }else if( $getinput['transaction'] == 'failed' ){
                unset($where['a.status !=']); 
                $where['a.status'] = 'FAILURE';

             }else if( $getinput['transaction'] == 'pending' ){
                unset($where['a.status !=']);  
                $inkey = 'a.status';
                $invalue = 'PENDING,PROCESSED'; 
             }else if( $getinput['transaction'] == 'imps' ){ 
                $where['a.mode'] = 'IMPS';

             }else if( $getinput['transaction'] == 'neft' ){ 
                $where['a.mode'] = 'NEFT';

             }else if( $getinput['transaction'] == 'dmt1' ){ 
                $where['a.apiname'] = 'paytm';

             }else if( $getinput['transaction'] == 'dmt2' ){ 
                $where['a.apiname'] = '';

             }
 



             if( $getinput['filterby']=='orderid' && $getinput['fvalue'] ){ 
                $where['a.orderid'] = $getinput['fvalue'];
             
             }else if( $getinput['filterby']=='txnid' && $getinput['fvalue'] ){ 
                $where['a.ptm_rrn'] = $getinput['fvalue'];

             }else if( $getinput['filterby']=='mob' && $getinput['fvalue'] ){ 
                $where['dt_sender.mobile'] = $getinput['fvalue'];

             }else if( $getinput['filterby']=='ac' && $getinput['fvalue'] ){ 
                $where['b.ac_number'] = $getinput['fvalue'];

             }  


        }

/********* filter script start here  *******/  

   
               

            $select = 'a.sys_orderid,a.id,dt_sender.name as s_name, dt_sender.mobile as s_mobile, b.name as b_name, b.ac_number, a.mode, a.apiname, a.orderid, a.amount, c.bankname, a.add_date, a.status, a.sur_charge, a.ag_comi, a.ag_tds, a.banktxnid,a.operatorname,a.ptm_rrn, a.status_update, e.sch_name,d.uniqueid as agent_uniqueid,a.usertype,d.ownername,dt_sender.kyc_status,a.complaint '; 
            $from = 'dt_dmtlog as a'; 
            $join[0]['table'] = 'dt_sender';
            $join[0]['joinon'] = 'a.sender_id = dt_sender.id';
            $join[0]['jointype'] = 'LEFT';

            $join[1]['table'] = 'dt_benificiary as b';
            $join[1]['joinon'] = 'b.id = a.benifi_id' ; 
            $join[1]['jointype'] = 'LEFT';

            $join[2]['table'] = 'dt_bank as c';
            $join[2]['joinon'] = 'c.id = b.bankname' ;  
            $join[2]['jointype'] = 'LEFT';

            $join[3]['table'] = 'dt_users as d';
            $join[3]['joinon'] = 'd.id = a.userid' ; 
            $join[3]['jointype'] = 'LEFT';

            $join[4]['table'] = 'dt_scheme as e';
            $join[4]['joinon'] = 'd.scheme_type = e.id' ; 
            $join[4]['jointype'] = 'LEFT'; 
 
            $groupby = null; 
            $orderby = 'a.id DESC' ; 
            $getorcount = 'get';
            $limit = null; 
            $offset = null;

            $data['trans_list'] = $this->c_model->joinmultiple( $select, $where, $from, $join, $groupby ,$orderby, $limit, $offset, $getorcount, $inkey, $invalue  );
 

 //echo '<pre/>'; print_r($data['trans_list']);
        /********* Get recent transactions script end here *********/

//exit; 

        /********* count transactions script start here *******/
        $data['success_amt'] = 0;
        $data['t_comi'] = 0;
        $data['t_surcharge'] = 0;
        $data['total_tds'] = 0;


            if(!empty($data['trans_list'])){
                foreach ($data['trans_list'] as $key => $lvalue) {
                     if($lvalue['status']=='SUCCESS'){ 
                        $data['success_amt'] += $lvalue['amount'];
                        $data['t_comi'] += ($lvalue['ag_comi'] + $lvalue['ag_tds']);
                        $data['t_surcharge'] += $lvalue['sur_charge'];
                        $data['total_tds'] += $lvalue['ag_tds'];
                     } 

                     
                }

            }
 
            /********* count transactions script end here *******/

		  
        agview('money_transaction_report',$data );
        }  



}
?>