<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Aeps_transaction_report extends CI_Controller{
    var $panename;
    var $folder;
	public function __construct(){
        parent::__construct();
        $this->load->library('session');
        $this->load->library('pagination');     
                agsession_check();
                sessionunique();  
                $this->pagename = 'Aeps_transaction_report';
                $this->folder = 'ag';
        }






    public function index(){
        
        $data['title'] = 'AEPS Transaction Details';
        $data['folder'] = $this->folder;
        $data['pagename'] = $this->pagename;  

        $data['success_amt'] = 0;
        $data['total_commission'] = 0;
        $data['total_surcharge'] = 0;
        $data['total_tds'] = 0;



            $apiwh['user_id'] = getloggeduserdata('id');
            $apiwh['usertype'] = getloggeduserdata('user_type');
            $apiwh['transaction'] = '';
            $apiwh['filterby'] = '';
            $apiwh['requestparam'] = '';
            $apiwh['daterange'] = date('Y-m-d').'|'.date('Y-m-d');
            $apiwh['limit'] = '';
            $apiwh['start'] = '';
            $apiwh['orderby'] = 'DESC';



        $data['from_date'] = '';
        $data['to_date'] = '';
        $data['transaction'] = '';
        $data['filterby'] = '';
        $data['fvalue'] = ''; 
         /********* filter script start here  *******/
        $urluri = ADMINURL.$this->folder.'/'.$this->pagename.'/index/?';
         
         if($this->input->get()){
            $input = $this->input->get();
            $data['from_date'] = $input['from_date'];
            $data['to_date'] = $input['to_date'];
            $data['transaction'] = $input['transaction'];
            $data['filterby'] = $input['filterby'];
            $data['fvalue'] = $input['fvalue'];  

            $apiwh['transaction'] =  $data['transaction'];
            $apiwh['filterby'] = $data['filterby'];
            $apiwh['requestparam'] = $data['fvalue'];
            if($data['from_date'] && $data['to_date']){
                $apiwh['daterange'] = $data['from_date'].'|'.$data['to_date'];
            }
         //echo json_encode($apiwh); exit;
         }           
                

 
             

            $apiurl = APIURL.('webapi/aeps/transaction_history'); 
            $wtlist = curlApis($apiurl,'POST', $apiwh ); 
            
            $data['list'] = []; 
            if($wtlist['status']){
                $data['list'] = $wtlist['data']; 
                $data['success_amt'] = $wtlist['s_total']; 
                $data['total_commission'] = $wtlist['s_total_comi']; 
                $data['total_surcharge'] = $wtlist['s_total_surch']; 
                $data['total_tds'] = $wtlist['s_total_tds']; 
            } 

        /********* Get recent transactions script end here *********/
		  
        agview('aeps_transaction_report',$data );
        }  


}
?>