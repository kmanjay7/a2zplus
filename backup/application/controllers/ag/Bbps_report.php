<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Bbps_report extends CI_Controller{
    var $panename;
    var $folder;
    var $serviceid;
public function __construct(){
        parent::__construct();
            if($this->input->get('apptype')!='app'){
                $this->load->library('session');
                $this->load->library('pagination');     
                    agsession_check();
                    sessionunique();  
            }
                $this->pagename = 'bbps_report';
                $this->folder = 'ag';
                $this->serviceid = $this->uri->segment(4);
        }
 

public function index(){ 
 
    $apptype = 'web';
    if($this->input->get('apptype')){ 
      $apptype = 'app'; 
    }

        if(!$this->serviceid){
            redirect( ADMINURL.$this->folder.'/dashboard');
        }

        $data['title'] = $this->servicetype($this->serviceid);
        $data['folder'] = $this->folder;
        $data['pagename'] = $this->pagename;
        $data['posturl'] = $this->folder.'/'.$this->pagename.'/index/'.$this->serviceid; 
        $data['serviceid'] = $this->serviceid; 


        $data['from_date'] = '';
        $data['to_date'] = '';
        $data['transaction'] = '';
        $data['filterby'] = '';
        $data['fvalue'] = ''; 
        $data['trans_list'] = [];
 
        $data['success_amt'] = 0;
        $data['t_comi'] = 0;
        $data['t_surcharge'] = 0;
        $data['total_tds'] = 0; 

        if($this->input->get()){
            $getinput = $this->input->get();
            // $data['from_date'] = $getinput['from_date'];
            // $data['to_date'] = $getinput['to_date'];
            // $data['transaction'] = $getinput['transaction'];
            // $data['filterby'] = $getinput['filterby'];
            // $data['fvalue'] = $getinput['fvalue'];

            $data['from_date'] = (isset($getinput['from_date'])) ? $getinput['from_date'] : '';
            $data['to_date'] = (isset($getinput['to_date'])) ? $getinput['to_date'] : '';
            $data['transaction'] = (isset($getinput['transaction'])) ? $getinput['transaction'] : '';
            $data['filterby'] = (isset($getinput['filterby'])) ? $getinput['filterby'] : '';
            $data['fvalue'] = (isset($getinput['fvalue'])) ? $getinput['fvalue'] : '';


            $getinput['from_date'] = (isset($getinput['from_date'])) ? $getinput['from_date'] : '';
            $getinput['to_date'] = (isset($getinput['to_date'])) ? $getinput['to_date'] : '';
            $getinput['transaction'] = (isset($getinput['transaction'])) ? $getinput['transaction'] : '';
            $getinput['filterby'] = (isset($getinput['filterby'])) ? $getinput['filterby'] : '';
            $getinput['fvalue'] = (isset($getinput['fvalue'])) ? $getinput['fvalue'] : '';

        }else{
            $getinput['from_date'] = date('Y-m-d');
            $getinput['to_date'] = date('Y-m-d');
            $getinput['transaction'] = '';
            $getinput['filterby'] = '';
            $getinput['fvalue'] = ''; 
        }    
         


            $getinput['serviceid'] = $this->serviceid; 

            if($apptype=='web'){
                $getinput['userid'] = getloggeduserdata('id');
                $userIdGet=md5(getloggeduserdata('id'));
            }else{
                $getinput['md5(userid)'] = $this->input->get('id');
                $userIdGet=$this->input->get('id');
            }

            $fetch = $this->get_records($getinput);  
            if(!empty($fetch['status'])){

                if($apptype=='web')
                {
                    $data['trans_list'] = $fetch['response'];
                    /********* count transactions script start here *******/ 
                    foreach ($data['trans_list'] as $key => $lvalue) {
                         if($lvalue['status']=='SUCCESS'){ 
                            $data['success_amt'] += $lvalue['amount'];
                            $data['t_comi'] += ($lvalue['ag_comi'] + $lvalue['ag_tds']);
                           //$data['t_surcharge'] = $lvalue['sur_charge'];
                            $data['total_tds'] += $lvalue['ag_tds'];

                            
                         }   
                    }  
                    /********* count transactions script end here *******/
                }else{

                    $responsearr=[];
                    foreach ($fetch['response'] as $rvalue) {
                        $newArr['reqid']=$rvalue['reqid'];
                        $newArr['status']=$rvalue['status'];
                        $newArr['amount']=$rvalue['amount'];
                        $newArr['cust_account_no']=$rvalue['cust_account_no'];
                        $newArr['field2']=$rvalue['field2'];
                        $newArr['apirefid']=$rvalue['apirefid'];
                        $newArr['op_transaction_id']=$rvalue['op_transaction_id'];
                        $newArr['operatorname']=$rvalue['operatorname'];
                        $newArr['add_date']=$rvalue['add_date'];
                        $newArr['ag_comi']=$rvalue['ag_comi'];
                        $newArr['ag_tds']=$rvalue['ag_tds'];
                        $newArr['status_update']=$rvalue['status_update'];
                        $newArr['image']=$rvalue['image'];
                        $newArr['complaint']=$rvalue['complaint'];
                        $newArr['id']=$rvalue['id'];
                        $newArr['printurl']=(string) ADMINURL.'ag/'.($this->serviceid==14 ? 'bbps_reciept':'bbps_reciept').'?utp='.md5($rvalue['reqid']).'&apptype=app&id='.$userIdGet;

                        array_push($responsearr, $newArr);
                         //echo '<pre>'; print_r($newArr); exit;
                    } 

                    if($apptype=='app')
                    {

                        if(empty($responsearr))
                        {
                            $appresponse['status'] = false;
                            $appresponse['message'] = 'No records found.'; 
                            header("Content-Type:application/json");
                            echo json_encode($appresponse);
                            exit;
                        }else{
                             $appresponse['status'] = true;
                            $appresponse['trans_list'] =$responsearr;
                            $appresponse['message'] = 'Records fetched successfully.'; 
                            header("Content-Type:application/json");
                            echo json_encode($appresponse);
                            exit;
                        }
                       
                    }

                    
                }
            }else{
                if($apptype=='app')
                {

                    if(empty($responsearr))
                    {
                        $appresponse['status'] = false;
                        $appresponse['message'] = 'No records found.'; 
                        header("Content-Type:application/json");
                        echo json_encode($appresponse);
                        exit;
                    }                   
                }
            }

          


        agview('bbps_report',$data );
        

}



 public function get_records($getinput = null){ 

            $getinput = !empty($getinput)?$getinput:$this->input->post();
            $response = [];  

            if(empty($getinput)){
                $response['status'] = false;
                $response['message'] = 'Select Search inputs'; 
                header("Content-Type:application/json");
                echo json_encode($response);
                exit;
            }

            if(isset($getinput['userid']))
            {
                $where['a.user_id'] = $getinput['userid'];
            }

            if(isset($getinput['md5(userid)']))
            {
                $where['md5(a.user_id)'] = $getinput['md5(userid)'];
            }
            

            $where['a.serviceid'] = $getinput['serviceid'];


    $where['DATE(a.add_date)'] = date('Y-m-d');
 if(!$getinput['filterby'] && !$getinput['fvalue']){ 
    $where['DATE(a.add_date)'] = date('Y-m-d');
 }
 if($getinput['from_date'] && $getinput['to_date'] ){ 
    unset($where['DATE(a.add_date)']);
    $where['DATE(a.add_date) >='] = date('Y-m-d',strtotime($getinput['from_date'])); 
    $where['DATE(a.add_date) <='] = date('Y-m-d',strtotime($getinput['to_date'])); 
 }

 

             if( $getinput['transaction'] == 'success' ){ 
                $where['a.status'] = 'SUCCESS';

             }else if( $getinput['transaction'] == 'success-submit' ){ 
                $where['a.status'] = 'SUCCESSFULLY SUBMITTED';

             }else if( $getinput['transaction'] == 'failed' ){ 
                //$inkey = 'a.status';
                // $invalue = 'FAILED,FAILURE';
                $where['a.status'] = 'FAILURE';

             }else if( $getinput['transaction'] == 'pending' ){
               // $inkey = 'a.status';
                // $invalue = 'PENDING,PROCESSED';
                $where['a.status'] = 'PENDING';

             } else if( $getinput['transaction'] == 'processed' ){
                $where['a.status'] = 'PROCESSED';

             }  


             if( $getinput['filterby']=='orderid' && $getinput['fvalue'] ){ 
                $where['a.reqid'] = $getinput['fvalue'];
             
             }else if( $getinput['filterby']=='txnid' && $getinput['fvalue'] ){ 
                $where['a.op_transaction_id'] = $getinput['fvalue'];

             }else if( $getinput['filterby']=='mob' && $getinput['fvalue'] ){ 
                $where['a.mobileno'] = $getinput['fvalue'];

             }else if( $getinput['filterby']=='custid' && $getinput['fvalue'] ){ 
                $where['a.cust_account_no'] = $getinput['fvalue'];

             }   


    

/********* filter script start here  *******/ 
              
               

            $select = 'a.reqid, a.status, a.amount, a.mobileno,a.cust_account_no, a.field2,a.apirefid,a.op_transaction_id, a.operatorname, a.add_date, a.ag_comi, a.ag_tds, a.status_update, b.image, a.operatorname,a.status,a.status_update,a.mobileno ,a.complaint,a.id,ra.apiname' ;

            $from = 'dt_rech_history as a'; 

            $join[0]['table'] = 'dt_operators as b';
            $join[0]['joinon'] = 'a.operatorid = b.id';
            $join[0]['jointype'] = 'LEFT'; 

            $join[1]['table'] = 'dt_recharge_api as ra';
            $join[1]['joinon'] = 'a.apiid = ra.id' ; 
            $join[1]['jointype'] = 'LEFT';

 
            $groupby = null; 
            $orderby = 'a.id DESC' ; 
            $getorcount = 'get';
            $limit = null; 
            $offset = null;
            $inkey = null;
            $invalue = null;

            $data_arr = $this->c_model->joinmultiple( $select, $where, $from, $join, $groupby ,$orderby, $limit, $offset, $getorcount, $inkey, $invalue );
            
            $response['status'] = false;
            $response['message'] = 'No Records!';
            if(!empty( $data_arr)){
            $response['status'] = true;
            $response['message'] = 'success';
            $response['response'] = $data_arr;
            } 
       // header("Content-Type:application/json");
        //echo json_encode($response);
            return $response;

 }  


 public function servicetype($id){
   $out = '';
   switch($id){
    case 9:
    $out = 'Postpaid Recharge Reports';
    break;
    case 12:
    $out = 'Fastag Payment Reports';
    break;
    case 13:
    $out = 'Loan Payment Reports';
    break;
    case 11:
    $out = 'Gas Payment Reports';
    break;
    case 4:
    $out = 'Electricity Bill Reports';
    break;
    case 10:
    $out = 'Water Bill Reports';
    break;
    case 14:
    $out = 'Electricity Bill Reports';
    break;
   }
   return $out;
}     



}
?>