<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Contact_us extends CI_Controller{
	
	public function __construct(){
		parent::__construct();
		$this->load->library('session');  
		}
	
	public function index(){ 
		$data = array();
		$data['title'] = 'Contact Us';

		$this->load->view('contact_us',$data);


	}
}
?>