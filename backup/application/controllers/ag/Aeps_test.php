<?php defined('BASEPATH') OR exit('No direct script access allowed');



class Aeps_test extends CI_Controller{
    var $pagename;
    var $folder;
    public function __construct(){
        parent::__construct();
        $this->load->library('session'); 
               agsession_check();
               $this->pagename = 'Aeps_test';
               $this->folder = $this->uri->segment(1);
        }

     public function index(){ 
             $data = [];

             /*check aeps registration start */ 
        $where['id'] = getloggeduserdata('id');
        $where['user_type'] = getloggeduserdata('user_type');
        $profileData = $this->c_model->getSingle( 'dt_users', $where, 'devicename,aeps_status,outlet_id,aepspan_no' );

        $outlet_id   = $profileData['outlet_id'];
        $aeps_status = $profileData['aeps_status'];
        $devicename  = $profileData['devicename'];
        $data['devicename'] = $devicename?$devicename:'Device Setting';

        
        $data['device_port'] = false;
        if($this->session->userdata('device_port')){
          $data['device_port'] = $this->session->userdata('device_port');
        }

        


             if(!$outlet_id && empty($aeps_status) ){
                redirect( ADMINURL.$this->folder.'/'.$this->pagename.'/registerotp');
             }else if( $outlet_id && empty($aeps_status) ){
                redirect( ADMINURL.$this->folder.'/'.$this->pagename.'/kycuploads');
             }else if( $outlet_id && ($aeps_status == 'reject') ){
                redirect( ADMINURL.$this->folder.'/'.$this->pagename.'/chmob');
             }else if( $outlet_id && ($aeps_status == 'doc') ){
                redirect( ADMINURL.$this->folder.'/'.$this->pagename.'/kycuploads');
             }
             /*check aeps registration start */


             if($aeps_status == 'pending'){

              $data['title'] = 'Aeps - Status';
              $data['text'] = 'Your AEPS activation is in Proccess';
              $data['textcolor'] = '#000';

             $pdocs['userid'] = getloggeduserdata('id'); 
             $pdocs['user_type'] = getloggeduserdata('user_type'); 
             $pdocs['uniqueid'] = getloggeduserdata('uniqueid'); 
             $pdocs['outletid'] = $profileData['outlet_id'];
             $pdocs['pan_no'] = $profileData['aepspan_no'];; 

             $dcsurl = APIURL.('webapi/aeps/require_docs');
             $dcbuffer = curlApis($dcsurl,'POST',$pdocs);

                 if( $dcbuffer['status'] && isset($dcbuffer['data']['statuscode']) && $dcbuffer['data']['statuscode']=='TXN' ){
                     $bfdata = $dcbuffer['data']['data']; 
                     if($bfdata['SCREENING']){
                        $data['text'] = 'Your AEPS activation is in Proccess.';
                        $data['textcolor'] = '#000';
                     }else if($bfdata['REQUIRED']){
                        $data['text'] = 'Your AEPS activation has been marked as REJECTED.';
                        $data['textcolor'] = '#ff003b';
                        redirect(ADMINURL.'ag/aeps');
                     }

                 } 

              agview('aeps_pending',$data);
             }else if($aeps_status == 'no'){
              $data['title'] = 'Aeps - Status';
              $data['text'] = 'Your AEPS Service is Blocked';
              $data['textcolor'] = '#000';
              agview('aeps_pending',$data);
             }else if( ($aeps_status == 'yes') && empty($devicename) ){
                  $data['title'] = 'Device Setting';
                  $data['devicename'] = $devicename;
                  $data['posturl'] = ADMINURL.'ag/aeps/dsetting_save'; 
                  agview('aeps_device_setting',$data);  
             }else if($aeps_status == 'yes'){ 

             $data['title'] = 'Aeps - ICICI Bank';

             /* fetch bank iin number and bank list start code*/
             $bkpst['userid'] = getloggeduserdata('id');
             $bkpst['user_type'] = getloggeduserdata('user_type');
             $bkpst['aeps_enable'] = 'yes';  
             $bankurl = APIURL.('webapi/banklist/bank_list'); 
             $bnkbuffer = curlApis($bankurl,'POST',$bkpst);
             $data['banklist'] = array();
             if($bnkbuffer['status'] && !empty($bnkbuffer['data'])){
                $data['banklist'] = $bnkbuffer['data'];
             }
             /* fetch bank iin number and bank list end code */
 
             agview('aeps_test',$data);
            }
    }  

 


