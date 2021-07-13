<?php defined('BASEPATH') OR exit('No direct script access allowed');



class Aeps_fund extends CI_Controller{
    var $pagename;
    var $folder;
	public function __construct(){
		parent::__construct();
        $this->load->library('session'); 
               $this->load->library('pagination');
               agsession_check();
               $this->pagename = 'aeps_fund';
               $this->folder = $this->uri->segment(1);
		}

     public function index(){ 
             $data = [];
             $data['title'] = 'Aeps Settlement';
             $data['folder'] = $this->folder;
             $data['pagename'] = $this->pagename;
             $aeps_status = 'yes';

             
             $data['adbank'] = '';
             $data['accountno'] = '';
             $data['adifsc'] = '';
             $data['mode'] = '';

             $data['ptype'] = 'aeps_tr_wt';
             $data['amount'] = ''; 


             if($aeps_status == 'yes'){  
             /* fetch bank name and bank list start code*/
             $bkpst['userid'] = getloggeduserdata('id');
             $bkpst['user_type'] = getloggeduserdata('user_type');
             $bkpst['aeps_enable'] = 'no';  
             $bankurl = APIURL.('webapi/banklist/bank_list'); 
             $bnkbuffer = curlApis($bankurl,'POST',$bkpst);
             $data['banklist'] = array();
             if($bnkbuffer['status'] && !empty($bnkbuffer['data'])){
                $data['banklist'] = create_dropdownfrom_array($bnkbuffer['data'],'id','bankname','Bank Name--');
             }
             /* fetch bank name and bank list end code */

             /* added bank list start code*/
             $gtbklt['user_id'] = getloggeduserdata('id');
             $gtbklt['usertype'] = getloggeduserdata('user_type');  
             $listurl = APIURL.('webapi/aeps/register_bank_list'); 
             $list_buffer = curlApis($listurl,'POST',$gtbklt);
             $data['ad_bank_list'] = array();
             if($list_buffer['status'] && !empty($list_buffer['data'])){
                $data['ad_bank_list'] = $list_buffer['data'];
             }
             /* added bank list start code*/ 
             agview('aeps_fund',$data);
            }

} 



public function geifsccode(){  
$pdata = $this->input->post()?$this->input->post():$this->input->get();
$bankid = $pdata['bankid'];
$arr = $this->c_model->getSingle('bank',array('id'=>$bankid),'id,master_ifsc');
echo isset($arr['master_ifsc'])?$arr['master_ifsc']:'';
} 


public function addbank(){
   $post = $this->input->post(); 


   $post_d['uid'] = getloggeduserdata('id');
   $post_d['utype'] = getloggeduserdata('user_type');
   $post_d['ifsccode'] = $post['adifsc'];
   $post_d['accountno'] = $post['accountno'];
   $post_d['bankid'] = $post['bankid'];
            
            $apiurl = ADMINURL.('webapi/aeps/register_aacount');  
            $upwt = curlApis($apiurl,'POST', $post_d ); 
            echo json_encode($upwt); 
}



public function checkamt(){
   $pdata = $this->input->post()?$this->input->post():$this->input->get();
   $amount = $pdata['amount'];
   $buffer['status'] = false;
   $buffer['debitamount'] = false;
            $post_url = APIURL.('webapi/aeps/check_transfer_range');  
            $newpost['amount'] = $amount; 
            $newpost['scheme_type'] = getloggeduserdata('scheme_type');
            $buffer = curlApis($post_url,'POST',$newpost );  

            if($buffer['status'] && $buffer['status']){ 
               $amount = $buffer['debitamount']; 
            }  

echo json_encode($buffer);
}



public function mydmt(){
   $post = $this->input->post(); 
   
   $type = $post['t'];
   if($type == 'wdt'){ 
        $post_d['user_id'] = getloggeduserdata('id');
        $post_d['usertype'] = getloggeduserdata('user_type');
        $post_d['uniqueid'] = getloggeduserdata('uniqueid');
        $post_d['amount'] = $post['amt']; 

        $apiurl = ADMINURL.('webapi/aeps/transfer_to_wallet');  
        $upwt = curlApis($apiurl,'POST', $post_d ); 
        echo json_encode($upwt);
   }

   else if( $type == 'bnk'){ 

        $post_d['loggedin_id'] = getloggeduserdata('id');
        $post_d['user_type'] = getloggeduserdata('user_type');
        $post_d['scheme_type'] = getloggeduserdata('scheme_type');
        $post_d['uniqueid'] = getloggeduserdata('uniqueid');
        $post_d['bankid'] = $post['bk'];
        $post_d['amount'] = $post['amt'];
        $post_d['debitamount'] = $post['amtb'];
        $post_d['paymode'] = $post['m'];
        $post_d['purpose'] = 'OTHERS'; 
        $post_d['apptype'] = 'W'; 

        $apiurl = ADMINURL.('webapi/paytm/fundtransfer_aeps/postparam');
        $header = array('auth: Access-Token='.AEPS_FUND_TXN_TOKEN );  
        $postwhere['dts'] = $post_d;  
        $upwt = curlApis($apiurl,'POST', $postwhere, $header ); 
        //$upwt['status'] = false;
        //$upwt['message'] = 'Service is Temporarily Down';
        echo json_encode($upwt);
   }
 
            
}




}?>