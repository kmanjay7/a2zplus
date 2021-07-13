<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Wallet_ladger_report extends CI_Controller{
    var $panename;
    var $folder;
	public function __construct(){
        parent::__construct();
        $this->load->library('session');   
                $this->pagename = 'Wallet_ladger_report';
                $this->folder = $this->uri->segment(1); 
                if($this->folder=='ad'){
                adsession_check(); 
                }else if($this->folder=='md'){
                mdsession_check(); 
                }else if($this->folder=='bp'){
                bpsession_check(); 
                }
        }


    public function index(){ 

        $data['title'] = 'Wallet Ledger Report';
        $data['folder'] = $this->folder;
        $data['pagename'] = $this->pagename; 



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
                

 
              
            $apiurl = APIURL.('webapi/wallet/Wallet_history_parent'); 
            $wtlist = curlApis($apiurl,'POST', $apiwh ); 
            //echo '<pre>';
           //print_r($wtlist); exit;
            $data['walletlist'] = []; 
            if($wtlist['status']){
                $data['walletlist'] = $wtlist['data']; 
                $data['success_amt'] = twodecimal($wtlist['t_successfull']);
                $data['total_commission'] = twodecimal($wtlist['t_comission']);
                $data['total_surcharge'] = twodecimal($wtlist['t_surcharge']);
                $data['total_tds'] = twodecimal($wtlist['t_tds']);
            }
        /********* Get recent transactions script end here *********/
 
         
            if($this->folder=='ad'){
            adview('wallet_ladger_report',$data );
            }else if($this->folder=='md'){
            mdview('wallet_ladger_report',$data );
            }else if($this->folder=='bp'){
            bpview('wallet_ladger_report',$data );
            } 
        
        }   


}
?>