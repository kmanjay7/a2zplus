<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Debit_amount extends CI_Controller{
    var $panename;
    var $folder;
	public function __construct(){
        parent::__construct();
        $this->load->library('session');
        $this->load->library('pagination');     
                adsession_check();
                sessionunique();  
                $this->pagename = 'Debit_amount';
                $this->folder = 'ad';
        }



    public function index(){ 

        $data['title'] = 'DEBIT';
        $data['folder'] = $this->folder;
        $data['pagename'] = $this->pagename;

       
        $userid = getloggeduserdata('id');
        $user_type = getloggeduserdata('user_type');
        
        


    $urluri = ADMINURL.$this->folder.'/'.$this->pagename.'/index/?';
              
          /********* Get recent transactions script start here *******/ 
                $urllimit = $this->input->get('per_page')?$this->input->get('per_page'):1;
                $urllimit = $urllimit > 1 ? ($urllimit-1) : 0;
                $table = 'dt_wallet';
                $limit = 10; 
                $where['userid'] = $userid;
                $where['usertype'] = $user_type;
                $where['subject'] = 'borrow_credit';   
                $countItem = $this->c_model->countitem($table,$where);

                $pgarr['baseurl'] = $urluri;
                $pgarr['total'] = $countItem;
                $pgarr['limit'] = $limit;
                $pgarr['segmenturi'] =  $urllimit;
                $data["pagination"] = my_pagination($pgarr);
                 
               
            $offset = $urllimit*$limit;


            $trwhere['a.addby'] = $userid;
            $trwhere['a.subject'] = 'borrow_credit';
            $trwhere['a.usertype'] = $user_type; 

               

            $select = 'a.id,a.paymode,a.credit_debit,a.remark,a.subject,a.referenceid,a.add_date,a.beforeamount,a.amount,a.finalamount,a.usertype as dusertype, b.uniqueid as duid, b.ownername as dname , c.user_type as cusertype, c.uniqueid as cuid, c.ownername as cname '; 
             
            $from = 'dt_wallet as a'; 

            $join[0]['table'] = 'dt_users as b';
            $join[0]['joinon'] = 'b.id = a.addby' ; 
            $join[0]['jointype'] = 'LEFT'; 

            $join[1]['table'] = 'dt_users as c';
            $join[1]['joinon'] = 'c.id = a.sendto' ; 
            $join[1]['jointype'] = 'LEFT';  

 
            $groupby = null; 
            $orderby = 'a.id DESC' ; 
            $getorcount = 'get';

            $data['trans_list'] = $this->c_model->joinmultiple( $select, $trwhere, $from, $join, $groupby ,$orderby, $limit, $offset, $getorcount ); 
 
//print_r($data['trans_list']); //exit;
  
        /********* Get recent transactions script end here *********/

 
          
        adview('debit_amount',$data );
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



public function debit(){
    $post = $this->input->post();
    $tableid = $post['id'];

$walletdb = $this->c_model->getSingle('dt_wallet',['md5(id)'=>$tableid],' * ');


    $amount = $walletdb['amount'];
    $comments = '';
    $usertype = $walletdb['usertype'];
    
    $out['status'] = false;
    $out['message'] = 'Please fill all required fields!';

    $userid = $walletdb['sendto'];

    if( $tableid && $amount && $usertype ){
        $senduserdb = $this->c_model->getSingle('dt_users',['id'=>$userid],'uniqueid,id,user_type');
        
        
       
/* credit to borrow user wallet start */
        $orderid = 'WLT'.date('YmdHis').rand(100,999); 
        $wtsave = array(); 
        $wtsave['userid'] = $senduserdb['id'];
        $wtsave['usertype'] = $senduserdb['user_type'];
        $wtsave['uniqueid'] = $senduserdb['uniqueid'];
        $wtsave['paymode'] = 'wallet';
        $wtsave['transctionid'] = $orderid ;
        $wtsave['credit_debit'] = 'debit';
        $wtsave['upiid'] = NULL;
        $wtsave['bankname'] = NULL; 
         $wtsave['remark'] = 'Amount INR '.$amount.' Debited from main wallet and Credited to main wallet of '.getloggeduserdata('uniqueid').'|Debited by '.getloggeduserdata('uniqueid');  
        $wtsave['status'] = 'success'; 
        $wtsave['amount'] = trim($amount); 
        $wtsave['subject'] = 'borrow_debit';
        $wtsave['addby'] = getloggeduserdata('id');
        $wtsave['orderid'] = $walletdb['referenceid'];
        $wtsave['coments'] = 'Amount debited Against orderid : '.$walletdb['referenceid'] ;
        $wtsave['borrow'] = 'return';
        $wtsave['sendto'] = getloggeduserdata('id');

        $wallet_apiurl = base_url('webapi/wallet/Creditdebit');
        $upwtwhere['dts'] = $wtsave;  
        $header = array('auth: Access-Token='.WALLETOKEN ); 
        $upwt = $amount ? curlApis($wallet_apiurl,'POST', $upwtwhere,$header ):array();


        if($upwt['status']){
            $out['status'] = true;

        $this->c_model->saveupdate('dt_wallet',['borrow'=>'return'],null,['id'=>$walletdb['id'] ]);    
             
        $orderid = 'WLT'.date('YmdHis').rand(100,999);
        $wtsave['userid'] = getloggeduserdata('id');
        $wtsave['usertype'] = getloggeduserdata('user_type');
        $wtsave['uniqueid'] = getloggeduserdata('uniqueid');
        $wtsave['paymode'] = 'wallet';
        $wtsave['transctionid'] = $orderid ;
        $wtsave['credit_debit'] = 'credit';
        $wtsave['upiid'] = NULL;
        $wtsave['bankname'] = NULL; 
        $wtsave['remark'] = 'Amount INR '.$amount.' Credited to main wallet from wallet '.$senduserdb['uniqueid'].' |Credited by '.getloggeduserdata('uniqueid'); 
        $wtsave['status'] = 'success'; 
        $wtsave['amount'] = trim($amount); 
        $wtsave['subject'] = 'borrow_credit';
        $wtsave['addby'] = getloggeduserdata('id');
        $wtsave['orderid'] = $orderid; 
        $wtsave['borrow'] = 'return';
        $wtsave['sendto'] = $userid;
         

        $wallet_apiurl = base_url('webapi/wallet/Creditdebit');
        $upwtwhere['dts'] = $wtsave;  
        $header = array('auth: Access-Token='.WALLETOKEN ); 
        $upwt = $amount ? curlApis($wallet_apiurl,'POST', $upwtwhere,$header ):array();
        
        if($upwt['status']){
            $out['status'] = true;
            $out['message'] = 'Amount INR '.$amount.' Debited from main wallet and Credited to main wallet of '.getloggeduserdata('uniqueid').'|Debited by '.getloggeduserdata('uniqueid');
        }
             /* credit to borrow user wallet start */


        }else{ $out['message'] = $upwt['message']; } 
       
        /* check wallet for this registration end */

    } 

    echo json_encode($out);
}



public function getRecord(){
    $post = $this->input->post()?$this->input->post():$this->input->get();
     

            $trwhere['a.addby'] = getloggeduserdata('id');
            $trwhere['a.subject'] = 'borrow_debit';
            $trwhere['a.sendto'] = $post['id'];
            $trwhere['a.credit_debit'] = 'debit'; 
            $trwhere['a.borrow'] = 'borrow';

               

            $select = 'a.id,a.paymode,a.credit_debit,a.remark,a.subject,a.referenceid,a.add_date,a.beforeamount,a.amount,a.finalamount,a.usertype as dusertype, b.uniqueid as duid, b.ownername as dname , c.user_type as cusertype, c.uniqueid as cuid, c.ownername as cname '; 
             
            $from = 'dt_wallet as a'; 

            $join[0]['table'] = 'dt_users as b';
            $join[0]['joinon'] = 'b.id = a.addby' ; 
            $join[0]['jointype'] = 'LEFT'; 

            $join[1]['table'] = 'dt_users as c';
            $join[1]['joinon'] = 'c.id = a.sendto' ; 
            $join[1]['jointype'] = 'LEFT';  

 
            $groupby = null; 
            $orderby = 'a.id DESC' ; 
            $getorcount = 'get';
            $offset = null;
            $limit =  null;

            $trans_list = $this->c_model->joinmultiple( $select, $trwhere, $from, $join, $groupby ,$orderby, $limit, $offset, $getorcount ); 

            
         $data['trans_list'] = $trans_list ;

         $this->load->view( $this->folder.'/ajax/debit_search_ajax',$data);   

}




}
?>