<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Electricity_bill extends CI_Controller{
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
        $this->load->library('curl');
    }

    public function index()
    { 
        $data['operator'] = array();
        $data = array();
        $data['title'] = 'Electricity Bill Pay';
        $data['folder'] = $this->folder ;
        $data['pagename'] = $this->pagename ; 
        $data["operatorlist"]=[];
        
        // $datas = $this->db->select()
        //                  ->where('service',14)
        //                  ->get('operators')
        //                  ->result();
        // echo "<pre>"; print_r($datas); die;
        
        /*get operator start*/

        $post['serviceid'] = $this->serviceid; 
        $url = APIURL.('webapi/a2z/Getoperators');
        $operator = curlApis($url,'POST',$post);  

        // echo "<pre>"; print_r($operator); die;
        // $datas = array(
        //     'api_token' => 'eVHGnU59YQEo1Rvwa7cNyF9pTRNeGQQ5Lbi0EWQHW5uso4rnKLkttCKfky6w', 
        //     'userId'    => '12816',
        //     'secretKey' => '12816yvRbT7rBYqwPAUQqVdNb0P4xbs8cVBY6S95PqDSVdC7bcihRyEkFxqoCh2cM',
        // );
        
        // $response = $this->curl->simple_post('https://partners.a2zsuvidhaa.com/api/v3/get-provider-list',$datas);
        
        // $res = json_decode($response);
        
        // $listOfProvider = $res->providerList;
        
        // $list = [];
        // foreach($listOfProvider as $key =>$val ){
        //     if($val->service_name=='ELECTRICITY'){
        //         $list['0'] = '--Provider--';
        //         $key = $val->provider_id;
        //     $list[$key] = $val->provider_name;    
        //     }   
        // }
        // $data["operatorlist"] = $list;
        
        if(isset($operator['data']) && $operator['status']){
            $data["operatorlist"] = create_dropdownfrom_array($operator['data'],'op_code','operator','operator--');
        }  
        /*get operator end*/  
        
        
        agview('a2z',$data);
    }  

    


    // function get_inputs(){
        
    //     $biller_id=$this->input->post("operator");
    //     $url = APIURL.('webapi/bbps/fetch_billers');
    //     $res = curlApis( $url,'POST',["biller_id"=>$biller_id]);
    //     echo json_encode($res);exit;
    //     if($res["status"])
    //     {
    //         $html=""; $i = 1;
    //         foreach ($res["data"]["bill_details_input_parameters"] as $field) 
    //         {
    //             $required=""; $io = '';
    //             if($field["required"]) $required=' required=""';
    //             /*check first input*/
    //             if($i==1){
    //                 $io = 'onkeyup="io(this.value)" onblur="io(this.value)"';
    //             }

    //             $html.='<div class="col-md-3 col-lg-4 col-12">
    //                    <label> '.$field["display_value"].' </label>
    //                    <input onkeydown="hide_pay_btn()" '.$io.' required="" class="form-control" placeholder="'.$field["display_value"].'" type="text" name="'.$field["field_name"].'"'.$required.' autocomplete="off">
    //                 </div>';
    //         }
    //         $response["status"]=true;
    //         $response["data"]=$html;
    //         $response["message"]="Success!";
    //     }
    //     else
    //     {
    //         $response["status"]=false;
    //         $response["message"]="Unable to fetch this biller!";
    //     }
    //     echo json_encode($response);
    // }



    // function fetch(){
    //     echo "hello"; die;
    //     $request=getArrayFromRawData();

    //     $request["uniqueid"]=$this->session->userdata("uniqueid");
    //     $request["usertype"]=$this->session->userdata("user_type");
    //     $request["serviceid"]=$this->serviceid;

    //     $url = APIURL.('webapi/bbps/bill_fetch');
    //     $response = curlApis( $url,'POST',$request );
    //     echo json_encode($response);
    // }

    // function pay()
    // {
    //     $request=getArrayFromRawData();
        
    //     $request["userid"]=$this->session->userdata("id");
    //     $request["uniqueid"]=$this->session->userdata("uniqueid");
    //     $request["usertype"]=$this->session->userdata("user_type");
    //     $request["serviceid"]=$this->serviceid;  
        
    //     echo "<pre>"; print_r($request); die;
        
    //     $url = APIURL.('webapi/bbps/bill_pay');
    //     $response = curlApis( $url,'POST',$request,null, 100 );
        
    //     echo json_encode($response);
    // }

      function fetch(){
          
        $data = array(
            'api_token' => 'eVHGnU59YQEo1Rvwa7cNyF9pTRNeGQQ5Lbi0EWQHW5uso4rnKLkttCKfky6w',
            'provider' => $this->input->post('operator'),
            'number' => $this->input->post('number'),
            'userId'    => '12816',
            'secretKey' => '12816yvRbT7rBYqwPAUQqVdNb0P4xbs8cVBY6S95PqDSVdC7bcihRyEkFxqoCh2cM',
        );
        
        $response = $this->curl->simple_post('https://partners.a2zsuvidhaa.com/api/v3/fetch/bill-details',$data);
        
        
        
        // $request=getArrayFromRawData();

        // $request["uniqueid"]=$this->session->userdata("uniqueid");
        // $request["usertype"]=$this->session->userdata("user_type");
        // $request["serviceid"]=$this->serviceid;

        // $url = APIURL.('webapi/bbps/bill_fetch');
        // $response = curlApis( 'https://partners.a2zsuvidhaa.com/api/v1/recharge/bill-recharge','POST',$data );
        // echo "<pre>"; print_r($response); die;
        echo json_encode($response).'^'.$this->input->post('number');
    }
    

    function fetch_bbps(){
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
        
        $url = APIURL.('webapi/a2z/bill_pay');
        $response = curlApis( $url,'POST',$request,null, 100 );
        
        // echo "<pre>"; print_r($response); die;
        
    //   $response = array("status"=> 'SUCCESS FULLY SUBMITTED', 
    //                     "message"=> '', 
    //                     "txnId"=> 'TXN11111111', 
    //                     "txnTime"=> "2020-10-15 15:15:15", 
    //                     "operator_ref"=> "", 
    //                     "billerName"=>"TEST", 
    //                     "providerName"=> "JODHPUR VIDUT VITARAN", 
    //                     "amount"=> 10, 
    //                     "statusId"=> 24
    //                     );
        
        echo json_encode($response);
    }

}?>