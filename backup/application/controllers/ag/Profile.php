<?php defined('BASEPATH') OR exit('No direct script access allowed');



class Profile extends CI_Controller{
	public function __construct(){
		parent::__construct();
         $this->load->library('session');  
               $this->load->model('Common_model','dg_model'); 
               $this->load->library('pagination');
               agsession_check();
		}



    public function index(){ 
        $userid = $this->session->userdata('id');
        $data['title'] = 'Admin Profile Update';
        $adminData = $this->dg_model->getSingle('users',array('id'=>$userid),'*');
        $data['userd'] = $adminData;
        $data['state'] = $this->dg_model->getAll('dt_state','statename ASC',array('status'=>'yes'));
        $data['city'] = $this->dg_model->getAll('dt_city','cityname ASC',array('status'=>'yes','stateid'=>$adminData['stateid']));
        $data["kyc_status"]=$this->dg_model->getSingle("users", ["id"=>$userid], "kyc_status");
        agview('profile-detail',$data);
	}

    

    public function saveupdate(){
             $userid = $this->session->userdata('id');
             
             $kyc_status=$this->dg_model->getSingle("users", ["id"=>$userid], "kyc_status");
             if($kyc_status=="yes")
             {
                $this->session->set_flashdata('error','Approved KYC can not be updated!');
                redirect(ADMINURL.'ag/profile');  
             }
             
             $pdata = $this->input->post();
             $saveup['ownername'] = $pdata['owner_name'];
             $saveup['firmname'] = $pdata['firm_name'];
             
             $saveup['pancard'] = $pdata['pan_card'];
             $saveup['aadharno'] = $pdata['adhar_card'];
             
             $saveup['mobileno'] = $pdata['mobileno'];
             $saveup['alt_mobileno'] = $pdata['al_number'];
             $saveup['emailid'] = $pdata['email'];
             $saveup['pincode'] = $pdata['pin_code'];
             $saveup['address'] = $pdata['address'];
             $saveup['stateid'] = $pdata['stateid'];
             $saveup['cityid'] = $pdata['cityid'];
             $update = $this->dg_model->saveupdate('users', $saveup, null , array('id'=>$userid), null ); 
             if(!empty($update)){
              $this->session->set_flashdata('success',$pdata['owner_name'].' Your Profile Updated Successfully!!!!');
                 redirect(ADMINURL.'ag/profile');      
             }else{
               $this->session->set_flashdata('error','Something Went Wrong!');
                 redirect(ADMINURL.'ag/profile');     
             }
        }

}