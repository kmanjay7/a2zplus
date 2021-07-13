<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Agent extends CI_Controller{
	public function __construct(){
		parent::__construct(); 
        $this->load->library('session');
               $this->load->model('Common_model','dg_model'); 
               $this->load->library('pagination');
               bpsession_check();
		}

	public function index($id=false){ 

            /* check subscription plan start*/
            if( checkSubscription( ['id'=>getloggeduserdata('id')] )){ 
            redirect( ADMINURL.'bp/Subscribe_plan');
            } 
            /* check subscription plan end*/
	        

            $data['title'] = ' Add AGENT';
            $id = $this->input->get('id');
            $page = $this->input->get('page');
            $parentid = $this->input->get('parentid');
             $bpdata   = $this->dg_model->getSingle('users',array('md5(id)'=>$id),'*');
             $cityname = !empty($bpdata['cityid'])?$this->dg_model->getSingle('dt_city',array('id'=>$bpdata['cityid']),'cityname'):'';
             $count = $this->dg_model->getSingle('users',null,'id', 'id DESC','1');
             $uniqid = 900 + $count + 1;
             $uniqid = 'AG'.$uniqid;  
             $parent = !empty($bpdata['parentid'])?$this->dg_model->getSingle('users',array('id'=>$bpdata['parentid']),'*'):'';
             $data['parenttype']      = !empty($parent['user_type']) ? $parent['user_type'] : '';
             $data['parentownername']     = !empty($parent['ownername']) ? $parent['ownername'] : '';
             $data['parentownerid']     = !empty($parent['id']) ? $parent['id'] : '';
             $data['parentid1']      = !empty($parent['uniqueid']) ? $parent['uniqueid'] : '';
             $data['id']            = !empty($id) ? $id : '';
             $data['page']          = !empty($page) ? $page : '';
             $data['parentid']      = !empty($parentid) ? $parentid : '';
             $data['uniqueid']      = !empty($bpdata['uniqueid']) ? $bpdata['uniqueid'] : '';
             $data['ownername']     = !empty($bpdata['ownername']) ? $bpdata['ownername'] : '';
             $data['firmname']      = !empty($bpdata['firmname']) ? $bpdata['firmname'] : '';
             $data['mobileno']      = !empty($bpdata['mobileno']) ? $bpdata['mobileno'] : '';
             $data['emailid']       = !empty($bpdata['emailid']) ? $bpdata['emailid'] : '';
             $data['pancard']       = !empty($bpdata['pancard']) ? $bpdata['pancard'] : '';
             $data['aadharno']      = !empty($bpdata['aadharno']) ? $bpdata['aadharno'] : '';
             $data['scheme_type']   = !empty($bpdata['scheme_type']) ? $bpdata['scheme_type'] : '';
             $data['scheme_amount'] = !empty($bpdata['scheme_amount']) ? $bpdata['scheme_amount'] : '';
             $data['pincode'] = !empty($bpdata['pincode']) ? $bpdata['pincode'] : '';
             $data['dob'] = !empty($bpdata['dob']) ? date('m/d/Y',strtotime($bpdata['dob'])) : '';
             $data['address']       = !empty($bpdata['address']) ? $bpdata['address'] : '';
             $data['stateid']       = !empty($bpdata['stateid']) ? $bpdata['stateid'] : '';
             $data['cityid']        = !empty($bpdata['cityid']) ? $bpdata['cityid'] : '';
             $data['cityname']      = !empty($cityname) ? $cityname : '';
             $data['unique_code']   = !empty($bpdata['uniquecode']) ? $bpdata['uniquecode'] : $uniqid;
             $data['buttonname']      = !empty($id) ? 'Update' : 'Add';
             $data['admin'] = $this->dg_model->getSingle('users',array('id'=>1),'*');
             $data['state'] = $this->dg_model->getAll('dt_state','statename ASC',array('status'=>'yes'));
             $data['scheme'] = $this->dg_model->getAll('dt_scheme','sch_name ASC',array('status'=>'yes','user_type'=>'AGENT'));
		bpview('add-agent',$data);
	}



     public function view($page=false){ 

        if($this->input->get()){
         echo $get = $this->input->get('search_by');
          if( filter_var($get, FILTER_SANITIZE_NUMBER_INT )){
              $where['uniqueid'] = $get;
          }else if( filter_var($get, FILTER_SANITIZE_STRING )){
              $where['ownername'] = $get;
          } 
        }

                $parentid = $this->session->userdata('id');
                $where['user_type'] = 'AGENT';
                $where['parentid'] = $parentid;


               
		        $data['title'] = 'Agent List';
                $start = $page;
                $limit = 10;
                $base_url = (ADMINURL.'bp/Agent/view/');
                $countItem = $this->dg_model->countitem('users',$where );
                $data["pagination"] = pagination($base_url, $countItem, $limit);
                
                $data['aguser']  = $this->dg_model->getfilter('users',$where,$limit,$start, 'id DESC');
                $data['total'] =$countItem;
		        bpview('list-agent',$data);
	}




         public function save($id=false){
             $data['title'] = 'Agent ';
             $id = $this->input->get('id');
             $page = $this->input->get('page');
             $parentidx = $this->input->get('parentid');
             $pdata = $this->input->post();
              $parentid = $this->dg_model->getSingle('users',array('uniqueid'=>$pdata['parentid']),'id');
             $bpregd   = $this->dg_model->getSingle('users',array('md5(id)'=>$id),'*');
             $oldmobileno = $id ? $bpregd['mobileno'] : false; 

             $saveup['parenttype'] = $pdata['parentytpe'];
             $saveup['parentid'] = $parentid;
             $saveup['user_type'] = 'AGENT';
             $saveup['uniqueid'] = $pdata['mobile'];
             $saveup['ownername'] = $pdata['ownername'];
             
             $saveup['mobileno'] = $pdata['mobile'];
             
             $saveup['scheme_type'] = $pdata['schemid'];
             $saveup['scheme_amount'] = $pdata['schemeamount'];
             

             if(!empty($id))
             {
                $saveup['firmname'] = $pdata['firmname'];
                $saveup['emailid'] = $pdata['emailid'];
                 $saveup['pancard'] = strtoupper($pdata['pancard']);
                 $saveup['aadharno'] = $pdata['adharcard'];
                 $saveup['pincode'] = $pdata['pincode'];
                 if(!empty($pdata['dob'])){
                    $saveup['dob'] = str_replace('/', '-', $pdata['dob']);
                    $saveup['dob'] = date('Y-m-d',strtotime($saveup['dob']));
                 }
                 $saveup['address'] = $pdata['address'];
                 $saveup['stateid'] = $pdata['stateid'];
                 $saveup['cityid'] = $pdata['cityid'];
             }

              if(empty($id)){
                $password = generateStrongPassword($length = 6, $add_dashes = false, $available_sets = 'luds');
             $saveup['password'] = $password;
             $saveup['en_password'] = md5($password);
             }
             if( empty( $id ) ){
              $saveup['status'] =  'yes';
              $saveup['register_date'] = date('Y-m-d H:i:s');
             }
             $where = !empty($id) ? array('md5(id)'=>$id) : null;
             $dpcheck = empty($id) ? (['uniqueid'=>$saveup['uniqueid'],'user_type'=>'AGENT']):null;
             $update = $this->dg_model->saveupdate('dt_users', $saveup,$dpcheck,$where); 
             $lid = $this->db->insert_id();

/*send msg to agent start*/
if(!$id){
$msgbody = 'Dear '.strtoupper($saveup['ownername']).', Thank you for joining DigiCash India. Login Id- '.$saveup['uniqueid'].' & Password- '.$password.' to login at mydigicash.in.
Regards,
DigiCash India.';
$sendsms = simplesms($saveup['mobileno'],$msgbody);
}
/*send msg to agent end*/

/*send update msg to agent start*/
if($id && $oldmobileno != $saveup['mobileno'] ){
$msgbodyup = 'Dear '.strtoupper($saveup['ownername']).', Your user information registered with us got updated as per your request.
Regards,
DigiCash India.';
$sendsms = simplesms($saveup['mobileno'],$msgbodyup);
}
/*send update msg to agent end*/

             if(!empty($update)){
                 $uniqid = 900 + $lid;
                 $uniqid = 'AG'.$uniqid;
               $dataaa = array('uniquecode'=>$uniqid);  
              $update = $this->dg_model->saveupdate('dt_users', $dataaa, NULL, array('id'=>$lid));
              if($page == '1'){
              $this->session->set_flashdata('success','Done Successfully!!!!');
              redirect(ADMINURL.'bp/Addon_ag_list/view/'.$parentidx);  
              }else{
              $this->session->set_flashdata('success','Done Successfully!!!!');
              redirect(ADMINURL.'bp/agent/view');      
              }
             }else{
               $this->session->set_flashdata('error','Something Went Wrong!');
                 redirect(ADMINURL.'bp/agent/view');     
             }
        }
}

?>