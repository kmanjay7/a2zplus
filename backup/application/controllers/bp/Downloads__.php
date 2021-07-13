<?php defined('BASEPATH') OR exit('No direct script access allowed');



class Downloads extends CI_Controller{

	public function __construct(){
		parent::__construct();
        $this->load->library('session'); 
               $this->load->library('pagination');
               bpsession_check();
		}

     public function index(){ 
		     $userid = $this->session->userdata('id');
		     $data['title'] = 'Downloads';
             
             bpview('downloads',$data);
	}   

}?>