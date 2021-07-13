<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Downloads extends CI_Controller{
	
	public function __construct()
    {
		parent::__construct();
		$this->load->library('session');  
        $this->load->model("general_model");
        bpsession_check();
        sessionunique();
	}

    function index()
    {
        $data['title'] = 'Downloads';
        $data['folder'] = 'bp';
        $data['pagename'] = 'downloads';
        $data["rows"] = $this->general_model->getAll("downloads", "`usertype`='".$this->session->userdata("user_type")."' or `usertype`='' or `usertype`='null'");
        bpview('downloads',$data);
    }


}