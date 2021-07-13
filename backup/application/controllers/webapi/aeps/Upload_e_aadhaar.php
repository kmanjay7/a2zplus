<?php defined("BASEPATH") OR exit("No Direct Access Allowed!");

class Upload_e_aadhaar extends CI_Controller{
	
	public function __construct(){
		parent::__construct();
		}
		
	
				
public function uploadimage(){
 
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Content-Type'); 


		header("Pragma: no-cache");
		header("Cache-Control: no-cache");
		header("Expires: 0");

if( ($_SERVER['REQUEST_METHOD'] == 'POST') ){

       $post = $this->input->post();  
       $filename = 'eaadhaar';
       $userid = isset($post['userid'])?$post['userid']:false;

       if(!$userid){
          $response['status'] = false;
          $response['message'] = 'User Not Exists';
          header("Content-Type: application/json");
          echo json_encode( $response );
          exit;
       }
        
       $compress = false; 
       $documenttype = 'eaadhaar';

       $response = [];
       


       $oldimage = false;
       $updateupload = null;
       $uploadstatus = false;
       if( $userid && $documenttype ){
          $oldwhere['documenttype'] = $documenttype;
          $oldwhere['tableid'] = $userid;
          $oldwhere['usertype'] = 'AGENT';
          $olddata = $this->c_model->getSingle('dt_uploads', $oldwhere ,'*' );
          
          
            if( !empty($olddata) && $olddata['id'] ){
               $updateupload['id'] = $olddata['id'];
               $oldimage = $olddata['documentorimage'];
            } 
         // exit;
       }
      

       /*image upload script start here */
       $new_image_name = false;
         if( isset($_FILES[$filename]['name'])){
              if (!is_dir('uploads')) { mkdir('./uploads', 0777, true);} 

                $checkfile = $_FILES[$filename]['name']; 
                $ext = pathinfo($checkfile, PATHINFO_EXTENSION);

                $config['upload_path'] = './uploads/';  
                $config['allowed_types'] = 'jpg|png|jpeg'; 
                $config['encrypt_name'] = false;  
                $config['overwrite'] = TRUE; 
                $config['file_name'] = $userid.'_eadh.'.$ext;
                   
                $this->load->library('upload', $config);  
                if(!$this->upload->do_upload($filename)){  
                     $message = $this->upload->display_errors();  
                     $uploadstatus = false; 
                     $response['status'] = false;
                     $response['message'] = strip_tags($message); 
                     
                }else{  
                     $data = $this->upload->data();  
                     $new_image_name = $data["file_name"]; 
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
 

        $isave['tableid']         =  $userid;
        $isave['usertype']        =  'AGENT';
        $isave['documenttype']    =  $documenttype;
        $isave['documentorimage'] =  $new_image_name; 
        $isave['uploaddate']    =  date('Y-m-d H:i:s');
        $isave['add_by']        =  $userid;
        $isave['verifystatus']  =  'yes';
        $isave['status']        =  'yes'; 
        $saveupdate = ($new_image_name && $userid) ? $this->c_model->saveupdate('dt_uploads', $isave,null ,$updateupload ) : '';  

        if($saveupdate){
           $response['status'] = true; 
           $response['message'] = 'Image Uploaded Successfully'; 
        }
 
 }else{

           $response['status'] = false;
           $response['message'] = 'Bad Request'; 
 }

  header("Content-Type: application/json");
  echo json_encode( $response );  

}
		
}
?>