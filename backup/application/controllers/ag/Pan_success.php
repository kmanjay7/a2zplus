<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Pan_success extends CI_Controller{
	var $folder;
    var $pagename;
	public function __construct(){
		parent::__construct(); 
        $this->load->library('session');  
                $this->folder = 'ag';
                $this->pagename = 'Pan_success';
		}
	
	public function index(){ 
	        
		        $data['title'] = 'Transaction Status';
                $data['folder'] = $this->folder;
                $data['pagename'] = $this->pagename; 
                $data['orderid'] = '';

				if( $id = $this->input->get('id') ){
 				   $getdata = $this->c_model->getSingle('dt_pancard',['md5(id)'=>$id],'orderid');
 				   $data['orderid'] = $getdata; 
				}
              
                agview('pan_success',$data); 

	}



}
?>