<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Terms extends CI_Controller{
	
	public function __construct(){
		parent::__construct(); 
		}
	

	public function index(){ 
		$data = array(); 
		$data['list'] = $this->c_model->getSingle('dt_cms', ['pagetype'=>'tc','status'=>'yes'], '*' );

		$this->load->view('terms',$data);


	}
}
?>