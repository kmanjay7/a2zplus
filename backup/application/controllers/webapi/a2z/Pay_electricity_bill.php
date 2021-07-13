<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Pay_electricity_bill extends CI_Controller{
    var $pagename;
    var $folder;
    private $serviceid=4;

    public function __construct()
    {
        parent::__construct();
        $this->pagename = 'electricity';
        $this->folder = $this->uri->segment(1);
        $this->load->model("general_model");
        $this->load->helper('bbps');
        $this->load->library('curl');
    }
    

    public function index()
    { 
        
        header("Pragma: no-cache");
        header("Cache-Control: no-cache");
        header("Expires: 0");
        header("Content-Type: application/json");

        $response = array();
        $data = array();
            if( ($_SERVER['REQUEST_METHOD'] == 'POST') )
            {

                $longitude=$this->input->post('longitude');
                $latitude=$this->input->post('latitude');
                $amount=$this->input->post('amount');
                $customerid=$this->input->post('customerid');
                $billno=$this->input->post('billno');
                $consumername=$this->input->post('consumername');
                $duedate=$this->input->post('duedate');
                $operator=$this->input->post('operator');
                $number=$this->input->post('customerid');
                $mobile_no=$this->input->post('mobile_no');
                $userid=$this->input->post('userid');
                $uniqueid=$this->input->post('uniqueid');
                $usertype='AGENT';
                $serviceid=4;


                $where['id'] = $userid;
                $where['uniqueid'] = $uniqueid;
                $where['user_type'] = $usertype; 
                $check = $this->c_model->countitem('dt_users',$where);
                /*CHECK EXISTING USER RECORD END*/
                if($check != 1){
                    $response['status'] = FALSE;
                    $response['message'] = "User not exists!"; 
                    header("Content-Type:application/json");
                    echo json_encode( $response );
                    exit;
                }  

                
                if(!empty($latitude) && !empty($latitude) && !empty($amount) && !empty($customerid) && !empty($billno) && !empty($consumername) && !empty($duedate) && !empty($operator) && !empty($mobile_no) && !empty($userid) && !empty($uniqueid))
                {
                  
                    $request=[];

                    $request['longitude']=$longitude;
                    $request['latitude']=$latitude;
                    $request['amount']=$amount;
                    $request['customerid']=$customerid;
                    $request['billno']=$billno;
                    $request['consumername']=$consumername;
                    $request['duedate']=$duedate;
                    $request['operator']=$operator;
                    $request['number']=$number;
                    $request['mobile_no']=$mobile_no;
                    $request['userid']=$userid;
                    $request['uniqueid']=$uniqueid;
                    $request['usertype']=$usertype;
                    $request['serviceid']=$serviceid;

                    $url = APIURL.('webapi/a2z/bill_pay');
                    $apires = curlApis( $url,'POST',$request,null, 100 );
                    
                    if(!empty($apires))
                    {
                        echo json_encode($apires); exit;                 
                    }else
                    {
                        $response['status']= FALSE;
                        $response['message']= "Some error occured, Please try again.";
                    }
                }else
                {
                    $response['status']= FALSE;
                    $response['message']= 'Please fill the required fields!';
                }
            }else
            { 
                $response['status']= FALSE;
                $response['message']= 'Bad request!'; 
            }

        echo json_encode( $response ); exit;
    }  


}?>