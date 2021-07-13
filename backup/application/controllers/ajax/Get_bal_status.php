<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Get_bal_status extends CI_Controller {
   public function __construct(){
    parent::__construct();  
    $this->load->model('Wallet_status_model', 'wt_status');
    }
 

 public function index(){
    $data = [];
    $data['aepswt'] = '0.000';
    $data['mainwt'] = '0.000';
   if( ($_SERVER['REQUEST_METHOD'] == 'POST') ){
    $post = $this->input->post();
    $user_type = $post['type']; 
    $userid = $post['id']; 
    /* get main wallet data start */ 
    $wt['userid'] = $userid;
    $wt['usertype'] = $user_type; 
    $wt['status !='] = 'failed'; 
    $inkey = 'credit_debit';
    $invalue = 'credit,debit';
    $wt_data = $this->wt_status->getSingle_wt( 'dt_wallet' ,$wt, 'id,finalamount','id DESC' , 1, $inkey, $invalue ); 
/* get main wallet data end */

/* get aeps wallet data start */ 
    $wte['userid'] = $userid;
    $wte['usertype'] = $user_type; 
    $wte['status !='] = 'failed'; 
    $ae_inkey = 'credit_debit';
    $ae_invalue = 'credit,debit';
    $wte_data = $this->wt_status->getSingle_wt('dt_wallet_aeps' ,$wte, 'id,finalamount','id DESC' , 1, $ae_inkey, $ae_invalue ); 
/* get aeps wallet data end */
       
        $data['aepswt'] = twoDecimal($wte_data['finalamount']);
        $data['mainwt'] = twoDecimal($wt_data['finalamount']);
    } 
    echo json_encode($data);

 }

}?>