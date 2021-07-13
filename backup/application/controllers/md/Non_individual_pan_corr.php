<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Non_individual_pan_corr extends CI_Controller{
	
	public function __construct(){
		parent::__construct(); 
        $this->load->library('session');
               $this->load->model('Common_model','dg_model'); 
               $this->load->helper('security');
               mdsession_check();
		}
	
	public function index(){ 
		
		$data['title'] = 'Non Individual Pancard ';
                $data['city'] = $this->dg_model->getAll('city','cityname ASC',array('status'=>'yes'));
                $data['state'] = $this->dg_model->getAll('state','statename ASC',array('status'=>'yes'));
                $data['pancardtype'] = $this->dg_model->getAll('pancardtype','pancardtype ASC',array('status'=>'yes','id !=' => '1'));
                $this->load->view('pancardajaxfile');
                mdview('pancard-non-individual-correction',$data);
	}
        public function saveupdate(){
            $sessionid  = $this->session->userdata('sessionid');
            $pdata      = xss_clean($this->input->post());
            $saveup['addby_userid']     = $this->session->userdata('id');
            $saveup['pancardno']        = $pdata['pancardno'];
            $saveup['applicant_type']   = $pdata['applicant_type'];
            $saveup['ao_cityarea']      = $pdata['ao_cityarea'];
            $saveup['add_date']         = $pdata['add_date'];
            
            $saveup['ao_ward']          = $pdata['ao_ward'];
            $saveup['ao_areacode']      = $pdata['ao_areacode'];
            $saveup['ao_type']          = $pdata['ao_type'];
            $saveup['ao_rangecode']     = $pdata['ao_rangecode'];
            $saveup['ao_no']            = $pdata['ao_no'];
            
            $saveup['name_title']       = $pdata['name_title'];
            $saveup['first_name']       = $pdata['first_name'];
            $saveup['name_on_pancard']  = $pdata['name_on_pancard'];
            
            $saveup['address_comunication'] = $pdata['address_comunication'];
            $saveup['pan_dispatch_stateid'] = $pdata['pan_dispatch_stateid'];
            $saveup['c_flat_door_block']    = $pdata['c_flat_door_block'];
            $saveup['c_build_vill_permis']  = $pdata['c_build_vill_permis'];
            $saveup['c_road_street_post']   = $pdata['c_road_street_post'];
            $saveup['c_area_subdevision']   = $pdata['c_area_subdevision'];
            
            $saveup['c_city_district']  = !empty($pdata['c_city_district']) ? $pdata['c_city_district'] : '';
            $saveup['c_pincode']        = !empty($pdata['c_pincode']) ? $pdata['c_pincode'] : '';
            $saveup['c_stateid_ut']     = $pdata['c_stateid_ut'];
            $saveup['c_country']        = $pdata['c_country'];
            $saveup['contact']          = $pdata['contact'];
            $saveup['email']            = $pdata['email'];
            $saveup['aadhar_no']        = $pdata['aadhar_no'];
            $saveup['name_on_aadhar']   = $pdata['name_on_aadhar'];
            
            $saveup['id_proof']         = $pdata['id_proof'];
            $saveup['address_proof']    = $pdata['address_proof'];
            $saveup['dob_proof']        = $pdata['dob_proof'];
            $saveup['proccessing_fee']  = $pdata['proccessing_fee'];
            $saveup['category']         = 'correction';
            if(!empty($pdata['ao_ward']) && !empty($sessionid) && !empty($pdata['contact']) && !empty($pdata['email'])){
            $saveupdate = $this->dg_model->saveupdate('pancard', $saveup);
            $lastid = $this->db->insert_id();
            if(!empty($lastid)){
                $isave['tableid'] = $lastid;
                $isave['usertype'] =  $pdata['applicant_type'];
                $isave['documenttype'] = '';
                $isave['uploaddate'] = date('Y-m-d H:i:s');
                $isave['add_by'] = '';
                $isave['verifystatus'] = 'no';
                $isave['status'] = 'yes';
                $saveupdate = $this->dg_model->saveupdate('uploads', $isave,null, array('tableid'=>$sessionid)); 
                $this->session->set_flashdata('success','Done Successfully!!!!');
              redirect(ADMINURL.'md/non_individual_pan_corr');  
            }else{
               $this->session->set_flashdata('error','Something Went Wrong!');
                 redirect(ADMINURL.'md/non_individual_pan_corr');   
            }} else {
              $this->session->set_flashdata('warning','all required fields are mandatory!!!!');
              redirect(ADMINURL.'md/non_individual_pan_corr');  
           }
            
        }
}
?>