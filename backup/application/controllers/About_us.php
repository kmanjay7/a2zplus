<?php defined('BASEPATH') OR exit('No direct script access allowed');

class About_us extends CI_Controller{
	
	public function __construct(){
		parent::__construct(); 
		$this->load->library('session'); 
		}
	
	public function index(){ 
		$data = array();
		$data['title'] = 'About Us';

		$this->load->view('about_us',$data);


	}
}
?>