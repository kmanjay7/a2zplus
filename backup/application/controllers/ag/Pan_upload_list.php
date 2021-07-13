<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Pan_upload_list extends CI_Controller{
    var $panename;
    var $folder;
	public function __construct(){
        parent::__construct();
        $this->load->library('session');
        $this->load->library('pagination');     
                agsession_check();
                sessionunique();  
                $this->pagename = 'Pan_upload_list';
                $this->folder = 'ag';
        }


    public function index(){ 

        $data['title'] = 'Upload Pending'; 
        $data['pagename'] = $this->pagename;
        $data['folder'] = $this->folder;
        $attemptstatus = 'temp';

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
        
         }        

            $apiwh['attemptstatus'] =  $attemptstatus; 
            $apiwh['agentid'] =  $this->session->userdata('id');


            $apiwh['orderby'] = 'DESC';
            $apiwh['limit'] = null;
            $apiwh['start'] = null; 
                

 //echo json_encode($apiwh); exit;
 
                $data['list'] = array();

                $apiurl = ADMINURL.('webapi/agent/pancard_histroy'); 
                
 
                $buffer = curlApis($apiurl,'POST', $apiwh );
                if( $buffer['status'] ){
                    $data['list'] = $buffer['data'];
                }
                
            

 


           /********* count transactions script start here *******/
            $data['p_total'] = 0;
            $data['p_today'] = 0;
            $data['p_new'] = 0;
            $data['p_correct'] = 0; 


            if(!empty($data['list'])){
                foreach ($data['list'] as $key => $lvalue) {
                     $data['p_total'] += 1; 
                     if($lvalue['category']=='new'){
                        $data['p_new'] += 1; 
                     }else if($lvalue['category']=='correction'){
                        $data['p_correct'] += 1; 
                     }else if($lvalue['fill_date']==date('d-M-Y')){
                        $data['p_today'] += 1;
                     }
                }

            }
 
            /********* count transactions script end here *******/

		  
        agview('pan_upload_list',$data );
        }  



}
?>