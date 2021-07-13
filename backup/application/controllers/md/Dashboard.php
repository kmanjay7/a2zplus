<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller{
	
	public function __construct(){
		parent::__construct(); 
		$this->load->library('session');
                mdsession_check();
                sessionunique();
                $this->load->model("general_model");
		}
	
	public function index(){ 
		$data = array();
		$data['title'] = 'Dashboard Page';
		
		$where="(`usertype` like '".$this->session->userdata("user_type")."' OR usertype='') AND ";
        $where.="((CONCAT(`from_date`,' ', `from_time`) <='".date("Y-m-d H:i:s")."' AND CONCAT(`end_date`,' ', `end_time`)>='".date("Y-m-d H:i:s")."') OR (`from_date` is NULL AND `end_date` is NULL))";
        $data["notifications"]=$this->general_model->getAll("notification", $where, "", "id desc");
        
        $where="(`usertype` like '".$this->session->userdata("user_type")."' OR usertype='') AND ";
        $where.="((CONCAT(`from_date`,' ', `from_time`) <='".date("Y-m-d H:i:s")."' AND CONCAT(`end_date`,' ', `end_time`)>='".date("Y-m-d H:i:s")."') OR (`from_date` is NULL AND `end_date` is NULL))";
        $data["banner"]=$this->general_model->getAll("banner", $where, "", "id desc");
        
		mdview('dashboard',$data);
	}
}
?>