     public function registerotp(){
         $data['pagename'] = $this->pagename;
         $data['folder'] = $this->folder;

         $data['title'] = 'AEPS | Register Mobile';
         $data['mobile'] = $userid = getloggeduserdata('uniqueid');
         agview('aeps_registerotp',$data); 
     }
     public function register_otp(){
             $post = $this->input->post(); 
             $postdb['userid'] = getloggeduserdata('id'); 
             $postdb['user_type'] = getloggeduserdata('user_type'); 
             $postdb['uniqueid'] = getloggeduserdata('uniqueid'); 

             $url = APIURL.('webapi/aeps/registration_otp');
             $buffer1['gotourl'] = ADMINURL.$this->folder.'/'.$this->pagename.'/register' ;
             $buffer = curlApis($url,'POST',$postdb);           
             echo json_encode( ($buffer + $buffer1) );  
     }



     public function register(){ 
         $data['pagename'] = $this->pagename;
         $data['folder'] = $this->folder;

         $data['title'] = 'AEPS | Register User';
         $where['id'] = getloggeduserdata('id');
         $where['user_type'] = getloggeduserdata('user_type');
         $keys = 'uniqueid,emailid,ownername,pancard,firmname,pincode,address';

         $getdata = $this->c_model->getSingle( 'dt_users', $where, $keys );
         $data['mobile'] = $getdata['uniqueid'];
         $data['email'] =  $getdata['emailid'];
         $data['name'] =  $getdata['ownername'];
         $data['pancard'] =  $getdata['pancard'];
         $data['firmname'] =  $getdata['firmname'];
         $data['pincode'] =  $getdata['pincode'];
         $data['address'] =  $getdata['address'];

         agview('aeps_register',$data); 

     }

    public function register_user(){
                $post = $this->input->post(); 
                $where['id'] = getloggeduserdata('id');
                $where['user_type'] = getloggeduserdata('user_type');
                $keys = 'id,user_type, uniqueid,emailid,ownername,pancard,firmname,pincode,address';

                $getdata = $this->c_model->getSingle( 'dt_users', $where, $keys );  

                $postdb['userid'] = $getdata['id'];
                $postdb['user_type'] = $getdata['user_type'];
                $postdb['uniqueid'] = $getdata['uniqueid'];
                $postdb['email'] =  $getdata['emailid'];
                $postdb['company'] =  $getdata['firmname'];
                $postdb['name'] =  $getdata['ownername'];
                $postdb['pan'] =  $getdata['pancard'];
                $postdb['pincode'] =  $getdata['pincode'];
                $postdb['address'] =  $getdata['address'];
                $postdb['otp'] =  $post['otp']; 

             $url = APIURL.('webapi/aeps/registration');
             $buffer1['gotourl'] = ADMINURL.$this->folder.'/'.$this->pagename.'/kycuploads' ;
             $buffer = curlApis($url,'POST',$postdb);           
             echo json_encode( ($buffer + $buffer1) );  
     }


     public function kycuploads(){
         $data['pagename'] = $this->pagename;
         $data['folder'] = $this->folder;

         $data['title'] = 'AEPS | Upload Documents';

            $whereupload['usertype'] = getloggeduserdata('user_type'); 
            $whereupload['tableid'] = getloggeduserdata('id'); 
            $whereupload['documenttype'] = 'Aadhaar Card';

            $aadhaar = $this->c_model->getSingle('dt_uploads',$whereupload,'documentorimage');
          $data['adhaar'] = $aadhaar;
          agview('aeps_uploads',$data); 
     }

     public function upload_kyc(){
             $post = $this->input->post(); 
             $postdb['userid'] = getloggeduserdata('id'); 
             $postdb['user_type'] = getloggeduserdata('user_type');  

             $url = APIURL.('webapi/aeps/uploaddocs');
             $buffer1['gotourl'] = ADMINURL.$this->folder.'/'.$this->pagename ;
             $buffer = curlApis($url,'POST',$postdb);  
             echo json_encode( ($buffer + $buffer1) );
     }



