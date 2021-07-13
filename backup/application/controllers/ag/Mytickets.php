<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Mytickets extends CI_Controller{
    
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session'); 
    }

    public function index()
    { 

        $data['title'] = 'My Tickets';
        $data['folder'] = $this->uri->segment(1);
        $data['pagename'] = 'mytickets';
        
        $where = [];

        $where["userid"] = getloggeduserdata('id');
        $where['usertype'] = getloggeduserdata('user_type');
        $where["filterby"] = '';
        $where["requestparam"] = '';
        $where["limit"] = 500;
        $where["start"] = 0;
        $where["orderby"] = 'DESC';

        if( $this->input->get() ){
         $get = $this->input->get();
         $dateFrom = str_replace('/', '-', $get['from']);
         $dateTo = str_replace('/', '-', $get['to']);
         $where["filterby"] ='date';
         $where["requestparam"] = date("Y-m-d", strtotime($dateFrom)).'|'.date("Y-m-d", strtotime($dateTo)); 
        } 

///print_r($where); exit;

         $searchurl = APIURL.('webapi/agent/trans_complnt_history');
         $searchlist = curlApis( $searchurl,'POST',$where );
         $data['rows'] = array();
         if($searchlist['status']){
            $data['rows'] = $searchlist['data'];
         }   
        //print_r($data['rows']); exit;

        agview('complaint_transational', $data);
    }  


}