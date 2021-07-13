<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Add_fund_online_report extends CI_Controller{
    var $folder;
    var $pagename;
    var $serviceid;
    public function __construct(){
        parent::__construct();
        $this->load->library('session'); 
                mdsession_check();
                sessionunique();
                $this->folder = $this->uri->segment(1);  
                $this->pagename = 'add_fund_online_report';
                $this->serviceid = 7;
        }



    public function index(){ 

        $data['title'] = 'Online Fund  Report';
        $data['folder'] = $this->folder;
        $data['pagename'] = $this->pagename;
        $data['posturl'] = $this->folder.'/'.$this->pagename;  


        $data['success_amt'] = false;
        $data['total_commission'] = false;
        $data['total_surcharge'] = false;
        $data['total_tds'] = false;


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
            $apiwh['daterange'] = $data['from_date'].'|'.$data['to_date'];
        
         }           
                

 
              
            $apiurl = APIURL.('webapi/agent/online_fund_report'); 
            $wtlist = curlApis($apiurl,'POST', $apiwh ); 
            
            $data['trans_list'] = [];
            if(!empty($wtlist)){
                $data['trans_list'] = isset($wtlist['data'])?$wtlist['data']:[]; 
              
            }
        /********* count transactions script start here *******/
        $data['success_amt'] = 0;
        $data['t_comi'] = 0;
        $data['t_surcharge'] = 0;
        $data['total_tds'] = 0;


             if(!empty($data['trans_list'])){
                foreach ($data['trans_list'] as $key => $value) { 
                    if( isset($value['statushtm']) && $value['statushtm'] =='SUCCESS' ){ 
                        $data['success_amt'] += $value['amount']; 
                        $data['t_surcharge'] += $value['surcharge']; 
                     } 

                     
                }

            }
 
            /********* count transactions script end here *******/


          
       mdview('add_fund_online_report',$data ); 
           
        }  



}?>