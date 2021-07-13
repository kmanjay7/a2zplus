<?php defined('BASEPATH') OR exit('No direct script access allowed');



class Subscribe_plan extends CI_Controller{
    var $pagename;
    var $folder;
	public function __construct(){
		parent::__construct();
        $this->load->library('session');  
               bpsession_check();
               $this->pagename = 'subscribe_plan';
               $this->folder = 'bp';
		}

     public function index(){ 
        
		      
		     $data['title'] = 'Subscribe A Plan';
		     $data['folder'] = $this->folder;
		     $data['pagename'] = $this->pagename;

		     $gt = $this->c_model->getSingle('dt_usertype',['type'=>strtoupper($this->folder)], 'validity,amount,id' );
		     $data['validity'] = $gt['validity'];
		     $data['amount'] = $gt['amount'];
		     $data['id'] = $gt['id'];


 $dbdata = $this->c_model->getSingle('dt_users',['id'=>getloggeduserdata('id')], 'fromdate,todate' );
 $data['fromdate'] = date('Y-m-d H:i:s',strtotime($dbdata['fromdate']));
 $data['todate'] = date('Y-m-d H:i:s',strtotime($dbdata['todate']));

      
             bpview('subscribe_plan',$data);
	}   


    public function takeplan(){ 
                    $s_post['userid'] = getloggeduserdata('id');
                    $s_post['user_type'] = getloggeduserdata('user_type');
                    $s_post['indays'] = 0;
                    $s_post['amount'] = 0; 
                    $s_post['comission'] = 0; 
            $apiurl = ADMINURL.('webapi/agent/update_plan'); 
  
            $buffer = curlApis($apiurl,'POST', $s_post );
            echo json_encode($buffer); 
}  



}?>