<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Update_kyc extends CI_Controller{
	public function __construct(){
		parent::__construct(); 
    $this->load->library('session');
               $this->load->model('Common_model','dg_model'); 
               $this->load->library('pagination');
               $this->load->helper('security');
               adsession_check();
		}
                
    

public function index(){

                $data['title'] = 'Complete KYC';

                $data['bank_name'] = ''; 
                $data['viewbankdiv'] = 'yes'; 

                $id = $this->input->get('id');   
                $adminData = $this->dg_model->getSingle('users',array('md5(id)'=>$id),'*');
                
                $data['bank_name'] = $adminData['bank_name'];

                if($adminData['acct_holder'] && $adminData['acct_no'] && $adminData['acct_type'] && $adminData['bank_name'] && $adminData['ifsc_code']){ 
                     $data['viewbankdiv'] = 'no';
                }

$data['Applicant'] = $this->dg_model->getSingle('uploads',array('documenttype'=>'Applicant Photo','md5(tableid)'=>$id),'documentorimage');
$data['Shop'] = $this->dg_model->getSingle('uploads',array('documenttype'=>'Shop Photo','md5(tableid)'=>$id),'documentorimage');
$data['Pan'] = $this->dg_model->getSingle('uploads',array('documenttype'=>'Pan Card','md5(tableid)'=>$id),'documentorimage');
$data['Educational'] = $this->dg_model->getSingle('uploads',array('documenttype'=>'Educational Certificate','md5(tableid)'=>$id),'documentorimage');
$data['Bank'] = $this->dg_model->getSingle('uploads',array('documenttype'=>'Bank PB/CL','md5(tableid)'=>$id),'documentorimage');
$data['Aadhaar'] = $this->dg_model->getSingle('uploads',array('documenttype'=>'Aadhaar Card','md5(tableid)'=>$id),'documentorimage');
$data['Firm'] = $this->dg_model->getSingle('uploads',array('documenttype'=>'Firm/Shop/Outlet Registration Certificate','md5(tableid)'=>$id),'documentorimage');
                
                
                
                
$data['kycdata'] = $adminData;
$data['state'] = $this->dg_model->getAll('dt_state','statename ASC',array('status'=>'yes'));
$data['city'] = $this->dg_model->getAll('dt_city','cityname ASC',array('status'=>'yes','stateid'=>$adminData['stateid']));


                   adview('kyc',$data);

}





    public function saveupdatekyc(){
                   $userid= $this->input->get('id');
                   $id    = $this->session->userdata('id');
                   $pdata = xss_clean($this->input->post()); 

                   if(isset($pdata['holder_name'])){
                    $saveup['acct_holder']    = $pdata['holder_name'];
                   }

                   if(isset($pdata['ac_no'])){
                     $saveup['acct_no']        = $pdata['ac_no'];
                   }

                   if(isset($pdata['acctt_ype'])){
                     $saveup['acct_type']        = $pdata['acctt_ype'];
                   }

                   if(isset($pdata['bank_name'])){
                     $saveup['bank_name']        = $pdata['bank_name'];
                   }
                   
                   if(isset($pdata['ifsc_code'])){
                     $saveup['ifsc_code']      = $pdata['ifsc_code'];
                   }

                   if(isset($pdata['branch_name'])){
                     $saveup['branchname']      = $pdata['branch_name'];
                   }
 

                   $saveup['kyc_updated_by'] = $id;
                   $saveup['kyc_status'] = 'pending';
 
                   
              $query = $this->dg_model->saveupdate('users', $saveup,null,array('md5(id)'=>$userid));


              if(!empty($query)){
              $this->session->set_flashdata('success','Done Successfully!!!!');
              redirect(ADMINURL.'ad/update_kyc?id='.$userid);
              }else{
              $this->session->set_flashdata('error','Something Went Wrong!!');
              redirect(ADMINURL.'ad/update_kyc?id='.$userid);      
              }
                   
    }
}
?>