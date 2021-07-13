<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Pancard_hold extends CI_Controller{
  var $panename; 
  var $foldername; 
  var $attemptstatus; 
  public function __construct(){ 
    parent::__construct();     
               $this->load->library('session'); 
               agsession_check();
               sessionunique();  
               $this->pagename = 'pancard_hold';
               $this->foldername = 'ag';
               $this->attemptstatus = 'hold';
    }
 

   
    public function index(){ 

        $data['title'] = 'All Hold Pan Card'; 
        $data['pagename'] = $this->pagename;
        $data['folder'] = $this->foldername;

            $apiwh['agentid'] = '';
            $apiwh['attemptstatus'] = '';
            $apiwh['transaction'] = '';
            $apiwh['filterby'] = '';
            $apiwh['requestparam'] = '';
            $apiwh['daterange'] = '' ;
            $apiwh['limit'] = '';
            $apiwh['start'] = '';
            $apiwh['orderby'] = 'DESC';



        $data['from_date'] = '';
        $data['to_date'] = '';
        $data['transaction'] = '';
        $data['filterby'] = '';
        $data['fvalue'] = '';
        $data['countItem'] = ''; 
         /********* filter script start here  *******/
        
         
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
        
         }           
                

                $apiwh['attemptstatus'] =  $this->attemptstatus;
                $apiwh['agentid'] =  $this->session->userdata('id');
                $apiwh['orderby'] = 'DESC';
                $apiwh['limit'] = '';
                $apiwh['start'] = '';


 //echo json_encode($apiwh); exit;
 
                $data['list'] = array();

                $apiurl = ADMINURL.('webapi/agent/pancard_histroy'); 
                
 
                $buffer = curlApis($apiurl,'POST', $apiwh );
                if( $buffer['status'] ){
                    $data['list'] = $buffer['data'];
                }
                
         
 
            /********* count transactions script end here *******/
 
        agview('pancard_hold',$data);
  }

          
}
?>