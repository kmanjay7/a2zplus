<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Upload extends CI_Controller {
   public function __construct(){
    parent::__construct(); 
    $this->load->library('session');
    $this->load->model('Common_model','dg_model');
    
    }


   function index(){
       $sessionid = $this->session->userdata('sessionid');
       $query = $this->dg_model->delete('uploads',array('tableid'=>$sessionid));
       
        $config['upload_path']="./uploads";
        $config['allowed_types']='gif|jpg|png|jpeg';
        $config['encrypt_name'] = TRUE;
        
        $this->load->library('upload',$config);
	    if($this->upload->do_upload("file")){
	        $data = array('upload_data' => $this->upload->data());

                $judul = $this->input->post('judul');
                $image = $data['upload_data']['file_name']; 
                
                
                $isave['tableid'] = $sessionid;
                $isave['usertype'] = '1';
                $isave['documentorimage'] = $image;
                $isave['documenttype'] = '';
                $isave['uploaddate'] = date('Y-m-d H:i:s');
                $isave['add_by'] = '';
                $isave['verifystatus'] = 'no';
                $isave['status'] = 'yes';
                $saveupdate = $this->dg_model->saveupdate('uploads', $isave); 
                
	        echo json_decode($result);
        }

     }
     
     public function documentsave(){

        $config['upload_path']="./uploads";
        $config['allowed_types']='gif|jpg|png|jpeg';
        $config['encrypt_name'] = TRUE;
        
        $this->load->library('upload',$config);

        $tableid = $this->input->post('tableid');
        $doctype = $this->input->post('doctype');
        $usertype = $this->input->post('usertype');
        $dd = $this->dg_model->getSingle('uploads',array('tableid'=>$tableid,'usertype'=>$usertype,'documenttype'=>$doctype),'*');;
	        
                
	    if($this->upload->do_upload("file")){
	        $data = array('upload_data' => $this->upload->data());
                $judul= $this->input->post('judul');
	        $image= $data['upload_data']['file_name']; 
                //print_r($this->input->post());
                if(!empty($image)){
                  $file = './uploads/'.$dd['documentorimage'];
                if(is_file($file) && @unlink($file)){
                $this->session->set_flashdata('success','Images successfully!!!');
                }
                }
                
                
                $isave['tableid'] = $tableid;
                $isave['usertype'] = $usertype;
                $isave['documentorimage'] = !empty($image)? $image :$dd['documentorimage'];
                $isave['documenttype'] = $doctype;
                $isave['uploaddate'] = date('Y-m-d H:i:s');
                $isave['add_by'] = '';
                $isave['verifystatus'] = 'no';
                $isave['status'] = 'no';
                
                
                if(!empty($dd)){
                 $saveupdate = $this->dg_model->saveupdate('uploads', $isave,null, array('id'=>$dd['id']));  
                }else{
                 $saveupdate = $this->dg_model->saveupdate('uploads', $isave);    
                }

        }
        echo !empty($image) ? $image :$dd['documentorimage'];
     }



     public function approval(){
        $config['upload_path']="./uploads";
        $config['allowed_types']='gif|jpg|png';
        $config['encrypt_name'] = TRUE;
        
        $this->load->library('upload',$config);
	    if($this->upload->do_upload("file")){
	        $data = array('upload_data' => $this->upload->data());

	        $judul= $this->input->post('judul');
	        $image= $data['upload_data']['file_name'];
                 }
	        $tableid = $this->input->post('tableid');
                $doctype = $this->input->post('doctype');
                $usertype = $this->input->post('usertype');
                $verifystatus = $this->input->post('verifystatus');
                $dd = $this->dg_model->getSingle('uploads',array('tableid'=>$tableid,'usertype'=>$usertype,'documenttype'=>$doctype),'*');
                
                $isave['tableid'] = $tableid;
                $isave['usertype'] = $usertype;
                $isave['documentorimage'] = !empty($image)? $image :$dd['documentorimage'];
                $isave['documenttype'] = $doctype;
                $isave['uploaddate'] = date('Y-m-d H:i:s');
                $isave['add_by'] = '';
                $isave['verifystatus'] = $verifystatus;
                $isave['status'] = 'no';
                
                
                if(!empty($dd)){
                 $saveupdate = $this->dg_model->saveupdate('uploads', $isave,null, array('id'=>$dd['id']));  
                }else{
                 $saveupdate = $this->dg_model->saveupdate('uploads', $isave);    
                } 
                
               echo !empty($image) ? $image :$dd['documentorimage'];
	        
       
     }



 public function getbankifsc(){
   $post = $this->input->post();
   $res = $this->c_model->getSingle('dt_bank',array('id'=>$post['id']),'master_ifsc');
    if(!empty($res)){
     echo $res;
    }
 }


}
?>