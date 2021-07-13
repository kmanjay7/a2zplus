<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Privacy_policy extends CI_Controller{
	
	public function __construct(){
		parent::__construct(); 
		}
	

	public function index(){ 
		$data = array();

		$data['title'] = 'Privacy Policy';

		$data['list'] = $this->c_model->getSingle('dt_cms', ['pagetype'=>'pp','status'=>'yes'], '*' );

		$this->load->view('privacy_policy',$data);


	}
}
?>