         public function post_formdata(){

             $post = $this->input->post(); 
             $post['userid'] = getloggeduserdata('id'); 
             $post['user_type'] = getloggeduserdata('user_type');
             $post['apptype'] = 'web';
             if(isset($post['sp_keybap']) && ($post['sp_keybap']=='BAP') ){ 
                unset( $post['sp_key'],$post['sp_keybap']);
                $post['sp_key'] = 'BAP'; 
             }



             $where['id'] = getloggeduserdata('id');
             $where['user_type'] = getloggeduserdata('user_type');
             $keys = 'outlet_id';

             $post['outlet_id'] = $this->c_model->getSingle( 'dt_users', $where, $keys );
 

             $buffer['status'] = true;
             $buffer['message'] = 'success';
            
  
             $aepsurl = APIURL.('webapi/aeps/Make_transaction_aeps'); 
             //$buffer = curlApis($aepsurl,'POST',$post);

             echo json_encode($post); exit;

            $ch = curl_init($aepsurl);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json",
            "Accept: application/json"));
            // url_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20 );
            //curl_setopt($ch, CURLOPT_TIMEOUT, 30 );
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
            $json = curl_exec($ch); 
            curl_close($ch);
            $buffer = json_decode($json,TRUE);


            $data['data'] = array(); 
            $mode = $post['sp_key'];

            $data['data'] = $buffer;
            $data['mode'] = $mode ; 


            if($mode == 'BAP'){
            $this->load->view('ag/ajax/aeps_balance',$data);
            }else if($mode == 'WAP'){
            $this->load->view('ag/ajax/aeps_balance',$data);
            }else if($mode == 'SAP'){
            $this->load->view('ag/ajax/aeps_statement',$data);
            }

             /*if($buffer['status'] && !empty($buffer['data'])){
                $buffer['data'] = $buffer['data'];
             } 
             //echo json_encode( $post );
             echo json_encode( $buffer );*/
         }



     public function dsetting(){
        $data['title'] = 'Device Setting';
        $where['id'] = getloggeduserdata('id');
        $where['user_type'] = getloggeduserdata('user_type');
        $data['devicename'] = $this->c_model->getSingle( 'dt_users', $where, 'devicename' );

        $data['posturl'] = ADMINURL.'ag/aeps/dsetting_save'; 
        agview('aeps_device_setting',$data);  
     }   

      public function dsetting_save(){
        $post = $this->input->post();

        $where['id'] = getloggeduserdata('id');
        $where['user_type'] = getloggeduserdata('user_type');

        $update = $this->c_model->saveupdate('dt_users', $post , null , $where );
        if( $update ){
            $this->session->set_flashdata('success','Device Set Successfully');
        } 

         $redirect = ADMINURL.'ag/aeps'; 
         redirect( $redirect ); 
      } 

 

