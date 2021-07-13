<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Kyc_nonkyc_users extends CI_Controller{
    var $foldername;
	public function __construct(){
        parent::__construct(); 
        $this->load->library('session'); 
                agsession_check();
                sessionunique(); 
                $this->foldername = 'ag'; 
        }


    public function index(){ 
		 
		$data['title'] = 'Domestic Money Transfer';
        $data['folder'] = $this->foldername;
        $data['pagename'] = 'Kyc_nonkyc_users';
        $countItem = '';
       

        if(!$this->session->userdata('dmtusers')){
            $gotourl = base_url($this->foldername.'/Domestic_money_transfer');
            redirect( $gotourl );
        } 

        $dataarray = $this->session->userdata('dmtusers');
        $dmtusers = $dataarray['data'];
        $data['dmtusers'] = $dmtusers;
        
        //print_r($data['dmtusers']);


        /* benificiary list start */
        $limit = 5; 
        $urllimit = $this->uri->segment(4)?$this->uri->segment(4):0;
        $data['urisegment'] = $urllimit;
        $url = APIURL.('webapi/dmtusers/Benificiary_list'); 
        $get['sender_id'] = $dmtusers['senderid'];
        $get['filterby'] = '';
        $get['requestparam'] = '';
        $get['limit'] = $limit;
        $get['start'] = $urllimit/$limit;
        $get['orderby'] = 'DESC'; 
        $benifilist = curlApis($url,'POST',$get); 
        $data['benilist'] = NULL;
        $countItem = $benifilist['count'];
        if($benifilist['status']){
        $data['benilist'] = $benifilist['data']; 
        }  

                
        $base_url = (ADMINURL.$this->foldername.'/Kyc_nonkyc_users/index/'); 
        $data["pagination"] = pagination($base_url, $countItem, $limit);

        /* benificiary list start */ 


        /* get fund transfer benoficiary details start*/
        $data['t_bankid'] = '';
        $data['t_beni_id'] = '';
        $data['t_ifsc'] = '';
        $data['t_acc_number'] = '';
        $data['t_beni_name'] = '';
        $data['t_vefify'] = '';
        $data['fund_div_view'] = 'no';
        if($this->input->get('utm')){
        $countkeys = count($data['benilist']); 
        $data['fund_div_view'] = 'yes';
        $utmkey = $this->input->get('utm')?( $this->input->get('utm') - 1 ):null;
        $utmkey = $countkeys == 1 ? 0 : $utmkey;
        $transferbenifi = $data['benilist'][$utmkey]; 
        $data['t_bankid'] = $transferbenifi['bankid'];
        $data['t_ifsc'] = $transferbenifi['ifsc_code'];
        $data['t_acc_number'] = $transferbenifi['ac_number'];
        $data['t_beni_name'] = $transferbenifi['name'];
        $data['t_vefify'] = $transferbenifi['acc_verification'];
        $data['t_beni_id'] = $transferbenifi['id']; 
        }
        /* get fund transfer benoficiary details end*/


        /* bank list start */ 
        $bank_url = APIURL.('webapi/banklist/Bank_list');  
        $banklist = curlApis($bank_url,'GET'); 
        $data['banklist'] = array();
        if($banklist['status']){
        $data['banklist'] = create_dropdownfrom_array($banklist['data'],'id','bankname','Bank Name--');
        }   
        /* bank list start */


        /********* Get recent transactions script start here *******/
             $senderarray = $this->session->userdata('dmtusers');
             $sender_id = $senderarray['data']['senderid'];  

                  
                $table2 = 'dmtlog';
                $where['userid'] = getloggeduserdata('id');
                $where['sender_id'] = $sender_id;
                $rows2 = $this->c_model->countitem($table2,$where); 
                
                $limit2 = 5; 
                $per_page = ($this->input->get('per_page') ? $this->input->get('per_page') : 0);
                $serviceid = '6';  
                $arr['baseurl'] = ADMINURL.$this->foldername.'/Kyc_nonkyc_users/index/'.$urllimit.'/';    
                $arr['total'] = $rows2;
                $arr['limit'] = $limit2;
                $arr['segmenturi'] = 5;
                $data["pagination2"] = my_pagination($arr);
                $start2 = $per_page ? ($per_page -1 )*$limit2: 0 ;
 
                

 

            $trwhere['dt_dmtlog.userid'] = getloggeduserdata('id'); 
            $trwhere['dt_dmtlog.usertype'] = getloggeduserdata('user_type');  
            $trwhere['dt_dmtlog.sender_id'] = $sender_id;  
            $trwhere['dt_dmtlog.status !='] = 'REQUEST';  

            $select = 'dt_dmtlog.id,dt_sender.name as s_name, dt_sender.mobile as s_mobile, dt_benificiary.name as b_name, dt_benificiary.ac_number, dt_dmtlog.mode, dt_dmtlog.apiname, dt_dmtlog.orderid, dt_dmtlog.amount, dt_bank.bankname, dt_dmtlog.add_date, dt_dmtlog.status, dt_dmtlog.sur_charge, dt_dmtlog.ag_comi, dt_dmtlog.ag_tds,  dt_benificiary.ifsc_code,dt_dmtlog.sys_orderid,dt_dmtlog.respmsg,dt_dmtlog.ptm_rrn,dt_dmtlog.operatorname, dt_dmtlog.status_update, dt_scheme.sch_name,dt_users.uniqueid as agent_uniqueid,dt_dmtlog.usertype,dt_users.ownername,dt_sender.kyc_status,dt_dmtlog.complaint '; 
            $from = 'dt_dmtlog';

            $join[0]['table'] = 'dt_sender';
            $join[0]['joinon'] = 'dt_dmtlog.sender_id = dt_sender.id';
            $join[0]['jointype'] = 'LEFT';

            $join[1]['table'] = 'dt_benificiary';
            $join[1]['joinon'] = 'dt_benificiary.id = dt_dmtlog.benifi_id' ; 
            $join[1]['jointype'] = 'LEFT';

            $join[2]['table'] = 'dt_bank';
            $join[2]['joinon'] = 'dt_bank.id = dt_benificiary.bankname' ;  
            $join[2]['jointype'] = 'LEFT';

            $join[3]['table'] = 'dt_users';
            $join[3]['joinon'] = 'dt_users.id = dt_dmtlog.userid' ; 
            $join[3]['jointype'] = 'LEFT';

            $join[4]['table'] = 'dt_scheme';
            $join[4]['joinon'] = 'dt_users.scheme_type = dt_scheme.id' ; 
            $join[4]['jointype'] = 'LEFT'; 

            $groupby = null;//'sys_orderid' ; 
            $orderby = 'id DESC' ; 
            $getorcount = 'get';

           
       $data['recentdisbarsul'] = $this->c_model->joinmultiple( $select, $trwhere, $from, $join, $groupby ,$orderby, $limit2, $start2, $getorcount ); 
 
        /********* Get recent transactions script end here *********/


        /* get sender monthly limit start */ 
        $limitbuffer = checksenderLimit( $dmtusers['senderid'] );  
        $data['totallimit'] = 25000;
        $data['availablelimit'] = 25000; 

        if( $limitbuffer['status'] ){
        $data['totallimit'] = $limitbuffer['total_limit'];
        $data['availablelimit'] = $limitbuffer['available_limit']; 
        }  
        /* get sender monthly limit start */



        agview('kyc_nonkyc_users',$data );
   }



   public function dmtuserlogout(){ 
      $this->session->unset_userdata('dmtusers');
       $gotourl = base_url($this->foldername.'/Domestic_money_transfer');
       redirect( $gotourl );
   }


   public function saveupdate(){
             $userid = $this->session->userdata('id');
             $senderarray = $this->session->userdata('dmtusers');
             $sender_id = $senderarray['data']['senderid'];

             $pdata = $this->input->post()?$this->input->post():$this->input->get();
             $saveup['add_by'] = $userid;
             $saveup['name'] = $pdata['benif_name'];
             
             $saveup['mobile'] = '';
             $saveup['bankname'] = $pdata['bank_name']; 
             $saveup['ac_number'] = $pdata['account_number'];
             $saveup['ifsc_code'] = $pdata['ifsc_code'];  
             $saveup['sender_id'] = $sender_id; 
             $saveup['acc_verification'] = $pdata['ac_verify']; 

             $url = APIURL.('webapi/dmtusers/Register_benificiary'); 
             $buffer = curlApis($url,'POST',$saveup);           
             echo json_encode($buffer);  
        }


public function deleterecord(){  
       $where['md5(id)'] = $this->input->get('dLid');
       $update['status'] = 'no';
       $delete = $this->c_model->saveupdate('dt_benificiary', $update, null, $where);
       $this->session->set_flashdata('success','Benificiary Deleted successfully!' );
       $gotourl = base_url($this->foldername.'/Kyc_nonkyc_users');
       redirect( $gotourl );
} 


public function accountdetails(){ 
        $buffer['status'] = false;
        $buffer['name'] = '';

        $wallet = checkwallet();
        $wallet = round($wallet);

        $add_by = $this->session->userdata('id');
        $senderarray = $this->session->userdata('dmtusers');
        $sender_id = $senderarray['data']['senderid'];

        $pdata = $this->input->post()?$this->input->post():$this->input->get();

        $get['loginuser_id'] = $add_by;
        $get['sender_id'] =  $sender_id;
        $get['benif_id'] = $pdata['benif_id'];
        $get['accountno'] = $pdata['accountno'];
        $get['ifsc_code'] = $pdata['ifsc_code']; 
        $newpost = array('dts'=>$get ); 
        
        if($wallet >= 4 ){
            $post_url = APIURL.('webapi/paytm/Accountverification/ac_check'); 
            $header = array('Auth: Access-Token='.WALLETOKEN ); 
            $buffer = curlApis($post_url,'POST',$newpost, $header);  

            if($buffer['status'] && $buffer['status']){ 
                $buffer['name'] = $buffer['data']['benename']; 
                $buffer['ac_verify'] = 'yes'; 
            }  

        }else{
             $buffer['status'] = false; 
             $buffer['message'] = 'wallet balance is low for this transaction'; 
        } 

        echo json_encode($buffer);
         
}

public function checkamt(){
   $pdata = $this->input->post()?$this->input->post():$this->input->get();
   $amount = $pdata['amount'];
   $buffer['status'] = false;
   $buffer['debitamount'] = false;
            $post_url = APIURL.('webapi/dmtusers/Check_transfer_range');  
            $newpost['amount'] = $amount; 
            $newpost['scheme_type'] = getloggeduserdata('scheme_type');
            $buffer = curlApis($post_url,'POST',$newpost );  

            if($buffer['status'] && $buffer['status']){ 
               $amount = $buffer['debitamount']; 
            }  

echo json_encode($buffer);
}

public function dmttransfer(){ 
        $buffer['status'] = false; 

        $wallet = checkwallet();
        $wallet = round($wallet);

        $add_by = $this->session->userdata('id');
        $senderarray = $this->session->userdata('dmtusers');
        $sender_id = $senderarray['data']['senderid'];
        $user_type = $this->session->userdata('user_type');

        $pdata = $this->input->post()?$this->input->post():$this->input->get();

        $get['loggedin_id'] = $add_by;
        $get['user_type'] = $user_type;
        $get['scheme_type'] = getloggeduserdata('scheme_type');
        $get['sender_id'] = $sender_id;
        $get['benif_id'] = $pdata['ben_id'];
        $get['account_number'] = $pdata['ac']; 
        $get['ifsc_code'] = $pdata['ifsc']; 
        $get['purpose'] = 'OTHERS'; 
        $get['amount'] = $pdata['amount'];
        $get['debitamount'] = $pdata['damount']; 
        $get['paymode'] = $pdata['pmode']; 
        $get['bankid'] = $pdata['bankname']; 

        $newpost = array('dts'=>$get ); 
        //echo json_encode($newpost);  
        if( $pdata['damount'] >= $pdata['amount'] ){
            $post_url = APIURL.('webapi/paytm/Fundtransfer/paytmpost'); 
            $header = array('Auth: Access-Token='.DIGI_FUND_TXN_TOKEN ); 
            $buffer = curlApis($post_url,'POST',$newpost, $header);  
            //echo json_encode($newpost); 
            if(isset($buffer['status']) && $buffer['status']){ 
                $buffer['status'] = true; 
            }  

        }else{
             $buffer['status'] = false; 
             $buffer['message'] = 'wallet balance is low for this transaction'; 
        } 

        echo json_encode($buffer);
         
}


public function geifsccode(){  

        $pdata = $this->input->post()?$this->input->post():$this->input->get();
        $bankid = $pdata['bankid'];

        $arr = $this->c_model->getSingle('bank',array('id'=>$bankid),'id,master_ifsc');
        echo isset($arr['master_ifsc'])?$arr['master_ifsc']:'';

       } 


public function benifi_old_record(){ 
        $output['status'] = false; 
        $output['name'] = '';
        $output['ifsccode'] = '';
        $pdata = $this->input->post()?$this->input->post():$this->input->get(); 
        
        $get['bankid'] = $pdata['bankid'];
        $get['accountno'] = $pdata['accountno']; 

         
            $post_url = APIURL.('webapi/dmtusers/Get_benificiary_olddata');  
            $buffer = curlApis($post_url,'POST',$get);  

            if($buffer['status'] && $buffer['status']){ 
                $output['status'] = true;  
                $output['name'] = $buffer['data']['name']; 
                $output['ifsccode'] = $buffer['data']['name']; 
                $output['ac_verify'] = $buffer['data']['ac_verify'];  
            } 

        echo json_encode($output);
         
}



}
?>