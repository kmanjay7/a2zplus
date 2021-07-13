<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Money_transaction_report extends CI_Controller{
    var $panename;
    var $folder;
	public function __construct(){
        parent::__construct();
        $this->load->library('session');
        $this->load->library('pagination');     
                adsession_check();
                sessionunique();  
                $this->pagename = 'Money_transaction_report';
                $this->folder = 'ad';
        }




  public function checkTranStatus(){

        $where['dt_wallet.userid'] = getloggeduserdata('id');
        $where['dt_wallet.usertype'] = getloggeduserdata('user_type'); 
        $where['dt_dmtlog.status'] = 'SUCCESS'; 

        $select = 'SUM(dt_dmtlog.ad_comi) as commission, SUM(dt_dmtlog.ad_tds) as tds, SUM(dt_dmtlog.amount) AS total, SUM(dt_dmtlog.sur_charge) as surcharge';
        $where['dt_wallet.subject'] = 'money_transfer_commission';
        $from = 'dt_dmtlog';
        $jointable = 'dt_wallet';
        $joinon = 'dt_wallet.transctionid = dt_dmtlog.orderid ';
        $jointype = 'INNER';
        $getorcount = 'get';
 
        $array =  $this->c_model->joindata( $select, $where, $from, $jointable, $joinon, $jointype,null,null,null, null, null,null,null,$getorcount );
  
        return !is_null($array)?$array[0]:array();
 }   






    public function index(){ 

        $data['title'] = 'Money Transaction Details';
        $data['folder'] = $this->folder;
        $data['pagename'] = $this->pagename;

       

        $userid = getloggeduserdata('id');
        $user_type = getloggeduserdata('user_type');
        
        $transferreport = $this->checkTranStatus();
        $data['success_amt'] = $transferreport['total'];
        $data['total_commission'] = $transferreport['commission'];
        $data['total_surcharge'] = $transferreport['surcharge'];
        $data['total_tds'] = $transferreport['tds'];


 $urluri = ADMINURL.$this->folder.'/'.$this->pagename.'/index/?';
              
          /********* Get recent transactions script start here *******/ 
                $urllimit = $this->input->get('per_page')?$this->input->get('per_page'):1;
                $urllimit = $urllimit > 1 ? ($urllimit-1) : 0;
                $table = 'dt_wallet';
                $limit = 10; 
                $where['userid'] = $userid;
                $where['usertype'] = $user_type;
                $where['subject'] = 'money_transfer_commission';   
                $countItem = $this->c_model->countitem($table,$where);

                $pgarr['baseurl'] = $urluri;
                $pgarr['total'] = $countItem;
                $pgarr['limit'] = $limit;
                $pgarr['segmenturi'] =  $urllimit;
                $data["pagination"] = my_pagination($pgarr);
                 
               
                $offset = $urllimit*$limit;

            $orderby = 'DESC';
            $orderbykey = 'id';
            $whereor = null;
            $whereorkey = null;
            $like = null;
            $likekey = null;
            $getorcount = '';
            $infield = null;
            $invalue = null;
            $keys = 'id,credit_debit,remark,subject,referenceid,add_date,beforeamount,amount,finalamount';
            $groupby = null;

           
            $data['walletlist'] = $this->c_model->getfilter($table,$where,$limit,$offset, $orderby, $orderbykey, $whereor, $whereorkey, $like, $likekey,  $getorcount, $infield, $invalue, $keys ,$groupby );

  
        /********* Get recent transactions script end here *********/

 
		  
        adview('money_transaction_report',$data );
        }  



}
?>