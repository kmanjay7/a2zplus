<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Forgot extends CI_Controller{
	
	public function __construct(){
		parent::__construct(); 
               $this->load->model('Common_model','dg_model');
               $this->load->library('session'); 
		}


	
	public function index(){ 
        $data['title'] = 'Login Page';
        $this->load->view('forget_password',$data);
	}
        

  public function check(){
      $post = $this->input->post();
      $ckeck['uniqueid']  = trim($post['mobile']); 
      $ckeck['user_type !=']  = 'BP';
      $ckeck['user_type'] = trim($post['usertype']);
      $arrayData = $this->dg_model->getSingle('users',$ckeck,'uniqueid,id,mobileno,ownername');

     if(!empty($arrayData) && !empty($arrayData['id'])) {
          $uniqueid = $arrayData['uniqueid'];
          $mobileno = $arrayData['mobileno'];
          $where['id'] = $arrayData['id'];
          $otp = rand(1000,9999);
          $updt['otp'] = $otp;
          $updt['otp_time'] = date('Y-m-d H:i:s');
          $updt['firebaseid'] = '';
          $updt['imeidevice'] = '';
          $updt['loginstatus'] = 'no';

          $this->c_model->saveupdate('dt_users',$updt,null,$where );
          /*send update msg to agent start*/
if($arrayData['id'] && $arrayData['mobileno'] ){
$msgbodyup = 'Dear '.strtoupper($arrayData['ownername']).', Your reset password OTP is '.$otp.'.
Regards,
DigiCash India.';
    $sendsms = simplesms($mobileno,$msgbodyup);
}
    /*send update msg to agent end*/

          $data = array('uniqueidx'=> $arrayData['id'] );
          $this->session->set_userdata($data);
          $this->session->set_flashdata('success', 'OTP sent at your registered Mobile Number!!!');
          redirect(ADMINURL.'forgot/reset'); 
      } else {
          $this->session->set_flashdata('error', 'Mobile Number did not match!!!');
          redirect(ADMINURL.'forgot');  
      }
  }
        
       

  public function reset(){
            $data['title'] = 'Reset Password';
	          $this->load->view('reset',$data);
           }




  public function resetpass(){
          $post = $this->input->post(); 
          $id = $this->session->userdata('uniqueidx');
            if(!$id){
              redirect(ADMINURL.'forgot'); 
            }
          
          $password = trim($post['password']);
          $cpassword = trim($post['cpassword']);
          $otp = trim($post['otp']);

          if( $password != $cpassword ){
             $this->session->set_flashdata('success', 'Password and Confirm password didnot match!');
              //redirect(ADMINURL.'forgot/reset'); 
          }else if( !$otp ){
             $this->session->set_flashdata('success', 'OTP is Blank!');
              //redirect(ADMINURL.'forgot/reset'); 
          }

          $where['id'] = $id;
          $where['otp'] = $otp;
          $where['otp_time <='] = date('Y-m-d H:i:s',strtotime( date('Y-m-d H:i:s').' +4 minutes')); 
          $check = $this->c_model->countitem('dt_users',$where );

          if( !$check ){
             $this->session->set_flashdata('success','OTP did not match or expired!');
              redirect(ADMINURL.'forgot/reset'); 
          }

          if(($password == $cpassword) && $check && !empty($id)){
 
           $this->c_model->saveupdate('dt_users',array('password'=>$password,'en_password'=>md5($password),'imeidevice'=>'','loginstatus'=>'no'),null, ['id'=>$id ] );   
           $this->session->set_flashdata('success', 'your password has been changed successfully!!');
           redirect(ADMINURL.'login');
           
          }else{
           $this->session->set_flashdata('error', 'OTP did not match or expired !!!');
            redirect(ADMINURL.'forgot');     
          }
        }
        
        
}
?>