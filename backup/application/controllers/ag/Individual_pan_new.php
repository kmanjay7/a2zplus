<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Individual_pan_new extends CI_Controller{
	var $folder;
	public function __construct(){
		parent::__construct(); 
        $this->load->library('session'); 
               $this->load->model('Common_model','dg_model'); 
               $this->load->helper('security');
                agsession_check();
                $this->folder = 'ag';
		}
	
	public function index(){ 
	        
		$data['title'] = 'Individual Pancard ';
                $data['city'] = $this->dg_model->getAll('city','cityname ASC',array('status'=>'yes'));
                $data['state'] = $this->dg_model->getAll('state','statename ASC',array('status'=>'yes'));
                $data['identity'] = $this->dg_model->getAll('address_proof','content ASC',array('status'=>'yes','prooftype'=>'Identity','pancardtype'=>1));
                $data['address'] = $this->dg_model->getAll('address_proof','content ASC',array('status'=>'yes','prooftype'=>'Address','pancardtype'=>1));
                $data['dob'] = $this->dg_model->getAll('address_proof','content ASC',array('status'=>'yes','prooftype'=>'DOB','pancardtype'=>1));
		$this->load->view('pancardajaxfile');
                agview('pancard-individual-new',$data);
	}




        public function saveupdate(){
            $sessionid  = $this->session->userdata('sessionid');
            $pdata      = xss_clean($this->input->post());
            $saveup['addby_userid']     = $this->session->userdata('id');
            $saveup['pancardno']        = isset($pdata['pancardno'])?$pdata['pancardno']:NULL;
            $saveup['applicant_type']   = $pdata['applicant_type'];
            $saveup['ao_cityarea']      = $pdata['ao_cityarea'];
            $saveup['add_date']         = $pdata['add_date'];
            $saveup['ao_ward']          = $pdata['ao_ward'];
            $saveup['ao_areacode']      = $pdata['ao_areacode'];
            $saveup['ao_type']          = $pdata['ao_type'];
            $saveup['ao_rangecode']     = $pdata['ao_rangecode'];
            $saveup['ao_no']            = $pdata['ao_no'];
            $saveup['name_title']       = $pdata['name_title'];
            $saveup['last_name']        = $pdata['last_name'];
            $saveup['first_name']       = $pdata['first_name'];
            $saveup['middle_name']      = $pdata['middle_name'];
            $saveup['name_on_pancard']  = $pdata['name_on_pancard'];
            
            $date = str_replace('/"', '-', $pdata['dob_incorporate_year']);
            $saveup['dob_incorporate_year'] = $date;
            
            $saveup['parent_type']      = $pdata['parent_type'];
            $saveup['par_title']        = $pdata['par_title'];
            $saveup['par_last_name']    = $pdata['par_last_name'];
            $saveup['par_first_name']   = $pdata['par_first_name'];
            $saveup['par_middle_name']  = $pdata['par_middle_name'];
            
            $saveup['gender']           = $pdata['gender'];
            
            $saveup['pan_dispatch_stateid'] = $pdata['pan_dispatch_stateid'];
            $saveup['address_comunication'] = $pdata['address_comunication'];
            $saveup['c_flat_door_block']    = $pdata['c_flat_door_block'];
            $saveup['c_build_vill_permis']  = $pdata['c_build_vill_permis'];
            $saveup['c_road_street_post']   = $pdata['c_road_street_post'];
            $saveup['c_area_subdevision']   = $pdata['c_area_subdevision'];
            $saveup['c_city_district']      = $pdata['c_city_district'];
            $saveup['c_pincode']            = $pdata['c_pincode'];
            $saveup['c_stateid_ut']         = $pdata['c_stateid_ut'];
            $saveup['c_country']            = $pdata['c_country'];
            $saveup['contact']              = $pdata['contact'];
            $saveup['email']                = $pdata['email'];
            $saveup['aadhar_no']            = $pdata['aadhar_no'];
            $saveup['name_on_aadhar']       = $pdata['name_on_aadhar'];
            $saveup['rep_title']            = $pdata['rep_title'];
            $saveup['rep_first_name']       = $pdata['rep_first_name'];
            
            $saveup['rep_middle_name']      = $pdata['rep_middle_name'];
            $saveup['rep_last_name']        = $pdata['rep_last_name'];
            $saveup['rep_flat_door_block']  = $pdata['rep_flat_door_block'];
            $saveup['rep_build_vill_permis'] = $pdata['rep_build_vill_permis'];
            $saveup['rep_road_street_post'] = $pdata['rep_road_street_post'];
            $saveup['rep_area_subdevision'] = $pdata['rep_area_subdevision'];
            $saveup['rep_district_town']    = $pdata['rep_district_town'];
            $saveup['rep_pin_zipcode']      = $pdata['rep_pin_zipcode'];
            $saveup['rep_stateid_ut']       = $pdata['rep_stateid_ut'];
            $saveup['rep_country']          = $pdata['rep_country'];
            $saveup['id_proof']             = $pdata['id_proof'];
            $saveup['address_proof']        = $pdata['address_proof'];
            $saveup['dob_proof']            = $pdata['dob_proof'];
            $saveup['name_on_aadhar']       = $pdata['name_on_aadhar'];
            $saveup['rep_title']            = $pdata['rep_title'];
            $saveup['rep_first_name']       = $pdata['rep_first_name'];
            $saveup['proccessing_fee']      = $pdata['proccessing_fee'];
            $saveup['category']             = 'new';
            
            
           if(!empty($pdata['ao_ward']) && !empty($sessionid) && !empty($pdata['contact']) && !empty($pdata['email']) && !empty($pdata['first_name'])){
            $saveupdate = $this->dg_model->saveupdate('pancard', $saveup);
            $lastid = $this->db->insert_id();
            

            if(!empty($lastid)){

        /* check wallet for this registration start */ 
        $wtsave['userid'] = $this->session->userdata('id');
        $wtsave['usertype'] = $this->session->userdata('user_type');
        $wtsave['uniqueid'] = $this->session->userdata('uniqueid'); 
        $wtsave['paymode'] = 'wallet';
        $wtsave['transctionid'] = time().rand(100,300);
        $wtsave['credit_debit'] = 'debit';
        $wtsave['upiid'] = NULL;
        $wtsave['bankname'] = NULL; 
        $wtsave['remark'] = 'Pancard Register';
        $wtsave['status'] = 'success'; 
        $wtsave['amount'] = trim($pdata['proccessing_fee']); 
        $wtsave['subject'] = 'pancard';
        $wtsave['addby'] = $this->session->userdata('id'); 
        $wtsave['orderid'] = $lastid;

        $postapiurl = base_url('webapi/wallet/Creditdebit');
        $upwtwhere['dts'] = $wtsave;  
        $header = array('auth: Access-Token='.WALLETOKEN );
        $upwt = $wtsave['amount'] ? curlApis($postapiurl,'POST', $upwtwhere,$header ):array();
        if(isset($upwt['status']) && !$upwt['status']){
        $this->c_model->delete('pancard',array('id'=>$lastid));
        $this->session->set_flashdata('success',$upwt['message']);
        redirect(ADMINURL.$this->folder.'/individual_pan');
        }
        /* check wallet for this registration end */ 

                $isave['tableid']       = $lastid;
                $isave['usertype']      =  $pdata['applicant_type'];
                $isave['documenttype']  = '';
                $isave['uploaddate']    = date('Y-m-d H:i:s');
                $isave['add_by']        = $this->session->userdata('id');
                $isave['verifystatus']  = 'no';
                $isave['status']        = 'yes';
                $saveupdate = $this->dg_model->saveupdate('uploads', $isave,null, array('tableid'=>$sessionid));  

              $this->session->set_flashdata('success','Done Successfully!!!!');
              redirect(ADMINURL.$this->folder.'/individual_pan');   
            }else{
               $this->session->set_flashdata('error','Something Went Wrong!');

              redirect(ADMINURL.$this->folder.'/individual_pan');     
            }
        }else{
          $this->session->set_flashdata('warning','all required fields are mandatory!!!!');
          redirect(ADMINURL.$this->folder.'/individual_pan');  
        }
    }






}
?>