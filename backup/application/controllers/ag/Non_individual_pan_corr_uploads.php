<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Non_individual_pan_corr_uploads extends CI_Controller{
	  var $folder;
    var $pagename;
	public function __construct(){
		parent::__construct(); 
        $this->load->library('session'); 
               $this->load->model('Common_model','dg_model'); 
               $this->load->helper('security');
                agsession_check();
                $this->folder = 'ag';
                $this->pagename = 'Non_individual_pan_corr_uploads';
                ini_set('mermory_limit', '512MB');
		}
	
	public function index(){ 
	        
	          $data['title'] = 'Upload Pancard Documents ';
            $data['folder'] = $this->folder;
            $data['pagename'] = $this->pagename;
              

               $data['id'] = $this->input->get('id');
               $data['tab'] = '';
               $id = $this->input->get('id'); 
               $data['is_minor'] = '';
               $data['orderid'] = '';
               $data['attemptstatus'] = ''; 


               if( !empty($data['id']) ){
                $getdata = $this->c_model->getSingle('dt_pancard',['md5(id)'=>$data['id']],'id,is_minor,orderid,attemptstatus');  
                $data['id'] = $getdata['id']; 
                $data['is_minor'] = $getdata['is_minor'];
                $data['orderid'] = $getdata['orderid'];
                $data['attemptstatus'] = $getdata['attemptstatus']; 

                if( !in_array($getdata['attemptstatus'],['temp','hold'])){
                  redirect( ADMINURL.'ag/pancard_hold' ); 
                }

               $data['uploadlist'] = $this->dg_model->getAll('dt_uploads',null,array('tableid'=>$getdata['id'],'usertype'=>'PAN'));  
              }


               $data['redirect'] = base_url( $this->folder.'/'.$this->pagename.'?id='.$id );  

               /*get pancard surcharge start*/
               $comi_array = $this->c_model->getSingle('dt_set_commission',['operatorid'=>'25','serviceid'=>'8','user_type'=>'AGENT','scheme_type'=>$this->session->userdata('scheme_type') ],'surcharge_fixed,surcharge_percent');
               $data['surcharge'] = $comi_array['surcharge_fixed']; 
               /*get pancard surcharge start*/


               agview('non_individual_pan_corr_uploads',$data); 

	}



public function upload(){
       $post = $this->input->post();  
       $filename = $post['filename'];
       $id = $post['id'];
       $redirect = $post['redirect'];
       $compress = false;

       if( $post['filename'] == 'appforma' ){
            $documenttype = 'Application Form';
            $compress = true;
       } else if( $post['filename'] == 'addphoto' ){
            $documenttype = 'Photo';
            $compress = true;
       }else{
            $documenttype = $post['filename'];
            $compress = true;
       }


     $oldimage = false;
     $updateupload = null;
     $uploadstatus = false;
     if( $id && $documenttype ){
        $oldwhere['documenttype'] = $documenttype;
        $oldwhere['tableid'] = $id;
        $olddata = $this->c_model->getSingle('uploads', $oldwhere ,'*' );
        
        $oldimage = $olddata['documentorimage'];
          if( $olddata['id'] ){
             $updateupload['id'] = $olddata['id'];
          } 
       // exit;
     }
      

       /*image upload script start here */
       $new_image_name = false;
         if( isset($_FILES[$filename]['name'])){
              if (!is_dir('panuploads')) { mkdir('./panuploads', 0777, true);}

                  
                  $checkfile = $_FILES[$filename]['tmp_name'];
                 
    list($width, $height) = getimagesize( $checkfile );

    if($documenttype != 'Photo'){

    list($width_x, $height_y) = $this->get_dpi($checkfile); 

    if( ($width_x != 200) && ($height_y != 200) ) { 
    $this->session->set_flashdata('error','Image resulation must be 200dpi' );
    redirect( $redirect ); 
    }
  }
                  
              
                $config['upload_path'] = './panuploads/';  
                $config['allowed_types'] = 'jpg|jpeg|png'; 
                $config['encrypt_name'] = TRUE;  
                $config['overwrite'] = TRUE;  
                //$config['max_size'] = '44800';
                //$config['min_size'] = '100';
                //$config['width']  = '1300';
                //$config['max_height']  = '1700';
                //$config['min_width']  = '500';
                //$config['min_height']  = '400';
               
                   
                $this->load->library('upload', $config);  
                if(!$this->upload->do_upload($filename)){  
                     $message = $this->upload->display_errors();  
                     $uploadstatus = false;
                     $this->session->set_flashdata('error',strip_tags($message) );
                     redirect( $redirect );
                }else{  
                     $data = $this->upload->data();  
                     $new_image_name = $data["file_name"];
                     $config1['image_library'] = 'gd2';  
                     $config1['source_image'] = './panuploads/'.$data["file_name"];  
                     $config1['create_thumb'] = FALSE;  
                     $config1['maintain_ratio'] = true;  
                     $config1['quality'] = '100%';
                     $config1['width']  = $width;
                     $config1['height']  = $height; 
                     $config1['new_image'] = './panuploads/'.$data["file_name"];  
                     $this->load->library('image_lib', $config1); 
                     $this->image_lib->initialize($config1); 
                     $this->image_lib->resize(); 
                     $this->image_lib->clear();
                     $uploadstatus = true; 

                }  
                
             /*image upload script start here */
              
    if ( $uploadstatus && ($uploadstatus == true) && $oldimage ){
            $deletimagepath = ("panuploads/".$oldimage );
                    if(is_file($deletimagepath) && file_exists($deletimagepath)){ 
                        $unlink = unlink( $deletimagepath ); 
                    }
               }
             
         }

 


        $isave['tableid']         =  $id;
        $isave['usertype']        =  'PAN';
        $isave['documenttype']    =  $documenttype;
        $isave['documentorimage'] =  $new_image_name; 
        $isave['uploaddate']    =  date('Y-m-d H:i:s');
        $isave['add_by']        =  $this->session->userdata('id');
        $isave['verifystatus']  =  'no';
        $isave['status']        =  'yes'; 
        $saveupdate = ($new_image_name && $id) ? $this->c_model->saveupdate('uploads', $isave,null ,$updateupload ) : '';  

        if($saveupdate){
          //$this->session->set_flashdata('success','Image Uploaded Succesfully!' );
        }
 
        redirect( $redirect );


}

public function finalsubmit(){
  $post = $this->input->post();

  $is_hold = $post['attemptstatus']?$post['attemptstatus']:false;
 // print_r($post); 
  $operatorid = '25';
  $loggeduerid = $this->session->userdata('id');


if($is_hold != 'temp'){ /*old was hold*/
 $update = $this->c_model->saveupdate('dt_pancard', ['attemptstatus'=>'reupload','reuploadon'=>date('Y-m-d H:i:s') ],null ,['id'=>$post['id'] ] );
 $update = $post['id'] ? $this->c_model->saveupdate('dt_uploads',['verifystatus'=>'yes'],null, ['tableid'=>$post['id'],'usertype'=>'PAN','add_by'=>$loggeduerid ]) : '';
 $this->session->set_flashdata('success','Your application have been submitted for verification!' );
redirect( base_url('ag/pancard_hold' ) );
}


$comi_array = $this->c_model->getSingle('dt_set_commission',['operatorid'=>$operatorid,'serviceid'=>'8','user_type'=>'AGENT','scheme_type'=>$this->session->userdata('scheme_type') ],'surcharge_fixed,surcharge_percent');
$deductAmount = $comi_array['surcharge_fixed']; 

$deductAmount = $deductAmount ? $deductAmount : 0;
$checkwallet = checkwallet();

if( ($checkwallet < $deductAmount) ){
$this->session->set_flashdata('error','Wallet Balance is low for this Transaction!');
redirect(ADMINURL.$this->folder.'/individual_pan_corr_uploads?id='.md5($post['id']) );  
exit;  
}
  
  

  $chwk_wt = [];
  $chwk_wt['userid'] = $loggeduerid;
  $chwk_wt['referenceid'] = $post['orderid']; 
  $chwk_wt['credit_debit'] = 'debit';
  $chwk_wt['subject'] = 'pan';
  $countwt = $this->c_model->countitem('dt_wallet',$chwk_wt ); 


/* check wallet for this registration start */ 
        $wtsave['userid'] = $loggeduerid;
        $wtsave['usertype'] = $this->session->userdata('user_type');
        $wtsave['uniqueid'] = $this->session->userdata('uniqueid'); 
        $wtsave['paymode'] = 'wallet';
        $wtsave['transctionid'] = 'NA';
        $wtsave['credit_debit'] = 'debit';
        $wtsave['upiid'] = NULL;
        $wtsave['bankname'] = NULL; 
        $wtsave['remark'] = 'Pancard Registration';
        $wtsave['status'] = 'success'; 
        $wtsave['amount'] = $deductAmount; 
        $wtsave['subject'] = 'pan';
        $wtsave['addby'] = $loggeduerid;
        $wtsave['orderid'] = $post['orderid'];
        $wtsave['odr'] = $deductAmount;
        $wtsave['flag'] = 1;

        $postapiurl = base_url('webapi/wallet/Creditdebit');
        $upwtwhere['dts'] = $wtsave;  
        $header = array('auth: Access-Token='.WALLETOKEN );
    if( $countwt == 0 ){
        $upwt = $wtsave['amount'] ? curlApis($postapiurl,'POST', $upwtwhere,$header ):array();
        if(isset($upwt['status']) && $upwt['status']){ 
        $update = $this->c_model->saveupdate('dt_pancard', ['attemptstatus'=>'new','amount'=>$deductAmount,'operatorid'=>$operatorid ],null ,['id'=>$post['id'] ] );
        $update = $post['id'] ? $this->c_model->saveupdate('dt_uploads',['verifystatus'=>'yes'],null,['tableid'=>$post['id'],'usertype'=>'PAN','add_by'=>$loggeduerid ]) : '';
        redirect( base_url('ag/pan_success?id='.md5($post['id']) ) );
        }
    }else if($countwt == 1 ){
       $update = $post['id'] ? $this->c_model->saveupdate('dt_uploads',['verifystatus'=>'yes'],null, ['tableid'=>$post['id'],'usertype'=>'PAN','add_by'=>$loggeduerid ]) : ''; 

       redirect( base_url('ag/pan_success?id='.md5($post['id']) ) );
    }
        

        
        /* check wallet for this registration end */
 


      redirect(ADMINURL.$this->folder.'/individual_pan_corr_uploads?id='.md5($post['id']) ); 
}


public function get_dpi($filename){
    $a = fopen($filename,'r');
    $string = fread($a,20);
    fclose($a);

    $data = bin2hex(substr($string,14,4));
    $x = substr($data,0,4);
    $y = substr($data,0,4);

    return array(hexdec($x),hexdec($y));
} 


}
?>