/*
    public function postt(){

              $post = $this->input->post();

            $mode = $post['sp_key'];

            $buffer = '{"status":1,"message":"Success","orderid":"AEPS20207878787","bankname":"Sattebank of india","data":{"statuscode":"TXN","status":"Transaction Successful","data":{"opening_bal":"60855.33","ipay_id":"P200429135633EJDTE","amount":"0.00","amount_txn":"0.00","account_no":"8115171716","txn_mode":"CR","status":"SUCCESS","opr_id":"012013000984","balance":10289.1,"wallet_txn_id":"1200429135635ZFGNW","mini_statement":[{"date":"28\/04\/2020","txnType":"Dr","amount":"50.0","narration":"RWITHDRAWAL TRAN"},{"date":"27\/04\/2020","txnType":"Dr","amount":"177.0","narration":"RWITHDRAWAL TRAN"},{"date":"23\/04\/2020","txnType":"Dr","amount":"3184.82","narration":"RWITHDRAWAL TRAN"},{"date":"23\/04\/2020","txnType":"Cr","amount":"3183.0","narration":"RDEPOSIT TO DEP0"},{"date":"20\/04\/2020","txnType":"Cr","amount":"7000.0","narration":"RDEPOSIT TO DEP0"},{"date":"20\/04\/2020","txnType":"Dr","amount":"12400.0","narration":"RWITHDRAWAL TRAN"},{"date":"14\/04\/2020","txnType":"Cr","amount":"1000.0","narration":"RDEPOSIT TO DEP0"},{"date":"11\/04\/2020","txnType":"Dr","amount":"15.0","narration":"RWITHDRAWAL TRAN"},{"date":"11\/04\/2020","txnType":"Dr","amount":"2.7","narration":"RWITHDRAWAL TRAN"}]},"timestamp":"2020-04-29 13:56:35","ipay_uuid":"6F5D7EB307E6CC7BCAD3","orderid":"P200429135633EJDTE","environment":"PRODUCTION"}}';

            $data['data'] = json_decode($buffer,true);
            $data['mode'] = $mode ; 


            if($mode == 'BAP'){
            $this->load->view('ag/ajax/aeps_balance',$data);
            }else if($mode == 'WAP'){
            $this->load->view('ag/ajax/aeps_balance',$data);
            }else if($mode == 'SAP'){
            $this->load->view('ag/ajax/aeps_statement',$data);
            }

             /*if($buffer['status'] && !empty($buffer['data'])){
                $buffer['data'] = $buffer['data'];
             } 
             //echo json_encode( $post );
             echo json_encode( $buffer );
         }

*/


 public function chmob(){
         $data['pagename'] = $this->pagename;
         $data['folder'] = $this->folder;

         $data['title'] = 'AEPS | Change Mobile';

            $where['user_type'] = getloggeduserdata('user_type'); 
            $where['id'] = getloggeduserdata('id');  
            $keys = 'aeps,old_aeps';
            $aepsArr = $this->c_model->getSingle('dt_users',$where, $keys);
            $data['aeps_mobile'] = !empty($aepsArr['old_aeps']) ? $aepsArr['old_aeps'] : $aepsArr['aeps']; 
            agview('aeps_change_mobile',$data); 
 }


   public function chang_mob_post(){
             $post = $this->input->post(); 
             $postdb['userid'] = getloggeduserdata('id'); 
             $postdb['user_type'] = getloggeduserdata('user_type');
             $postdb['new_mobile'] = $post['nm'];
             $postdb['old_mobile'] = $post['om'];

             $url = APIURL.('webapi/aeps/change_mobile');
             $buffer1['gotourl'] = ADMINURL.$this->folder.'/'.$this->pagename.'/vfymob' ;
             $buffer = curlApis($url,'POST',$postdb);  
             echo json_encode( ($buffer + $buffer1) );
     }
       

public function vfymob(){
         $data['pagename'] = $this->pagename;
         $data['folder'] = $this->folder;

         $data['title'] = 'AEPS | Verify Mobile Number';

            $where['user_type'] = getloggeduserdata('user_type'); 
            $where['id'] = getloggeduserdata('id');  
            $keys = 'aeps,old_aeps';
            $aepsArr = $this->c_model->getSingle('dt_users',$where, $keys);
            $data['old_mobile'] = $aepsArr['old_aeps'];
            $data['new_mobile'] = $aepsArr['aeps'];
            agview('aeps_change_mobile_verify',$data); 
}

 public function chang_mob_verify(){
             $post = $this->input->post(); 
             $postdb['userid'] = getloggeduserdata('id'); 
             $postdb['user_type'] = getloggeduserdata('user_type');
             $postdb['new_mobile_otp'] = $post['nmtp'];
             $postdb['old_mobile_otp'] = $post['omtp'];

         $url = APIURL.('webapi/aeps/change_mobile_verify');
         $buffer1['gotourl'] = ADMINURL.$this->folder.'/'.$this->pagename.'/kycuploads';
         $buffer = curlApis($url,'POST',$postdb); 
         
         /* update docs automatic */
         if( $buffer['status'] ){
             $postdoc['userid'] = getloggeduserdata('id'); 
             $postdoc['user_type'] = getloggeduserdata('user_type');  

             $urldoc = APIURL.('webapi/aeps/uploaddocs'); 
             $buffer3 = curlApis($urldoc,'POST',$postdoc);
             $redirect = ADMINURL.'ag/aeps'; 
             redirect( $redirect ); 
         }  

         echo json_encode( ($buffer + $buffer1) );
     }


public function is_deviceready(){
    $post = $this->input->get('port');
    $this->session->set_userdata('device_port',$post );
    redirect( ADMINURL.'ag/aeps' ); 
}


}?>