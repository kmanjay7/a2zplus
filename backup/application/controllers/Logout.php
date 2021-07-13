<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Logout extends CI_Controller {
  var $redirect;
     public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->redirect = 'https://bp.mydigicash.in/'; 
      }

	public function index()
	{

        $user_type = getloggeduserdata('user_type');

        isset($dataxx)?$this->session->unset_userdata($dataxx):'';
        $this->session->sess_destroy();
        if($user_type=='BP'){
          redirect( $this->redirect );
        }

        redirect( ADMINURL.'login' );
        
	}
}
