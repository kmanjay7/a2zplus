<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Joindigicash extends CI_Controller{
	
	public function __construct(){
		parent::__construct(); 
		$this->load->library('session'); 

	}
	

	public function index(){ 
		$data = array();

		$data['title'] = 'Join Digicash India';
		$data['postdata'] = 'Joindigicash'; 

    	/* get City Drop Down List */
    	$select = 'a.id, CONCAT(a.cityname, ",", b.statename ) as cityname';
    	$where['a.status'] = 'yes';
    	$from = 'city as a';
    	$join[0]['table'] = 'state as b';
    	$join[0]['joinon'] = 'a.stateid = b.id';
    	$join[0]['jointype'] = 'INNER';
    	$groupby = null;
    	$orderby = 'a.cityname';
    	$limit = null;
    	$offset = null;

    	$list = $this->c_model->joinmultiple( $select, $where, $from, $join, $groupby ,$orderby, $limit, $offset, 'get' );
    	$data['list'] = $list;
 

		$this->load->view('joindigicash',$data); 
	}




	public function save(){ 
		$post = $this->input->post();

		// print_r($_POST);die();

		$name = $post['name'];
		$mobileno = $post['mobileno'];
		$mobileno = filter_var($mobileno,FILTER_SANITIZE_NUMBER_INT );
		$email = $post['email'];
	    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$email = $email;
	    }
		$pincode = $post['pincode'];
		$pincode = filter_var($pincode,FILTER_SANITIZE_NUMBER_INT );
		$cityname = $post['cityname'];
		$comment = $post['comment'];
		$user_type = $post['user'];

        
        $redirect = ADMINURL.'Joindigicash';

		if( !$name ){
          $this->session->set_flashdata('error','Please fill fullname!');
		  redirect( $redirect );
		  exit;
		}else if( !$email ){
          $this->session->set_flashdata('error','Please enter valid email address!');
		  redirect( $redirect );
		  exit;
		}else if( !$mobileno || ( strlen($mobileno) != 10 ) ){
          $this->session->set_flashdata('error','Please 10 digit mobile no!');
          redirect( $redirect );
		  exit;
		}else if( !$pincode || ( strlen($pincode) != 6 ) ){
          $this->session->set_flashdata('error','Please 6 digit pincode no!');
          redirect( $redirect );
		  exit;
		}else if( !$cityname ){
          $this->session->set_flashdata('error','Please select cityname!');
          redirect( $redirect );
		  exit;
		}else if( !$comment ){
          $this->session->set_flashdata('error','Please enter some remark!');
          redirect( $redirect );
		  exit;
		}else if( !$user_type ){
          $this->session->set_flashdata('error','Please select user type!');
          redirect( $redirect );
		  exit;
		}



		$save['fullname'] = $name;
		$save['mobileno'] = $mobileno; 
		$save['emailid'] = $email; 
		$save['pincode'] = $pincode; 
		$save['cityname'] = $cityname; 
		$save['comment'] = $comment; 
		$save['user_type'] = $user_type; 
		//$this->c_model->saveupdate();
		

   		$html = '<table cellspacing="0" cellpadding="0">
				 <tr><td style="width:150px"><b>Name:</b></td><td>'.$name.'</td></tr>
				 <tr><td><b>Mobile No.:</b></td><td>'.$mobileno.'</td></tr>
				 <tr><td><b>Email Address: </b></td><td>'.$email.'</td></tr>
				 <tr><td><b>Pincode:</b></td><td>'.$pincode.'</td></tr>
				 <tr><td><b>Cityname:</b></td><td>'.$cityname.'</td></tr>
				 <tr><td valign="top"><b>User type:</b></td><td>'.$user_type.'</td></tr>
				 <tr><td valign="top"><b>Comment:</b></td><td>'.$comment.'</td></tr>
   				 </table>';

   		

   		$to =  INFOMAIL;
   		$subject = 'Request on: '.date('d-M-Y h:i:s A').' |Digicash India';
   		$file = null;
   		$replyto = null;
   		$from = 'noreply@mydigicash.in';
   		$mailer = 'noreply@mydigicash.in';
   		$cc = false;
   		$bcc = false;

   		$send = sendmail($to,$subject,$html ,$file,$replyto,$from,$mailer,$cc,$bcc );
		 
       if($send){
       	  $this->session->set_flashdata('success','Our Representative will contact you soon!');
          redirect( $redirect );
       }else{
       	  $this->session->set_flashdata('error','Some Internal Error!');
          redirect( $redirect );
       }
        


  
	}




}
?>