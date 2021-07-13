<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Aeps_settlement_report extends CI_Controller{
    var $panename;
    var $folder;
	public function __construct(){
        parent::__construct();
        $this->load->library('session');   
                agsession_check();
                sessionunique();  
                $this->pagename = 'Aeps_settlement_report';
                $this->folder = 'ag';
        }



   
    public function index(){ 

        $data['title'] = 'Aeps Settlement Report ';
        $data['folder'] = $this->folder;
        $data['pagename'] = $this->pagename;  


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
                

 
             

            $apiurl = APIURL.('webapi/aeps/transaction_history_dmtaeps'); 
            $wtlist = curlApis($apiurl,'POST', $apiwh ); 
            
            $data['list'] = []; 
            if($wtlist['status']){
                $data['list'] = $wtlist['data'];  
            }
        /********* Get recent transactions script end here *********/
 
		  
       agview('aeps_settlement_report',$data );
        }  


}
?>