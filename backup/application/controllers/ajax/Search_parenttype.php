<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Search_parenttype extends CI_Controller {
    
  public function __construct(){
    parent::__construct(); 
    $this->load->model('Common_model','dg_model');
    $this->load->library('session'); 
  }
       

   public function index(){  
        $parentid = $this->session->userdata('id');
        $usertype = array();
        $id = $this->input->post('id');
        if($id){
            $where['user_type'] = $id;
            $where['parentid'] = $parentid;
            $where['fromdate <='] = date('Y-m-d H:i:s');
            $where['todate >='] = date('Y-m-d H:i:s');
            $query = $this->dg_model->getAll('users', 'id ASC', $where ,'id,ownername' );
        if(!empty($query)){
           $usertype = $query; 
        }else if($id == $this->session->userdata('user_type')){
            $where = [];
            $where['id'] = $parentid;
            $where['user_type'] = $this->session->userdata('user_type');
            $where['fromdate <='] = date('Y-m-d H:i:s');
            $where['todate >='] = date('Y-m-d H:i:s');
            $usertype = $this->dg_model->getAll('users', 'id ASC', $where ,'id,ownername' ); 
        }
        }
        echo json_encode($usertype);
	} 
        




        public function findunique(){  

        $id = $this->input->post('id');
        if($id){
        $user = $this->dg_model->getSingle('users',array('id'=>$id),'uniqueid');
        echo $user;
        }

        }



        
        public function findtype(){
        $alluer = array();
        $id = $this->input->post('pnumber');
        if($id){
            $where['fromdate <='] = date('Y-m-d H:i:s');
            $where['todate >='] = date('Y-m-d H:i:s');
            $where['uniqueid'] = $id;
            $where['parentid'] = $this->session->userdata('id');
        $alluer = $this->dg_model->getSingle('users',$where,'*');

        }
        echo json_encode($alluer);
        }
}
?>