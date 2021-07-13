<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Certificate extends CI_Controller{
    
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        mdsession_check();   
    }

    public function index()
    {
        $data['title'] = 'Certificate';
        $data['folder'] = 'md';
        $data['pagename'] = 'Certificate';
        $id = getloggeduserdata('id');
        $data["user"] = $this->c_model->getSingle("dt_users", ["id"=>$id ],'*');
        mdview('certificate', $data);
    }  
 

}