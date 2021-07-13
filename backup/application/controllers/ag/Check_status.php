<?php defined('BASEPATH') OR exit('No direct script access allowed');



class Check_status extends CI_Controller{
	public function __construct(){
		parent::__construct();
		$this->load->library('session');   
               agsession_check();  
		}

public function index(){
	$param = $this->input->get();
	if(!empty($param)){
		$data['odr'] = '';
	    $data['redirecton'] = '';
	    $data['ajaxurl'] = '';
		
		if($param['trtype']=='dmt'){
			$data['odr'] = $param['telto'];
			$data['redirecton'] = ADMINURL.'ag/print_reciept?utp='.md5( $data['odr'] );
			$data['ajaxurl'] = ADMINURL.'ag/print_reciept/confirm';
		}

		else if($param['trtype']=='bbps'){
			$data['odr'] = $param['telto'];
			$data['redirecton'] = ADMINURL.'ag/bbps_reciept?utp='.md5( $data['odr'] );
			$data['ajaxurl'] = ADMINURL.'ag/bbps_reciept/confirm';
		}

	agview('check_status',$data);  
	}else{  redirect( ADMINURL.'ag/dashboard'); }


  
}

}?>