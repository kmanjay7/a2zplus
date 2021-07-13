<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Credit_report extends CI_Controller{
    var $panename;
    var $folder;
	public function __construct(){
        parent::__construct();
        $this->load->library('session');
        $this->load->library('pagination');     
                bpsession_check();
                sessionunique();  
                $this->pagename = 'Credit_report';
                $this->folder = 'bp';
        }



    public function index(){ 

        $data['title'] = 'Credit Report';
        $data['folder'] = $this->folder;
        $data['pagename'] = $this->pagename;

       
 $userid = getloggeduserdata('id');
 $user_type = getloggeduserdata('user_type');
        
  
/*get inputs start here */ 
 if($input = $this->input->get()){
   $startdate = $input['start_date'];
   $startdate = str_replace('/', '-', $startdate);
   $startdate = date('Y-m-d',strtotime($startdate));
   $enddate = $input['to_date'];
   $enddate = str_replace('/', '-', $enddate);
   $enddate = date('Y-m-d',strtotime($enddate));
   if(($startdate != '1970-01-01') && ($enddate != '1970-01-01') ){
    $where['DATE(add_date) >='] = $startdate;
    $where['DATE(add_date) <='] = $enddate;  
    $trwhere['DATE(a.add_date) >='] = $startdate;
    $trwhere['DATE(a.add_date) <='] = $enddate; 
   }
 }      
 /*get inputs end here */



    $urluri = ADMINURL.$this->folder.'/'.$this->pagename.'/index/?';
              
          /********* Get recent transactions script start here *******/ 
                $urllimit = $this->input->get('per_page')?$this->input->get('per_page'):1;
                $urllimit = $urllimit > 1 ? ($urllimit-1) : 0;
                $table = 'dt_wallet';
                $limit = 10; 
                $where['userid'] = $userid;
                $where['usertype'] = $user_type;
                $where['subject'] = 'borrow_debit';   
                $countItem = $this->c_model->countitem($table,$where);

                $pgarr['baseurl'] = $urluri;
                $pgarr['total'] = $countItem;
                $pgarr['limit'] = $limit;
                $pgarr['segmenturi'] =  $urllimit;
                $data["pagination"] = my_pagination($pgarr);
                 
               
            $offset = $urllimit*$limit;


            $trwhere['a.addby'] = $userid;
            $trwhere['a.subject'] = 'borrow_debit';
            $trwhere['a.usertype'] = $user_type; 

               

            $select = 'a.id,a.paymode,a.credit_debit,a.remark,a.subject,a.referenceid,a.add_date,a.beforeamount,a.amount,a.finalamount,a.usertype as dusertype, b.uniqueid as duid, b.ownername as dname , c.user_type as cusertype, c.uniqueid as cuid, c.ownername as cname '; 
             
            $from = 'dt_wallet as a'; 

            $join[0]['table'] = 'dt_users as b';
            $join[0]['joinon'] = 'b.id = a.addby' ; 
            $join[0]['jointype'] = 'LEFT'; 

            $join[1]['table'] = 'dt_users as c';
            $join[1]['joinon'] = 'c.id = a.sendto' ; 
            $join[1]['jointype'] = 'LEFT';  

 
            $groupby = null; 
            $orderby = 'a.id DESC' ; 
            $getorcount = 'get';

            $data['trans_list'] = $this->c_model->joinmultiple( $select, $trwhere, $from, $join, $groupby ,$orderby, $limit, $offset, $getorcount ); 
 
//print_r($data['trans_list']); //exit;
  
        /********* Get recent transactions script end here *********/

 		  
        bpview('credit_amount_report',$data );
        }  



}
?>