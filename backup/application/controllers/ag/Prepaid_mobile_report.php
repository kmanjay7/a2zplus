<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Prepaid_mobile_report extends CI_Controller{
    var $panename;
    var $folder;
    var $serviceid;
	public function __construct(){
        parent::__construct();
        $this->load->library('session');
        $this->load->library('pagination');     
                agsession_check();
                sessionunique();  
                $this->pagename = 'prepaid_mobile_report';
                $this->folder = 'ag';
                $this->serviceid = 5;
        }



    public function index(){ 

        $data['title'] = 'Mobile Recharge Report';
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

        

        $where['a.user_id'] = getloggeduserdata('id');  
        $where['DATE(a.add_date)'] = date('Y-m-d');
        $where['a.status !='] = 'REQUEST';
        $where['a.serviceid'] = $this->serviceid;  

 
          
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

             }  


             if( $getinput['filterby']=='orderid' && $getinput['fvalue'] ){ 
                $where['a.reqid'] = $getinput['fvalue'];
             
             }else if( $getinput['filterby']=='txnid' && $getinput['fvalue'] ){ 
                $where['a.op_transaction_id'] = $getinput['fvalue'];

             }else if( $getinput['filterby']=='mob' && $getinput['fvalue'] ){ 
                $where['a.mobileno'] = $getinput['fvalue'];

             }   


        }

/********* filter script start here  *******/ 
              
               

            $select = 'a.reqid, a.status, a.amount, a.mobileno, a.field2,a.apirefid,a.op_transaction_id, a.operatorname, a.add_date, a.ag_comi, a.ag_tds, a.status_update, b.image, a.operatorname,a.status,a.status_update,a.mobileno ,a.complaint,a.id' ;

            $from = 'dt_rech_history as a'; 

            $join[0]['table'] = 'dt_operators as b';
            $join[0]['joinon'] = 'a.operatorid = b.id';
            $join[0]['jointype'] = 'LEFT'; 
 
            $groupby = null; 
            $orderby = 'a.id DESC' ; 
            $getorcount = 'get';
            $limit = null; 
            $offset = null;

            $data['trans_list'] = $this->c_model->joinmultiple( $select, $where, $from, $join, $groupby ,$orderby, $limit, $offset, $getorcount, $inkey, $invalue );
 

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
                       //$data['t_surcharge'] = $lvalue['sur_charge'];
                        $data['total_tds'] += $lvalue['ag_tds'];
                     } 

                     
                }

            }
 
            /********* count transactions script end here *******/
		  
        agview('prepaid_mobile_recharge_report',$data );
        }  



}
?>