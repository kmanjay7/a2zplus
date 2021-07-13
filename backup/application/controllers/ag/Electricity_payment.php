<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Electricity_payment extends CI_Controller{
    var $pagename;
    var $folder;
    private $serviceid=4;



    public function __construct()
    {
        parent::__construct();
        $this->load->library('session'); 
        //$this->load->library('pagination');
        agsession_check();
        // $this->pagename = 'electricity_digiu';
        $this->folder = $this->uri->segment(1);
        $this->load->model("general_model");
        $this->load->helper('bbps');
        $this->load->library('curl');

        $operator_code=$this->general_model->getSingle("operators_code", ["serviceid"=>$this->serviceid], "operatorid, op_code");
        if(empty($operator_code))
        {
            $response['status'] = FALSE;
            $response['message'] = 'No API set for this payment!';
            echo json_encode($response);
            exit;
        }

        $operator = $this->c_model->getSingle('operators', ["id"=>$operator_code['operatorid']], 'id,operator,currentapiid');
    
        // $GLOBALS['elc_apiid'] = $operator['currentapiid'];
        $GLOBALS['elc_apiid']=15;

        /*if($GLOBALS['elc_apiid']==7)
        {
            $this->pagename = 'electricity';
            
        }if($GLOBALS['elc_apiid']==14)
        {
            $this->pagename = 'electricity_bill';
        }else if($GLOBALS['elc_apiid']==15){
            $this->pagename = 'electricity_payment';
        }*/

        $this->pagename = 'electricity_payment';

        //echo $this->pagename;exit;
    }

    public function index()
    { 
        $data['operator'] = array();
        $data = array();
        $data['title'] = 'Electricity Bill Pay';
        $data['folder'] = $this->folder ;
        $data['pagename'] = $this->pagename ; 
        $data["operatorlist"]=[];
                
        /*get operator start*/

        $post['serviceid'] = $this->serviceid; 
        $url = APIURL.('webapi/a2z/Getoperators');
        $operator = curlApis($url,'POST',$post);  
        
        if(isset($operator['data']) && $operator['status']){
            $data["operatorlist"] = create_dropdownfrom_array($operator['data'],'op_code','operator','operator--');
        }  
        /*get operator end*/  
        
        
        agview('common_elc_pay',$data);
    }  

    function fetch(){
        $request=getArrayFromRawData();

        $request["uniqueid"]=$this->session->userdata("uniqueid");
        $request["usertype"]=$this->session->userdata("user_type");
        $request["serviceid"]=$this->serviceid;

        $url = APIURL.('webapi/bbps/bill_fetch');
        $response = curlApis( $url,'POST',$request );
        
        echo json_encode($response);
    }

    function pay()
    {
        $request=getArrayFromRawData();
        
        $request["userid"]=$this->session->userdata("id");
        $request["uniqueid"]=$this->session->userdata("uniqueid");
        $request["usertype"]=$this->session->userdata("user_type");
        $request["serviceid"]=$this->serviceid;  
        
        // echo "<pre>"; print_r($request); die;
        
        $url = APIURL.('webapi/digiupay/Transaction_request');
        $response = curlApis( $url,'POST',$request,null, 100 );
        
        // echo "<pre>"; print_r($response); die;
        
        
        echo json_encode($response);exit;
    }

}?>