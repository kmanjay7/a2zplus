<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Individual_pan extends CI_Controller{
	  var $folder;
    var $pagename;
	public function __construct(){
		parent::__construct(); 
        $this->load->library('session'); 
               $this->load->model('Common_model','dg_model'); 
               $this->load->helper('security');
                agsession_check();
                $this->folder = 'ag';
                $this->pagename = 'Individual_pan';
		}
	
	public function index(){ 


	        
		        $data['title'] = 'Individual Pancard ';
                $data['folder'] = $this->folder;
                $data['pagename'] = $this->pagename;


                $data['city'] = $this->dg_model->getAll('city','cityname ASC',array('status'=>'yes'));
                $data['state'] = $this->dg_model->getAll('state','statename ASC',array('status'=>'yes'));
               // $data['identity'] = $this->dg_model->getAll('address_proof','content ASC',array('status'=>'yes','prooftype'=>'Identity','pancardtype'=>1));
               // $data['address'] = $this->dg_model->getAll('address_proof','content ASC',array('status'=>'yes','prooftype'=>'Address','pancardtype'=>1));
               // $data['dob'] = $this->dg_model->getAll('address_proof','content ASC',array('status'=>'yes','prooftype'=>'DOB','pancardtype'=>1)); 

$comi_array = $this->c_model->getSingle('dt_set_commission',['operatorid'=>'24','serviceid'=>'8','user_type'=>'AGENT','scheme_type'=>$this->session->userdata('scheme_type') ],'surcharge_fixed,surcharge_percent');
$amount = false;
if( !empty($comi_array)){
$amount = $comi_array['surcharge_fixed'];
}

               $data['id'] = $this->input->get('id')?$this->input->get('id'):'';
               $data['odr'] = $this->input->get('odr')?$this->input->get('odr'):'';
               $data['tab'] = '';
               $id = '';

               $data['tab_1'] = true;
               $data['tab_1_con'] = true;
               $data['tab_2'] = false;
               $data['tab_2_con'] = false; 
               


               $data['uploadlist'] = array();
               $data['datalist'] = array();

               $data['add_date'] = date('d/m/Y');
               $data['applicant_type'] = 1;
               $data['name_title'] = '';
               $data['first_name'] = '';
               $data['middle_name'] = '';
               $data['last_name'] = '';
               $data['name_on_pancard'] = '';
               $data['parent_type'] = 'father';
               $data['par_title'] = '';
               $data['par_first_name'] = '';
               $data['par_middle_name'] = '';
               $data['par_last_name'] = '';
               $data['contact'] = '';
               $data['email'] = '';
               $data['pan_dispatch_stateid'] = '';
               $data['address_comunication'] = '';
               $data['name_on_aadhar'] = '';
               $data['aadhar_no'] = '';
               $data['dob_incorporate_year'] = '';
               $data['gender'] = '';
               $data['is_minor'] = '';
               $data['rep_title'] = '';
               $data['rep_first_name'] = '';
               $data['rep_middle_name'] = '';
               $data['rep_last_name'] = '';
               $data['rep_flat_door_block'] = '';
               $data['rep_build_vill_permis'] = '';
               $data['rep_road_street_post'] = '';
               $data['rep_area_subdevision'] = '';
               $data['rep_stateid_ut'] = '';
               $data['rep_country'] = 'India';
               $data['rep_district_town'] = '';
               $data['rep_pin_zipcode'] = '';
               $data['id_proof'] = '';
               $data['address_proof'] = '';
               $data['dob_proof'] = '';
               $data['c_flat_door_block'] = '';
               $data['c_build_vill_permis'] = '';
               $data['c_road_street_post'] = '';
               $data['c_area_subdevision'] = '';
               $data['c_city_district'] = '';
               $data['c_stateid_ut'] = '';
               $data['c_pincode'] = '';
               $data['c_country'] = '';
               $data['countrycode'] = '';
               $data['approvaldate'] = '';
               $data['status'] = '';
               $data['proccessing_fee'] = '';
               $data['ao_cityarea'] = '';
               $data['ao_ward'] = '';
               $data['ao_areacode'] = '';
               
               $data['ao_type'] = '';
               $data['ao_rangecode'] = '';
               $data['ao_no'] = '';
               $data['pancardno'] = '';
               $data['category'] = '';
               $data['income_soure'] = '';
               $data['verification_place'] = '';
               $data['ackno'] = '';
               $data['orderid'] = ''; 
               $data['is_minor'] = 'no';
               $data['amount'] = $amount;   



               $data['form49a'] = '';
               $data['form49b'] = '';
               $data['apliidproof'] = '';
               $data['raidproof'] = '';            

 
               if( !empty($data['id']) ){
               $getdata = $this->c_model->getSingle('dt_pancard',['md5(id)'=>$data['id']],' * '); 
               $data['datalist'] = $getdata;
               $data['id'] = $getdata['id'];
               /*manage redirect start*/
               if($getdata['attemptstatus']=='new' && !$data['odr']){
                redirect( base_url($this->folder.'/'.$this->pagename));
               }
               /*manage redirect end*/

               $data['applicant_type'] = $getdata['applicant_type'];
               $data['add_date'] = date('d/m/Y',strtotime($getdata['add_date']));
               $data['name_title'] = $getdata['name_title'];
               $data['first_name'] = $getdata['first_name'];
               $data['middle_name'] = $getdata['middle_name'];
               $data['last_name'] = $getdata['last_name'];
               $data['name_on_pancard'] = $getdata['name_on_pancard'];
               $data['parent_type'] = $getdata['parent_type'];
               $data['par_title'] = $getdata['par_title'];
               $data['par_first_name'] = $getdata['par_first_name'];
               $data['par_middle_name'] = $getdata['par_middle_name'];
               $data['par_last_name'] = $getdata['par_last_name'];
                $data['contact'] = $getdata['contact'];
               $data['email'] = $getdata['email'];
               $data['pan_dispatch_stateid'] = $getdata['pan_dispatch_stateid'];
               $data['address_comunication'] = $getdata['address_comunication'];
               $data['name_on_aadhar'] = $getdata['name_on_aadhar'];
               $data['aadhar_no'] = $getdata['aadhar_no'];
               $data['dob_incorporate_year'] = date('d/m/Y',strtotime($getdata['dob_incorporate_year']));
               $data['gender'] = $getdata['gender'];
               $data['is_minor'] = $getdata['is_minor'];
               $data['rep_title'] = $getdata['rep_title'];
               $data['rep_first_name'] = $getdata['rep_first_name'];
               $data['rep_middle_name'] = $getdata['rep_middle_name'];
               $data['rep_last_name'] = $getdata['rep_last_name'];
               $data['rep_flat_door_block'] = $getdata['rep_flat_door_block'];
               $data['rep_build_vill_permis'] = $getdata['rep_build_vill_permis'];
               $data['rep_road_street_post'] = $getdata['rep_road_street_post'];
               $data['rep_area_subdevision'] = $getdata['rep_area_subdevision'];
               $data['rep_stateid_ut'] = $getdata['rep_stateid_ut'];
               $data['rep_country'] = $getdata['rep_country'];
               $data['rep_district_town'] = $getdata['rep_district_town'];
               $data['rep_pin_zipcode'] = $getdata['rep_pin_zipcode'];
               $data['id_proof'] = $getdata['id_proof'];
               $data['address_proof'] = $getdata['address_proof'];
               $data['dob_proof'] = $getdata['dob_proof'];
               $data['c_flat_door_block'] = $getdata['c_flat_door_block'];
               $data['c_build_vill_permis'] = $getdata['c_build_vill_permis'];
               $data['c_road_street_post'] = $getdata['c_road_street_post'];
               $data['c_area_subdevision'] = $getdata['c_area_subdevision'];
               $data['c_city_district'] = $getdata['c_city_district'];
               $data['c_stateid_ut'] = $getdata['c_stateid_ut'];
               $data['c_pincode'] = $getdata['c_pincode'];
               $data['c_country'] = $getdata['c_country'];
               $data['countrycode'] = $getdata['countrycode'];
               $data['approvaldate'] = $getdata['approvaldate'];
               $data['status'] = $getdata['status'];
               $data['proccessing_fee'] = $getdata['proccessing_fee'];
               $data['ao_cityarea'] = $getdata['ao_cityarea'];
               $data['ao_ward'] = $getdata['ao_ward'];
               $data['ao_areacode'] = $getdata['ao_areacode'];
               
               $data['ao_type'] = $getdata['ao_type'];
               $data['ao_rangecode'] = $getdata['ao_rangecode'];
               $data['ao_no'] = $getdata['ao_no'];
               $data['pancardno'] = $getdata['pancardno'];
               $data['category'] = $getdata['category'];
               $data['income_soure'] = $getdata['income_soure'];
               $data['verification_place'] = $getdata['verification_place'];
               $data['ackno'] = $getdata['ackno'];
               $data['orderid'] = $getdata['orderid'];  
                

                $data['uploadlist'] = $this->dg_model->getAll('dt_uploads',null,array('tableid'=>$getdata['id']));  
              }


               $data['redirect'] = base_url($this->folder.'/'.$this->pagename.'?id='.$id.'&tab='.$data['tab'] );  
                agview('individual_pan',$data); 

	}







  public function saveupdate(){
    $comi_array = $this->c_model->getSingle('dt_set_commission',['operatorid'=>24,'serviceid'=>8],'surcharge_fixed,surcharge_percent');
    $deductAmount = $comi_array['surcharge_fixed']; 

            $deductAmount = $deductAmount?$deductAmount:0;
            $checkwallet = checkwallet();

            if($checkwallet < $deductAmount ){
            $this->session->set_flashdata('error','Wallet Balance is low for this Transaction!');
              redirect(ADMINURL.$this->folder.'/individual_pan');    
            }

            $sessionid  = $this->session->userdata('sessionid');
            $pdata      = xss_clean($this->input->post());
            //echo '<pre>';
            //print_r($pdata); //exit;
            $saveup['orderid'] = 'PAN'.date('YmdHis').rand(100,999); 
            $saveup['pancardno']        = isset($pdata['pancardno'])?$pdata['pancardno']:NULL;



            $saveup['applicant_type']     = $pdata['applicant_type'];  
            $saveup['name_title']         = isset($pdata['name_title'])?$pdata['name_title']:'';
            $saveup['first_name']         = $pdata['first_name'];
            $saveup['middle_name']        = $pdata['middle_name'];
            $saveup['last_name']          = $pdata['last_name'];
            $saveup['name_on_pancard']    = $pdata['name_on_pancard'];
            $saveup['parent_type']        = $pdata['parent_type'];
            $saveup['par_title']          = $pdata['par_title'];
            $saveup['par_first_name']     = $pdata['par_first_name'];
            $saveup['par_middle_name']    = $pdata['par_middle_name'];
            $saveup['par_last_name']      = $pdata['par_last_name'];
            
           
            $saveup['contact'] = $pdata['contact'];
            
            $saveup['email']      = $pdata['email'];
            $saveup['pan_dispatch_stateid']    = $pdata['pan_dispatch_stateid'];
            $saveup['address_comunication']    = $pdata['address_comunication'];
            $saveup['name_on_aadhar']   = $pdata['name_on_aadhar'];
            $saveup['aadhar_no']        = $pdata['aadhar_no'];
            
            $date = str_replace('/', '-', $pdata['dob_incorporate_year']);
            $saveup['dob_incorporate_year'] = date('Y-m-d',strtotime($date));
            
            $saveup['gender']           = $pdata['gender'];
            $saveup['is_minor']         = $pdata['is_minor'];
            $saveup['rep_title']        = $pdata['rep_title'];
            $saveup['rep_first_name']   = $pdata['rep_first_name'];
            $saveup['rep_middle_name']  = $pdata['rep_middle_name'];
            $saveup['rep_last_name']    = $pdata['rep_last_name'];
            $saveup['rep_flat_door_block']      = $pdata['rep_flat_door_block'];
            $saveup['rep_build_vill_permis']    = $pdata['rep_build_vill_permis'];
            $saveup['rep_road_street_post']     = $pdata['rep_road_street_post'];
            $saveup['rep_area_subdevision']     = $pdata['rep_area_subdevision'];
            $saveup['rep_stateid_ut']           = $pdata['rep_stateid_ut'];
            $saveup['rep_country']              = $pdata['rep_country'];
            $saveup['rep_district_town']        = $pdata['rep_district_town'];
            $saveup['rep_pin_zipcode']       = $pdata['rep_pin_zipcode'];
           // $saveup['id_proof']            = $pdata['id_proof'];
           // $saveup['address_proof']       = $pdata['address_proof'];
            
           // $saveup['dob_proof']            = $pdata['dob_proof'];
            $saveup['c_flat_door_block']    = $pdata['c_flat_door_block'];
            $saveup['c_build_vill_permis']  = $pdata['c_build_vill_permis'];
            $saveup['c_road_street_post']   = $pdata['c_road_street_post'];
            $saveup['c_area_subdevision']   = $pdata['c_area_subdevision'];
            $saveup['c_city_district']      = $pdata['c_city_district'];
            $saveup['c_stateid_ut']         = $pdata['c_stateid_ut'];
            $saveup['c_pincode']            = $pdata['c_pincode'];
            $saveup['c_country']            = $pdata['c_country'];
            $saveup['countrycode']          = $pdata['countrycode'];
           // $saveup['approvaldate']         = $pdata['approvaldate'];
           // $saveup['status']               = $pdata['status'];
            $saveup['proccessing_fee']      = $deductAmount;
            $saveup['ao_cityarea']          = $pdata['ao_cityarea'];
            $saveup['ao_ward']              = $pdata['ao_ward'];
            $saveup['ao_areacode']          = $pdata['ao_areacode'];
            $saveup['ao_type']              = $pdata['ao_type'];
            $saveup['ao_rangecode']         = $pdata['ao_rangecode'];
            $saveup['ao_no']                = $pdata['ao_no'];
          //  $saveup['pancardno']            = $pdata['pancardno'];
            $saveup['category']             =  'new'; 
            $saveup['income_soure']         = $pdata['income_soure'];
            $saveup['verification_place']   = $pdata['verification_place']; 
            $saveup['attemptstatus']   = 'temp'; 

            $id = $pdata['id'];
            if(!$id){
               $saveup['addby_userid']      = $this->session->userdata('id');
               $saveup['add_date']          = date('Y-m-d H:i:s');
            }

           // print_r( $saveup ); //exit;
            
    if(!empty($pdata['ao_cityarea']) && !empty($pdata['contact']) && !empty($pdata['email']) && !empty($pdata['name_on_aadhar'])){
        
        if($id){
        $lastid = $this->c_model->saveupdate('dt_pancard', $saveup,null,['id'=>$id]);
        $lastid = $id;  
        }else{
        $lastid = $this->c_model->saveupdate('dt_pancard', $saveup, $saveup ); 
        }

         
        
        if($lastid){    
 
        redirect(ADMINURL.$this->folder.'/pan_new_uploads?id='.md5($lastid) );
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