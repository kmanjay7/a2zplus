<?php defined('BASEPATH') OR exit('No direct script access allowed');



class Gotopaytm extends CI_Controller{
    var $folder;
    var $pagename;
    var $apiid;
    var $serviceid;
	public function __construct(){
		parent::__construct();
        $this->load->library('session'); 
               $this->folder = '';
               $this->pagename = 'Gotopaytm';
               $this->apiid = 8;
               $this->serviceid = 7;
		}

     public function index(){  
       
		    
     $data['folder'] = $this->folder;
     $data['pagename'] = $this->pagename;

     $post = $this->input->post();

     if(empty($post)){ redirect( ADMINURL ); }
     

     if($post['user_type']=='AGENT'){ $folder = 'ag'; }
     else{ $folder = strtolower( trim($post['user_type']) ); }


     if(!$post['user_type']){ 
      $redirect = ADMINURL.$folder.'/add_fund';
      redirect( $redirect );
     }else if(!$post['user_id']){
      $redirect = ADMINURL.$folder.'/add_fund';
      redirect( $redirect );
     }else if(!$post['amount']){
      $redirect = ADMINURL.$folder.'/add_fund';
      redirect( $redirect );
     }


/*if service is temporarily Down*/
//if($post['user_id'] != 13 ){
    // $this->session->set_flashdata('error','Service is Temporarily Down');
    //  redirect( ADMINURL.$folder.'/add_fund' ); 
 //}
/* remove after testing*/


     


     
     $posturl = ADMINURL.'webapi/paytm/Paytm_gateway_checksum';
     $param['user_id'] = $post['user_id'];
     $param['user_type'] = $post['user_type'];
     $param['amount'] = $post['amount'];

     $buffer = curlApis($posturl,'POST',$param );
    // print_r($buffer); exit;
     if(!empty($buffer['status'])){

        echo 'Please wait! Redirecting to Payment Gateway....';

        $paramListarr = $buffer['data']['paramList'];
        $paramLists['MID'] = $paramListarr['MID'];
        $paramLists['ORDER_ID'] = $paramListarr['ORDER_ID'];
        $paramLists['CUST_ID'] = $paramListarr['CUST_ID'];
        $paramLists['INDUSTRY_TYPE_ID'] = $paramListarr['INDUSTRY_TYPE_ID'];
        $paramLists['CHANNEL_ID'] = $paramListarr['CHANNEL_ID'];
        $paramLists['TXN_AMOUNT'] = $paramListarr['TXN_AMOUNT'];
        $paramLists['WEBSITE'] = $paramListarr['WEBSITE'];
        $paramLists['CALLBACK_URL'] = $paramListarr['CALLBACK_URL'];
        $checkSum = $buffer['data']['checkSum'];

        $log = $this->pushlog($paramListarr['ORDER_ID'],'adfund','I',json_encode($paramLists));
         

require_once(APPPATH . "/third_party/paytmgateway/config_paytm.php");
require_once(APPPATH . "/third_party/paytmgateway/encdec_paytm.php");

        echo '<form  method="post" action="'.PAYTM_TXN_URL.'" name="f1" id="form">'; 
            foreach($paramLists as $name => $value) { 
        echo '<input type="hidden" name="' . $name .'" value="' . $value . '">'; 
            }
 
        echo '<input type="hidden" name="CHECKSUMHASH" value="'.$checkSum.'">';
                   
                 echo '<button type="submit" class="btn btn-warning" ></button>
                    <script> document.getElementById("form").submit(); 
                    </script> </form>'; 
     }else{
        $this->session->set_flashdata('error',$buffer['message']);
        redirect( ADMINURL.$folder.'/add_fund' ); 
     }  
     exit;

 }



 /*****************capture paytem response here************/

 public function response(){
    $post = $this->input->post();



    $save['status'] = isset($post['STATUS'])?$post['STATUS']:false;
    $save['respmsg'] = isset($post['RESPMSG'])?$post['RESPMSG']:false;

    $orderid = isset($post['ORDERID'])?$post['ORDERID']:false;
    if(!$orderid){
      $redirect = ADMINURL;
      redirect( $redirect );
      exit;
    }

    $log = $this->pushlog($orderid,'adfund','O',json_encode($post));


    /*get order info*/
    $where['orderid'] = $orderid;
    $odrdb = $this->c_model->getSingle('dt_paytmlog',$where,'id,userid,usertype');

    $usrwhere['id'] = $odrdb['userid'];
    $usrwhere['user_type'] = $odrdb['usertype']; 
    $userdb = $this->c_model->getSingle('dt_users',$usrwhere,'id,user_type ');

    if($userdb['user_type']=='AGENT'){ $folder = 'ag'; }
    else{ $folder = strtolower( trim($userdb['user_type']) ); }

  
        /* in success status*/

        if( $post['STATUS'] == 'TXN_SUCCESS'){
           
                if($post['STATUS']){ 
                $this->session->set_flashdata('success',$save['respmsg']);
                }else{
                $this->session->set_flashdata('error',$save['respmsg']);
                } 
        $redirect = ADMINURL.$folder.'/add_fund';
        redirect( $redirect );
        exit;
        }else if( $post['STATUS'] == 'TXN_FAILURE'){
            $this->session->set_flashdata('error', $save['respmsg'] );
            $redirect = ADMINURL.$folder.'/add_fund';
            redirect( $redirect );
            exit;
        }else{
            $this->session->set_flashdata('error','Some error Occured!');
            $redirect = ADMINURL.$folder.'/add_fund';
            redirect( $redirect );
            exit;
        }

     
 }


 public function pushlog($odr,$type,$io,$payload){
    $insert = [];
    $insert['odr'] = $odr;
    $insert['type'] = $type;
    $insert['io'] = $io;
    $insert['req_res'] = $payload;
    $insert['timeon'] = date('Y-m-d H:i:s'); 
    return $this->c_model->saveupdate('dt_pushlog',$insert );
}
 

}?>