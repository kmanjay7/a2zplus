<?php defined("BASEPATH") OR exit("No Direct Access Allowed!");

class Upload_ackslip extends CI_Controller{
	
	public function __construct(){
		parent::__construct();
		}
		
	
				
public function uploadimage(){


  if (isset($_SERVER["HTTP_ORIGIN"]) === true) {
  $origin = $_SERVER["HTTP_ORIGIN"];
  $allowed_origins = array(
    "https://myadmin.mydigicash.in",
    "http://myadmin.mydigicash.in",
    "https://www.myadmin.mydigicash.in",
    "http://www.myadmin.mydigicash.in"
  );
  if (in_array($origin, $allowed_origins, true) === true) {
    header('Access-Control-Allow-Origin: ' . $origin);
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Content-Type');
  }
   
}




		header("Pragma: no-cache");
		header("Cache-Control: no-cache");
		header("Expires: 0");

if( ($_SERVER['REQUEST_METHOD'] == 'POST') ){

       $post = $this->input->post();  
       $filename = $post['filename'];
       $id = $post['id'];
       $acknumber = $post['ackno'];
       $agentid = isset($post['agentid'])?$post['agentid']:'';
        
       $compress = false; 
       $documenttype = 'Ack Slip';

       $response = [];
       


       $oldimage = false;
       $updateupload = null;
       $uploadstatus = false;
       if( $id && $documenttype ){
          $oldwhere['documenttype'] = $documenttype;
          $oldwhere['tableid'] = $id;
          $oldwhere['usertype'] = 'PAN';
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
              if (!is_dir('panuploads')) { mkdir('./panuploads', 0777, true);} 

                $checkfile = $_FILES[$filename]['name']; 

                $config['upload_path'] = './panuploads/';  
                $config['allowed_types'] = 'pdf'; 
                $config['encrypt_name'] = false;  
                $config['overwrite'] = TRUE; 
                $config['file_name'] = $acknumber.'_ackslip.pdf';

                /*delete old Pdf start here*/
                if ( $oldimage ){
                    $deletimagepath = ("panuploads/".$oldimage );
                    if(is_file($deletimagepath) && file_exists($deletimagepath)){ 
                        $unlink = unlink( $deletimagepath ); 
                    }
                }
                /*delete old Pdf end here*/

                   
                $this->load->library('upload', $config);  
                if(!$this->upload->do_upload($filename)){  
                     $message = $this->upload->display_errors();  
                     $uploadstatus = false;

                     $response['status'] = 'error';
                     $response['message'] = strip_tags($message); 
                     
                }else{  
                     $data = $this->upload->data();  
                     $new_image_name = $data["file_name"]; 
                     $uploadstatus = true;  
                }  
                
             /*image upload script start here */ 
             
         }


         /*check new uploaded file in folder start here*/
                if ( $new_image_name ){
                    $imgpath = ("panuploads/".$new_image_name );
                    if(!is_file($imgpath) && !file_exists($imgpath)){ 
                       $response['status'] = false;
                       $response['message'] = 'Please Try Again';
                       echo json_encode( $response ); 
                       exit;  
                    }
                }
        /*check new uploaded file in folder start here*/


 

        $isave['tableid']         =  $id;
        $isave['usertype']        =  'PAN';
        $isave['documenttype']    =  $documenttype;
        $isave['documentorimage'] =  $new_image_name; 
        $isave['uploaddate']    =  date('Y-m-d H:i:s');
        $isave['add_by']        =  $agentid;
        $isave['verifystatus']  =  'yes';
        $isave['status']        =  'yes'; 
        $saveupdate = ($new_image_name && $id) ? $this->c_model->saveupdate('dt_uploads', $isave,null ,$updateupload ) : '';  

        if($saveupdate){
            $where = [];
            $up['attemptstatus'] = 'approved';
            $up['ackno'] = $acknumber;
            $where['id'] = $id;
            $update = $this->c_model->saveupdate('dt_pancard',$up,null, $where );
            $update = $this->c_model->saveupdate('dt_uploads',['verifystatus'=>'yes'],null, ['tableid'=>$id ] );

           $response['status'] = 'success';
           $response['message'] = 'Image Uploaded Successfully'; 
        }
 
 }else{

           $response['status'] = false;
           $response['message'] = 'Bad Request'; 
 }

  echo json_encode( $response ); 


}


		
}
?>