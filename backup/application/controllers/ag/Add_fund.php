<?php defined('BASEPATH') OR exit('No direct script access allowed');



class Add_fund extends CI_Controller{
    var $folder;
    var $pagename;
	public function __construct(){
		parent::__construct();
        $this->load->library('session'); 
               $this->load->library('pagination');
               agsession_check(); 
               $this->folder = $this->uri->segment(1);
               $this->pagename = 'Add_fund'; 
		}

     public function index(){  

		     $data['title'] = 'Add Fund'; 
             $data['folder'] = $this->folder;
             $data['pagename'] = $this->pagename;
             
             $data['id'] = getloggeduserdata('id');
             $data['uniqueid'] = getloggeduserdata('uniqueid');
             $data['user_type'] = getloggeduserdata('user_type');
             $data['ownername'] = getloggeduserdata('ownername');
             $data['emailid'] = getloggeduserdata('emailid');
             $data['uniquecode'] = getloggeduserdata('uniquecode'); 
                        
            agview('add_fund',$data);
	 } 


 public function gotopaytm(){
     $data['folder'] = $this->folder;
     $data['pagename'] = $this->pagename;

     $post = $this->input->post(); 
     
     $amount = $post['amount']; 

     $param['user_id'] = getloggeduserdata('id');
     $param['user_type'] = getloggeduserdata('user_type');
     $param['amount'] = $amount; 
     if($param['user_id'] && $param['user_type'] && $param['amount']){
        echo '<form  method="post" action="'.ADMINURL.'Gotopaytm" name="f1" id="form">'; 
            foreach($param as $name => $value) { 
        echo '<input type="hidden" name="' . $name .'" value="' . $value . '">'; 
        echo '<br/>';
            }
  
        echo '</form>
                    <button type="submit" class="btn btn-warning" ></button>
                    <script> document.getElementById("form").submit();
                    </script>'; 
     }else{
        $this->session->set_flashdata('error','Please fill all required fields!');
        redirect( ADMINURL.$data['folder'].'/'.$data['pagename'] );
     } 

    

 }


}?>