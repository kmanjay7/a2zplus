<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Electricity extends CI_Controller{
    var $pagename;
    var $folder;
    private $serviceid=4;

	public function __construct()
    {
		parent::__construct();
        $this->load->library('session'); 
        //$this->load->library('pagination');
        agsession_check();
        $this->pagename = 'electricity';
        $this->folder = $this->uri->segment(1);
        $this->load->model("general_model");
        $this->load->helper('bbps');
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
        $url = APIURL.('webapi/bbps/Getoperators');
        $operator = curlApis($url,'POST',$post);  

        if(isset($operator['data']) && $operator['status']){
            $data["operatorlist"] = create_dropdownfrom_array($operator['data'],'op_code','operator','operator--');
        }  
        /*get operator end*/  

        agview('bbps',$data);
	}  

    


    function get_inputs(){
        $biller_id=$this->input->post("operator");
        $url = APIURL.('webapi/bbps/fetch_billers');
        $res = curlApis( $url,'POST',["biller_id"=>$biller_id]);
        //echo json_encode($res);exit;
        if($res["status"])
        {
            $html=""; $i = 1;
            foreach ($res["data"]["bill_details_input_parameters"] as $field) 
            {
                $required=""; $io = '';
                if($field["required"]) $required=' required=""';
                /*check first input*/
                if($i==1){
                    $io = 'onkeyup="io(this.value)" onblur="io(this.value)"';
                }

                $html.='<div class="col-md-3 col-lg-4 col-12">
                       <label> '.$field["display_value"].' </label>
                       <input onkeydown="hide_pay_btn()" '.$io.' required="" class="form-control" placeholder="'.$field["display_value"].'" type="text" name="'.$field["field_name"].'"'.$required.' autocomplete="off">
                    </div>';
            }
            $response["status"]=true;
            $response["data"]=$html;
            $response["message"]="Success!";
        }
        else
        {
            $response["status"]=false;
            $response["message"]="Unable to fetch this biller!";
        }
        echo json_encode($response);
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

        $url = APIURL.('webapi/bbps/bill_pay');
        $response = curlApis( $url,'POST',$request,null, 100 );
        
        echo json_encode($response);
    }

}?>