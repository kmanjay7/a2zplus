<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Credit_amount extends CI_Controller{
    var $panename;
    var $folder;
	public function __construct(){
        parent::__construct();
        $this->load->library('session');
        $this->load->library('pagination');     
                mdsession_check();
                sessionunique();  
                $this->pagename = 'Credit_amount';
                $this->folder = $this->uri->segment(1);
        }



    public function index(){ 

        $data['title'] = 'CREDIT';
        $data['folder'] = $this->folder;
        $data['pagename'] = $this->pagename; 
		  
        mdview('credit_amount',$data );
        }  




// public function getuserinfo(){
//     $post = $this->input->post();
//     $getvalue = $post['inputfield'];
    
//     $where['parentid'] = getloggeduserdata('id');
//     $where['status'] = 'yes';
    
//     $requestmethod = 'usertype';
//     if( is_numeric($getvalue) && strlen($getvalue) == 10 ){
//     $where['uniqueid'] = $getvalue;
//     $requestmethod = 'mobileno';
//     }else{
//     $where['user_type'] = $getvalue; 
//     }

//     $keys = 'id,uniqueid,ownername,user_type';
//     $getdbdata = $this->c_model->getAll('dt_users','ownername ASC',$where,$keys );
    
//     $getdata['status'] = false;
//     $getdata['list'] = '';
//     $getdata['usertype'] = $requestmethod;
//     if( !empty($getdbdata)){
//      $getdata['list'] = $getdbdata;
//      $getdata['status'] = true;
//     }  

//      echo json_encode($getdata);
    
// }

public function getuserinfo(){
    $post = $this->input->post();
    $getvalue = $post['inputfield'];
    $uniqueid = $post['uniqueid'];
    
    $where['parentid'] = getloggeduserdata('id');
    $where['status'] = 'yes';
    
    $where['uniqueid'] = $uniqueid;
    $where['user_type'] = $getvalue; 

    $keys = 'id,uniqueid,ownername,user_type';
    $getdbdata = $this->c_model->getAll('dt_users','ownername ASC',$where,$keys );
    
    $getdata['status'] = false;
    $getdata['list'] = '';
    if( !empty($getdbdata)){
     $getdata['list'] = $getdbdata;
     $getdata['status'] = true;
    }  

     echo json_encode($getdata);
}

public function getusertype(){
    $post = $this->input->post();
    $getvalue = $post['inputfield'];
    
    $where['parentid'] = getloggeduserdata('id');
    $where['status'] = 'yes';
    $where['uniqueid'] = $getvalue;

    $keys = 'user_type';
    $getdbdata = $this->c_model->getAll('dt_users','ownername ASC',$where,$keys );
    
    $getdata['status'] = false;
    $getdata['list'] = '';
    if( !empty($getdbdata)){
     $getdata['list'] = $getdbdata;
     $getdata['status'] = true;
    }  

    echo json_encode($getdata);
}


public function credit(){
    $post = $this->input->post();
    $userid = $post['userid'];
    $amount = $post['amount'];
    $comments = $post['comments'];
    $usertype = $post['usertype'];
    
    $out['status'] = false;
    $out['message'] = 'Please fill all required fields!';

    if( $userid && $amount && $comments && $usertype ){
        $senduserdb = $this->c_model->getSingle('dt_users',['id'=>$userid,'user_type'=>$usertype],'uniqueid,id');
        
        /* check wallet for this transaction start */  
        $orderid = 'WLT'.date('YmdHis').rand(100,999);
        $wtsave['userid'] = getloggeduserdata('id');
        $wtsave['usertype'] = getloggeduserdata('user_type');
        $wtsave['uniqueid'] = getloggeduserdata('uniqueid');
        $wtsave['paymode'] = 'wallet';
        $wtsave['transctionid'] = date('YmdHis');
        $wtsave['credit_debit'] = 'debit';
        $wtsave['upiid'] = '';
        $wtsave['bankname'] = ''; 
        //$wtsave['remark'] = 'Amount INR '.$amount.' Debited from main wallet and Credited to main wallet of '.$senduserdb['uniqueid'].'|Debited by self Request';
        $wtsave['remark'] = $comments; 
        $wtsave['status'] = 'success'; 
        $wtsave['amount'] = trim($amount); 
        $wtsave['subject'] = 'borrow_debit';
        $wtsave['addby'] = getloggeduserdata('id');
        $wtsave['orderid'] = $orderid;
        $wtsave['coments'] = $comments;
        $wtsave['borrow'] = 'borrow';
        $wtsave['sendto'] = $userid;

        $wallet_apiurl = base_url('webapi/wallet/Creditdebit');
        $upwtwhere['dts'] = $wtsave;   
        $header = array('auth: Access-Token='.WALLETOKEN ); 
        $upwt = $amount ? curlApis($wallet_apiurl,'POST', $upwtwhere,$header ):array();
       


        if($upwt['status']){
            $out['status'] = true;
             

             /* credit to borrow user wallet start */
        $orderid = 'WLT'.date('YmdHis').rand(100,999);

             $wtsave = array(); 
        $wtsave['userid'] = $userid;
        $wtsave['usertype'] = $usertype;
        $wtsave['uniqueid'] = $senduserdb['uniqueid'];
        $wtsave['paymode'] = 'wallet';
        $wtsave['transctionid'] = date('YmdHis');
        $wtsave['credit_debit'] = 'credit';
        $wtsave['upiid'] = '';
        $wtsave['bankname'] = ''; 
        $wtsave['remark'] = 'Amount INR '.$amount.' Credited to main wallet|Credited by '.getloggeduserdata('uniqueid'); 
        $wtsave['status'] = 'success'; 
        $wtsave['amount'] = trim($amount); 
        $wtsave['subject'] = 'borrow_credit';
        $wtsave['addby'] = getloggeduserdata('id');
        $wtsave['orderid'] = $orderid;
        $wtsave['coments'] = $comments;
        $wtsave['borrow'] = 'borrow';

        $wallet_apiurl = base_url('webapi/wallet/Creditdebit');
        $upwtwhere['dts'] = $wtsave;  
        $header = array('auth: Access-Token='.WALLETOKEN ); 
        $upwt = $amount ? curlApis($wallet_apiurl,'POST', $upwtwhere,$header ):array();
        if($upwt['status']){
            $out['status'] = true;
            $out['message'] = 'Amount INR '.$amount.' Debited from main wallet and Credited to main wallet of '.$senduserdb['uniqueid'].'|Debited by self Request';
        }
             /* credit to borrow user wallet start */


        }else{ $out['message'] = $upwt['message']; } 
       
        /* check wallet for this registration end */

    } 

    echo json_encode($out);
}



}
?>